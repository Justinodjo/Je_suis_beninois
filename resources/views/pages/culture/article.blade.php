{{-- ═══════════════════════════════════════════════════════
     ARTICLE DÉTAILLÉ — Culture / Actualités
     Hero + contenu complet + galerie médias (images & vidéos)
     + likes + commentaires + articles similaires cliquables
     Route: GET /culture/article/{slug} → CultureController@show
     Variables: $article, $relatedArticles
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', $article->titre.' — Je Suis Béninois')

@push('styles')
<style>
/* ══════════ HERO ARTICLE ══════════ */
.article-hero {
    position: relative;
    min-height: 380px;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    background: #0a1a0a;
}
.article-hero-bg { position:absolute; inset:0; }
.article-hero-bg img { width:100%; height:100%; object-fit:cover; opacity:.55; }
.article-hero-overlay {
    position:absolute; inset:0;
    background: linear-gradient(to top, rgba(0,0,0,.9) 0%, rgba(0,0,0,.4) 60%, transparent 100%);
}
.article-hero-content { position:relative; z-index:2; padding:60px 0 40px; max-width:760px; }
.article-hero-breadcrumb { display:flex; align-items:center; gap:8px; font-size:.75rem; color:rgba(255,255,255,.55); margin-bottom:16px; }
.article-hero-breadcrumb a { color:var(--jaune); }
.article-hero-breadcrumb span { color:rgba(255,255,255,.3); }
.article-hero-cats { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:14px; }
.article-hero-title {
    font-family: var(--font-h);
    font-size: 2.4rem;
    font-weight: 700;
    color:#fff;
    line-height:1.2;
    margin-bottom:16px;
}
.article-hero-meta { display:flex; gap:18px; flex-wrap:wrap; font-size:.82rem; color:rgba(255,255,255,.75); }
.article-hero-meta i { margin-right:6px; color:var(--jaune); }

/* Bouton like + lien login (hero) */
#likeBtn {
    display: none;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 20px;
    border: 1.5px solid rgba(255,255,255,.5);
    background: transparent;
    color: #fff;
    font-weight: 600;
    font-size: .82rem;
    cursor: pointer;
    margin-top: 14px;
    transition: background .18s, color .18s;
}
#loginToLike {
    display: none;
    margin-top: 14px;
    color: var(--jaune);
    font-size: .82rem;
}

/* ══════════ LAYOUT CONTENU + SIDEBAR ══════════ */
.article-body-section { padding:56px 0; }
.article-layout { display:grid; grid-template-columns:1fr 300px; gap:48px; align-items:start; }

.article-content { font-size:.92rem; line-height:1.85; color:var(--text-l); }
.article-content p { margin-bottom:18px; }
.article-content img { width:100%; border-radius:var(--radius); margin:20px 0; }

/* Tags */
.article-tags { margin-top:32px; display:flex; gap:8px; flex-wrap:wrap; }

/* Partage social */
.article-share {
    margin-top:28px; padding-top:24px; border-top:1px solid var(--border);
    display:flex; align-items:center; gap:12px;
}
.article-share-btn {
    width:36px; height:36px; border-radius:50%; color:#fff;
    display:flex; align-items:center; justify-content:center;
}

/* ══════════ GALERIE MÉDIAS (images + vidéos de l'article) ══════════ */
.article-galerie { margin-top:40px; }
.article-galerie h3 { font-family:var(--font-h); font-size:1.15rem; font-weight:700; margin-bottom:16px; }
.article-galerie-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
.article-galerie-item { position:relative; border-radius:8px; overflow:hidden; cursor:pointer; aspect-ratio:4/3; }
.article-galerie-item img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.article-galerie-item:hover img { transform:scale(1.06); }
.article-galerie-overlay {
    position:absolute; inset:0; background:rgba(0,0,0,.35);
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity .25s; color:#fff; font-size:1.3rem;
}
.article-galerie-item:hover .article-galerie-overlay { opacity:1; }
.article-galerie-play {
    width:44px; height:44px; background:rgba(255,255,255,.9); border-radius:50%;
    display:flex; align-items:center; justify-content:center; color:var(--vert);
}

/* Vidéos intégrées (lecteur inline) */
.article-videos { margin-top:32px; display:flex; flex-direction:column; gap:20px; }
.article-video-embed {
    position:relative; border-radius:var(--radius); overflow:hidden;
    aspect-ratio:16/9; background:#000;
}
.article-video-embed video, .article-video-embed iframe {
    width:100%; height:100%; border:0; object-fit:cover;
}

