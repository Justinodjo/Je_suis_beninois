<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * ✅ index() — liste avec filtres et pagination
     */
    public function index(Request $request)
    {
        $query = Media::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $perPage = min((int) $request->get('per_page', 24), 100);
        $medias  = $query->paginate($perPage);

        return response()->json($medias);
    }

    /**
     * ✅ show() — un seul média
     */
    public function show(Media $media)
    {
        return response()->json($media);
    }

    /**
     * ✅ store() — upload image ou vidéo
     *    Le champ s'appelle "fichier" pour correspondre au JS du dashboard
     */
    public function store(Request $request)
    {
        $request->validate([
            'fichier' => [
                'required',
                'file',
                'max:10240',
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    $allowed = ['jpeg','png','jpg','gif','webp','mp4','mov','avi'];
                    if (! in_array($ext, $allowed)) {
                        $fail('Format de fichier non autorisé.');
                    }
                },
            ],
            'nom'  => 'nullable|string|max:255',
            'type' => 'nullable|in:image,video',
        ]);

        $file     = $request->file('fichier');
        $isVideo  = $file->getMimeType() && str_starts_with($file->getMimeType(), 'video');
        $typeMedia = $request->input('type', $isVideo ? 'video' : 'image');

        // ✅ Dossier selon le type
        $folder   = $isVideo ? 'videos/articles' : 'images/articles';

        // ✅ Nom de fichier unique + nettoyé
        $extension = $file->getClientOriginalExtension();
        $filename  = time() . '_' . Str::random(8) . '.' . $extension;

        // ✅ Stockage dans storage/app/public/
        $storedPath = $file->storeAs($folder, $filename, 'public');

        // ✅ URL publique via symlink public/storage
        $url = Storage::url($storedPath);

        // ✅ Dimensions (images uniquement)
        $width = $height = null;
        if (! $isVideo) {
            $imagePath = storage_path('app/public/' . $storedPath);
            if (file_exists($imagePath)) {
                [$width, $height] = getimagesize($imagePath);
            }
        }

        // ✅ Miniature (images uniquement, sans dépendance externe)
        $thumbnailUrl = null;
        if (! $isVideo) {
            $thumbnailUrl = $this->createThumbnail($storedPath, $folder, $filename);
        }

        $media = Media::create([
            'nom'           => $request->input('nom', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
            'type'          => $typeMedia,
            'url'           => $url,
            'url_thumbnail' => $thumbnailUrl,
            'chemin'        => $storedPath,       // ← pour suppression future
            'mime_type'     => $file->getMimeType(),
            'taille'         => $file->getSize(),
            'largeur'       => $width,
            'hauteur'       => $height,
            'user_id'       => auth()->id(),
        ]);

        return response()->json(['data' => $media], 201);
    }

    /**
     * ✅ update() — renommer le média
     */
    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
        ]);

        $media->update($validated);

        return response()->json(['data' => $media]);
    }

    /**
     * ✅ destroy() — supprime le fichier physique ET la miniature
     */
   public function destroy(Media $media)
{
    // Uniquement si un fichier a réellement été stocké localement (pas une vidéo externe)
    if ($media->mime_type !== 'video/external') {
        if ($media->chemin && Storage::disk('public')->exists($media->chemin)) {
            Storage::disk('public')->delete($media->chemin);
        } elseif ($media->url && ! str_starts_with($media->url, 'http')) {
            $path = ltrim(str_replace('/storage', '', $media->url), '/');
            Storage::disk('public')->delete($path);
        }

        // Miniature locale uniquement
        $rawThumb = $media->getRawOriginal('url_thumbnail');
        if ($rawThumb && ! str_starts_with($rawThumb, 'http')) {
            Storage::disk('public')->delete($rawThumb);
        }
    }

    $media->delete();

    return response()->json(['message' => 'Média supprimé']);
}

    /**
     * ✅ Créer une miniature 300×300 sans dépendance externe (GD natif PHP)
     */
    private function createThumbnail(string $storedPath, string $folder, string $filename): ?string
    {
        try {
            $sourcePath    = storage_path('app/public/' . $storedPath);
            $thumbFolder   = 'thumbnails/' . $folder;
            $thumbRelPath  = $thumbFolder . '/' . $filename;
            $thumbFullPath = storage_path('app/public/' . $thumbRelPath);

            // Créer le dossier si nécessaire
            $dir = dirname($thumbFullPath);
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // ✅ GD natif — pas de composer require
            $info = getimagesize($sourcePath);
            if (! $info) {
                return null;
            }

            [$srcW, $srcH, $imgType] = $info;

            $src = match ($imgType) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
                IMAGETYPE_PNG  => imagecreatefrompng($sourcePath),
                IMAGETYPE_WEBP => imagecreatefromwebp($sourcePath),
                IMAGETYPE_GIF  => imagecreatefromgif($sourcePath),
                default        => null,
            };

            if (! $src) {
                return null;
            }

            // Recadrage centré 300×300
            $size    = min($srcW, $srcH);
            $offsetX = (int) (($srcW - $size) / 2);
            $offsetY = (int) (($srcH - $size) / 2);

            $thumb = imagecreatetruecolor(300, 300);

            // Transparence PNG/GIF
            if (in_array($imgType, [IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP])) {
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                imagefill($thumb, 0, 0, $transparent);
            }

            imagecopyresampled($thumb, $src, 0, 0, $offsetX, $offsetY, 300, 300, $size, $size);

            match ($imgType) {
                IMAGETYPE_JPEG => imagejpeg($thumb, $thumbFullPath, 80),
                IMAGETYPE_PNG  => imagepng($thumb, $thumbFullPath, 8),
                IMAGETYPE_WEBP => imagewebp($thumb, $thumbFullPath, 80),
                IMAGETYPE_GIF  => imagegif($thumb, $thumbFullPath),
                default        => null,
            };

            imagedestroy($src);
            imagedestroy($thumb);

            return Storage::url($thumbRelPath);

        } catch (\Throwable $e) {
            \Log::warning('Miniature non créée : ' . $e->getMessage());
            return null;
        }
    }


    public function storeExternal(Request $request)
{
    $request->validate([
        'url' => ['required', 'url'],
        'nom' => ['nullable', 'string', 'max:255'],
    ]);

    [$provider, $videoId] = $this->parseVideoUrl($request->input('url'));

    if (! $provider) {
        return response()->json([
            'message' => 'Lien non reconnu. Formats supportés : YouTube, Vimeo.',
        ], 422);
    }

    $thumbnail = match ($provider) {
        'youtube' => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
        'vimeo'   => $this->fetchVimeoThumbnail($videoId),
        default   => null,
    };

    $media = Media::create([
        'nom'           => $request->input('nom', 'Vidéo ' . ucfirst($provider)),
        'type'          => 'video',
        'url'           => $request->input('url'),
        'url_thumbnail' => $thumbnail,
        'chemin'        => null,              // pas de fichier local
        'mime_type'     => 'video/external',  // marqueur pour la suppression
        'user_id'       => auth()->id(),
    ]);

    return response()->json(['data' => $media], 201);
}

private function parseVideoUrl(string $url): array
{
    if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{11})#', $url, $m)) {
        return ['youtube', $m[1]];
    }
    if (preg_match('#vimeo\.com/(\d+)#', $url, $m)) {
        return ['vimeo', $m[1]];
    }
    return [null, null];
}

private function fetchVimeoThumbnail(string $videoId): ?string
{
    try {
        $r = \Illuminate\Support\Facades\Http::timeout(4)
            ->get("https://vimeo.com/api/v2/video/{$videoId}.json");
        return $r->successful() ? ($r->json()[0]['thumbnail_large'] ?? null) : null;
    } catch (\Throwable) {
        return null;
    }
}
}