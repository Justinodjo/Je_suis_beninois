{{-- ═══════════════════════════════════════════════════════
     GALERIE
     Route: GET /galerie → MediaController@index
     Variables: $medias (paginé)
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Galerie — Je Suis Béninois')

@push('styles')
<style>
.galerie-hero { background: var(--vert); padding: 44px 0; }
.galerie-hero h1 { font-family: var(--font-titre); font-size: clamp(1.7rem,3vw,2.3rem); color: #fff; }
.galerie-hero p { color: rgba(255,255,255,.75); font-size: .9rem; margin-top: 8px; }

.galerie-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 32px 0 28px;
}
.galerie-filter-pill {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: .82rem;
    font-weight: 600;
    border: 1.5px solid var(--border);
    color: var(--text-l);
    transition: all .2s;
}
.galerie-filter-pill:hover { border-color: var(--vert); color: var(--vert); }
.galerie-filter-pill.active { background: var(--vert); border-color: var(--vert); color: #fff; }

.galerie-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 48px;
}
.galerie-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 1/1;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(15,61,20,.08);
}
.galerie-item img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.galerie-item:hover img { transform: scale(1.06); }
.galerie-item .play-badge {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,.25);
    color: #fff; font-size: 1.6rem;
}

.galerie-lightbox {
    display: none;
    position: fixed; inset: 0; z-index: 999;
    background: rgba(0,0,0,.9);
    align-items: center; justify-content: center;
}
.galerie-lightbox.open { display: flex; }
.galerie-lightbox img, .galerie-lightbox video { max-width: 90vw; max-height: 85vh; border-radius: 8px; }
.galerie-lightbox-close {
    position: absolute; top: 24px; right: 32px;
    color: #fff; font-size: 2rem; cursor: pointer; background: none; border: none;
}

.galerie-empty { text-align: center; padding: 60px 20px; color: var(--text-l); grid-column: 1/-1; }
.galerie-empty i { font-size: 2.6rem; color: var(--border); margin-bottom: 14px; display: block; }

@media (max-width: 1024px) { .galerie-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px)  {
    .galerie-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .galerie-lightbox-close { top: 14px; right: 18px; font-size: 1.6rem; }
}
</style>
@endpush

@section('content')

<section class="galerie-hero">
    <div class="container">
        <h1>Galerie</h1>
        <p>{{ $medias->total() }} média{{ $medias->total() !== 1 ? 's' : '' }} — photos et vidéos de la culture béninoise</p>
    </div>
</section>

<div class="container">

    <div class="galerie-filters">
        <a href="{{ route('galerie') }}" class="galerie-filter-pill {{ !request('type') ? 'active' : '' }}">
            Tout
        </a>
        <a href="{{ route('galerie') }}?type=image" class="galerie-filter-pill {{ request('type') === 'image' ? 'active' : '' }}">
            Photos
        </a>
        <a href="{{ route('galerie') }}?type=video" class="galerie-filter-pill {{ request('type') === 'video' ? 'active' : '' }}">
            Vidéos
        </a>
    </div>

    <div class="galerie-grid">
        @forelse($medias as $media)
        <div class="galerie-item"
             data-type="{{ $media->type }}"
             data-src="{{ $media->url }}"
             onclick="openLightbox(this)">
            <img src="{{ $media->url }}" alt="{{ $media->titre ?? $media->nom ?? 'Média' }}" loading="lazy">
            @if($media->type === 'video')
            <div class="play-badge"><i class="fa-solid fa-circle-play"></i></div>
            @endif
        </div>
        @empty
        <div class="galerie-empty">
            <i class="fa-solid fa-images"></i>
            <p>Aucun média disponible pour le moment.</p>
        </div>
        @endforelse
    </div>

    <div style="margin-bottom:48px;">
        {{ $medias->links() }}
    </div>

</div>

<div class="galerie-lightbox" id="galerieLightbox">
    <button class="galerie-lightbox-close" onclick="closeLightbox()">&times;</button>
    <div id="galerieLightboxContent"></div>
</div>

@endsection

@push('scripts')
<script>
function openLightbox(el) {
    const type = el.dataset.type;
    const src = el.dataset.src;
    const content = document.getElementById('galerieLightboxContent');
    content.innerHTML = type === 'video'
        ? `<video src="${src}" controls autoplay></video>`
        : `<img src="${src}" alt="">`;
    document.getElementById('galerieLightbox').classList.add('open');
}
function closeLightbox() {
    document.getElementById('galerieLightbox').classList.remove('open');
    document.getElementById('galerieLightboxContent').innerHTML = '';
}
</script>
@endpush