/* ══════════ COMMENTAIRES ══════════ */
.article-comments { margin-top:40px; padding-top:32px; border-top:1px solid var(--border); }
.article-comments h3 { font-family:var(--font-h); font-size:1.15rem; font-weight:700; margin-bottom:20px; }
#commentForm { display:none; margin-bottom:28px; }
#commentForm textarea {
    width:100%; border:1px solid var(--border); border-radius:8px;
    padding:12px; font-size:.85rem; resize:vertical; font-family:inherit;
}
#loginToComment { display:none; font-size:.85rem; color:var(--gris-t); margin-bottom:28px; }
#commentsList { display:flex; flex-direction:column; gap:18px; }
.comment-item { display:flex; gap:12px; }
.comment-item img { width:38px; height:38px; border-radius:50%; flex-shrink:0; object-fit:cover; }
.comment-author { font-size:.82rem; font-weight:700; }
.comment-time { font-size:.72rem; color:var(--gris-t); margin-bottom:4px; }
.comment-body { font-size:.85rem; color:var(--text-l); line-height:1.6; }

/* ══════════ SIDEBAR ══════════ */
.article-sidebar {}
.sidebar-related {
    background:#fff; border-radius:var(--radius); box-shadow:var(--shadow);
    overflow:hidden; margin-bottom:24px;
}
.sidebar-related-header { padding:16px 20px; border-bottom:1px solid var(--border); }
.sidebar-related-header h3 { font-size:.92rem; font-weight:700; }
.sidebar-related-item {
    display:flex; gap:12px; padding:14px 20px; border-bottom:1px solid var(--gris-c);
    transition:background .18s;
}
.sidebar-related-item:hover { background:var(--gris-c); }
.sidebar-related-item:last-child { border-bottom:none; }
.sidebar-related-thumb { width:64px; height:52px; border-radius:6px; overflow:hidden; flex-shrink:0; }
.sidebar-related-thumb img { width:100%; height:100%; object-fit:cover; }
.sidebar-related-title { font-size:.8rem; font-weight:600; line-height:1.35; margin-bottom:6px; }
.sidebar-related-time { font-size:.72rem; color:var(--gris-t); }

/* ══════════ SUGGESTIONS BAS DE PAGE ══════════ */
.suggestions-section { padding:52px 0; border-top:1px solid var(--border); }
.suggestions-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-top:28px; }
.suggestion-card { background:#fff; border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow); transition:.2s; }
.suggestion-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-lg); }
.suggestion-card img { width:100%; height:180px; object-fit:cover; }
.suggestion-card-body { padding:16px 18px; }
.suggestion-card-title { font-family:var(--font-h); font-size:.95rem; font-weight:700; margin-bottom:8px; line-height:1.35; }
.suggestion-card-meta { font-size:.72rem; color:var(--gris-t); margin-bottom:8px; }
.suggestion-card-cta { font-size:.78rem; font-weight:600; color:var(--vert); display:flex; align-items:center; gap:4px; }

@media (max-width:900px) {
    .article-layout { grid-template-columns:1fr; }
    .article-sidebar { order:-1; }
    .article-galerie-grid { grid-template-columns:repeat(2,1fr); }
    .suggestions-grid { grid-template-columns:1fr 1fr; }
}
@media (max-width:600px) {
    .article-hero-title { font-size:1.7rem; }
    .suggestions-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')

{{-- ════ HERO ════ --}}
<section class="article-hero">
    <div class="article-hero-bg">
        <img src="{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1400&q=80' }}"
             alt="{{ $article->titre }}">
    </div>
    <div class="article-hero-overlay"></div>

    <div class="container">
        <div class="article-hero-content">
            <div class="article-hero-breadcrumb">
                <a href="{{ route('home') }}">Accueil</a>
                <span>/</span>
                <a href="{{ route('culture.index') }}">Culture</a>
                <span>/</span>
                <span>{{ Str::limit($article->titre, 40) }}</span>
            </div>

            <div class="article-hero-cats">
                @foreach($article->categories as $cat)
                <span class="badge" style="background:{{ $cat->couleur ?? 'var(--vert)' }};color:#fff;">
                    {{ strtoupper($cat->nom) }}
                </span>
                @endforeach
            </div>

            <h1 class="article-hero-title">{{ $article->titre }}</h1>

            <div class="article-hero-meta">
                <span><i class="fa-solid fa-pen-nib"></i>{{ $article->user?->name ?? 'Admin' }}</span>
                <span><i class="fa-regular fa-calendar"></i>{{ $article->created_at?->diffForHumans() }}</span>
                <span><i class="fa-regular fa-eye"></i>{{ number_format($article->nb_vues) }}</span>
                <span><i class="fa-regular fa-heart"></i>{{ $article->nb_likes }}</span>
            </div>

            {{-- Bouton Like / lien connexion --}}
            <button id="likeBtn" data-article-id="{{ $article->id }}">
                <i class="fa-regular fa-heart"></i>
                <span id="likeCount">{{ $article->nb_likes }}</span> J'aime
            </button>
            <a id="loginToLike" href="{{ route('login') }}">
                <i class="fa-regular fa-heart"></i> Connectez-vous pour aimer cet article
            </a>
        </div>
    </div>
</section>

{{-- ════ CORPS + SIDEBAR ════ --}}
<section class="article-body-section">
    <div class="container">
        <div class="article-layout">

            {{-- ── CONTENU PRINCIPAL ── --}}
            <div class="article-content-wrapper">

                @if($article->extrait)
                <p style="font-size:1rem;font-weight:600;color:var(--text);margin-bottom:24px;border-left:4px solid var(--vert);padding-left:16px;">
                    {{ $article->extrait }}
                </p>
                @endif

                <div class="article-content">
                    {!! $article->contenu !!}
                </div>

                {{-- Tags --}}
                @if($article->tags->count() > 0)
                <div class="article-tags">
                    @foreach($article->tags as $tag)
                    <span class="badge badge-outline-vert"># {{ $tag->nom }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Partage social --}}
                <div class="article-share">
                    <span style="font-size:.82rem;font-weight:600;color:var(--text-l);">Partager :</span>
                    <a href="https://www.facebook.com/sharer?u={{ urlencode(request()->url()) }}" target="_blank"
                       class="article-share-btn" style="background:#1877F2;"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank"
                       class="article-share-btn" style="background:#000;"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="https://wa.me/?text={{ urlencode($article->titre.' '.request()->url()) }}" target="_blank"
                       class="article-share-btn" style="background:#25D366;"><i class="fa-brands fa-whatsapp"></i></a>
                </div>

                {{-- ── VIDÉOS de l'article (lecture inline) ── --}}
                @php $videos = $article->medias->where('type', 'video'); @endphp
                @if($videos->count() > 0)
                <div class="article-videos">
                    <h3 style="font-family:var(--font-h);font-size:1.15rem;font-weight:700;">Vidéos</h3>
                    @foreach($videos as $video)
                    <div class="article-video-embed">
                        @if(Str::contains($video->url, ['youtube.com','youtu.be']))
                            <iframe src="{{ str_replace('watch?v=', 'embed/', $video->url) }}" allowfullscreen></iframe>
                        @else
                            <video src="{{ $video->url }}" controls preload="metadata"></video>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- ── GALERIE PHOTOS de l'article ── --}}
                @php $images = $article->medias->where('type', 'image')->skip(1); @endphp
                @if($images->count() > 0)
                <div class="article-galerie">
                    <h3>Galerie photo</h3>
                    <div class="article-galerie-grid">
                        @foreach($images as $img)
                        <div class="article-galerie-item">
                            <img src="{{ $img->url }}" alt="{{ $article->titre }}">
                            <div class="article-galerie-overlay"><i class="fa-solid fa-magnifying-glass"></i></div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── COMMENTAIRES ── --}}
                <div class="article-comments">
                    <h3>Commentaires ({{ $article->comments->count() }})</h3>

                    <form id="commentForm" data-article-id="{{ $article->id }}">
                        <textarea name="contenu" rows="3" maxlength="1000" required
                                  placeholder="Votre commentaire..."></textarea>
                        <button type="submit" class="btn btn-vert btn-sm" style="margin-top:10px;">Publier</button>
                        <span id="commentFeedback" style="font-size:.78rem;color:var(--gris-t);margin-left:10px;"></span>
                    </form>
                    <p id="loginToComment">
                        <a href="{{ route('login') }}" style="color:var(--vert);font-weight:600;">Connectez-vous</a> pour laisser un commentaire.
                    </p>

                    <div id="commentsList">
                        @forelse($article->comments as $comment)
                        <div class="comment-item">
                            <img src="{{ $comment->user?->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($comment->user?->name ?? 'Anonyme') }}"
                                 alt="{{ $comment->user?->name }}">
                            <div>
                                <div class="comment-author">{{ $comment->user?->name ?? 'Anonyme' }}</div>
                                <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
                                <p class="comment-body">{{ $comment->contenu }}</p>
                            </div>
                        </div>
                        @empty
                        <p style="font-size:.82rem;color:var(--gris-t);">Aucun commentaire pour le moment. Soyez le premier à réagir !</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ── SIDEBAR : articles liés ── --}}
            <aside class="article-sidebar">
                <div class="sidebar-related">
                    <div class="sidebar-related-header">
                        <h3>Articles similaires</h3>
                    </div>
                    @forelse($relatedArticles as $related)
                    <a href="{{ route('culture.article', $related->slug) }}" class="sidebar-related-item">
                        <div class="sidebar-related-thumb">
                            <img src="{{ $related->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=120&q=70' }}"
                                 alt="{{ $related->titre }}">
                        </div>
                        <div>
                            <div class="sidebar-related-title">{{ Str::limit($related->titre, 60) }}</div>
                            <div class="sidebar-related-time">{{ $related->created_at?->diffForHumans() }}</div>
                        </div>
                    </a>
                    @empty
                    <div style="padding:16px 20px;font-size:.8rem;color:var(--gris-t);">
                        Aucun article similaire pour le moment.
                    </div>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ════ SUGGESTIONS BAS DE PAGE (cliquables vers d'autres articles) ════ --}}
@if($relatedArticles->count() > 0)
<section class="suggestions-section">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h2 class="section-title">À lire aussi</h2>
                <div class="section-divider"></div>
            </div>
            <a href="{{ route('culture.index') }}" class="section-link">Tout voir →</a>
        </div>

        <div class="suggestions-grid">
            @foreach($relatedArticles as $sug)
            <article class="suggestion-card">
                <a href="{{ route('culture.article', $sug->slug) }}">
                    <img src="{{ $sug->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=500&q=80' }}"
                         alt="{{ $sug->titre }}">
                </a>
                <div class="suggestion-card-body">
                    <div class="suggestion-card-title">
                        <a href="{{ route('culture.article', $sug->slug) }}">{{ Str::limit($sug->titre, 60) }}</a>
                    </div>
                    <div class="suggestion-card-meta">{{ $sug->created_at?->diffForHumans() }}</div>
                    <a href="{{ route('culture.article', $sug->slug) }}" class="suggestion-card-cta">Lire l'article →</a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const TOKEN_KEY = 'auth_token'; // ⚠️ à ajuster selon la clé réelle utilisée par votre AuthController JS
    const token = localStorage.getItem(TOKEN_KEY);

    const likeBtn      = document.getElementById('likeBtn');
    const loginToLike  = document.getElementById('loginToLike');
    const commentForm  = document.getElementById('commentForm');
    const loginComment = document.getElementById('loginToComment');

    if (!token) {
        if (loginToLike)  loginToLike.style.display  = 'inline-block';
        if (loginComment) loginComment.style.display = 'block';
        return;
    }

    const authHeaders = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };

    // Vérifie la validité du token puis affiche les contrôles + état du like
    fetch('/api/v1/me', { headers: authHeaders })
        .then(res => {
            if (!res.ok) throw new Error('invalid token');
            return fetch(`/api/v1/articles/${likeBtn.dataset.articleId}/like`, { headers: authHeaders });
        })
        .then(res => res.json())
        .then(data => {
            likeBtn.style.display = 'inline-flex';
            commentForm.style.display = 'block';
            applyLikeState(data.liked, data.nb_likes);
        })
        .catch(() => {
            localStorage.removeItem(TOKEN_KEY);
            if (loginToLike)  loginToLike.style.display  = 'inline-block';
            if (loginComment) loginComment.style.display = 'block';
        });

    function applyLikeState(liked, nbLikes) {
        document.getElementById('likeCount').textContent = nbLikes;
        likeBtn.style.background = liked ? 'var(--jaune)' : 'transparent';
        likeBtn.style.color = liked ? '#1a1a1a' : '#fff';
        likeBtn.querySelector('i').className = liked ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
    }

    likeBtn?.addEventListener('click', async () => {
        const id = likeBtn.dataset.articleId;
        const res = await fetch(`/api/v1/articles/${id}/like`, { method: 'POST', headers: authHeaders });
        const data = await res.json();
        applyLikeState(data.liked, data.nb_likes);
    });

    commentForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = commentForm.dataset.articleId;
        const contenu = commentForm.querySelector('textarea').value;
        const feedback = document.getElementById('commentFeedback');

        const res = await fetch(`/api/v1/articles/${id}/comments`, {
            method: 'POST',
            headers: { ...authHeaders, 'Content-Type': 'application/json' },
            body: JSON.stringify({ contenu }),
        });

        if (res.ok) {
            commentForm.querySelector('textarea').value = '';
            feedback.textContent = 'Commentaire envoyé, en attente de modération.';
            feedback.style.color = 'var(--vert)';
        } else {
            feedback.textContent = 'Erreur — réessayez.';
            feedback.style.color = 'var(--rouge)';
        }
    });
});
</script>
@endpush