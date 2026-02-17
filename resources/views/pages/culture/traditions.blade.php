{{-- ═══════════════════════════════════════════════════════
     CULTURE & HISTOIRE — NOS TRADITIONS
     Fidèle Images 1 & 5 : bannière, article featured, sidebar,
     galerie photo & vidéo
     Route: GET /culture/traditions → CultureController@traditions
     Variables: $articles (type=tradition), $galleryMedia
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Nos Traditions Culturelles — Je Suis Béninois')

@push('styles')
<style>
/* ══════════ BANNIÈRE HERO CULTURE ══════════ */
.culture-hero {
    position: relative;
    height: 260px;
    overflow: hidden;
    display: flex;
    align-items: center;
    background: #f5f1e8;
}
.culture-hero-bg {
    position: absolute; inset: 0;
    background:
        url('https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1400&q=70') center/cover no-repeat;
    opacity: .22;
}
/* Pattern africain en overlay */
.culture-hero-pattern {
    position: absolute; inset: 0;
    background-image:
        repeating-linear-gradient(45deg, transparent, transparent 30px,
            rgba(27,94,32,.04) 30px, rgba(27,94,32,.04) 60px),
        repeating-linear-gradient(-45deg, transparent, transparent 30px,
            rgba(255,215,0,.04) 30px, rgba(255,215,0,.04) 60px);
}
/* Artefact décoratif gauche — Image 1 */
.culture-hero-deco {
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 200px;
    background: url('https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=70') center/cover;
    opacity: .35;
    mask-image: linear-gradient(to right, black 0%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, black 0%, transparent 100%);
}
.culture-hero-content {
    position: relative; z-index: 2;
    text-align: center;
    width: 100%;
}
.culture-hero-content h1 {
    font-family: var(--font-h);
    font-size: 2.8rem;
    font-weight: 700;
    color: var(--text);
    line-height: 1.15;
}
.culture-hero-content h1 .accent { color: var(--rouge); }

/* ══════════ TRADITIONS LAYOUT (Image 1) ══════════ */
.traditions-section { padding: 56px 0; }
.traditions-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 40px;
    align-items: start;
}

/* Article featured gauche */
.featured-article {}
.featured-article-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.featured-article-header h2 {
    font-family: var(--font-h);
    font-size: 1.4rem;
    font-weight: 700;
}
.featured-btns { display: flex; gap: 8px; }
.featured-image {
    position: relative;
    border-radius: var(--radius);
    overflow: hidden;
    margin-bottom: 20px;
}
.featured-image img {
    width: 100%;
    height: 340px;
}
.featured-cats {
    display: flex;
    gap: 6px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}
.featured-cat {
    font-size: .7rem;
    font-weight: 700;
    color: var(--rouge);
    text-transform: uppercase;
    letter-spacing: .06em;
}
.featured-cat + .featured-cat::before {
    content: '·';
    margin-right: 6px;
    color: var(--border);
}
.featured-title {
    font-family: var(--font-h);
    font-size: 1.65rem;
    font-weight: 700;
    line-height: 1.25;
    color: var(--text);
    margin-bottom: 12px;
}
.featured-title .accent { color: var(--jaune-d); }
.featured-body {
    font-size: .88rem;
    color: var(--text-l);
    line-height: 1.75;
    margin-bottom: 20px;
}

/* Carte Glétons du Bénin */
.gletons-card {
    background: linear-gradient(135deg, var(--vert), var(--vert-l));
    border-radius: var(--radius);
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-top: 20px;
    color: #fff;
}
.gletons-icon {
    width: 48px; height: 48px;
    background: rgba(255,215,0,.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.gletons-text h4 { font-size: .95rem; font-weight: 700; margin-bottom: 4px; }
.gletons-text p { font-size: .78rem; color: rgba(255,255,255,.75); line-height: 1.5; }
.gletons-cta {
    margin-left: auto;
    padding: 8px 18px;
    background: var(--jaune);
    color: #1a1a1a;
    border-radius: 6px;
    font-size: .8rem;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ══════════ SIDEBAR (Image 1) ══════════ */
.traditions-sidebar {}

/* Carte mise en avant sidebar */
.sidebar-feature {
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    margin-bottom: 28px;
}
.sidebar-feature-img { position: relative; }
.sidebar-feature-img img { height: 180px; width: 100%; }
.sidebar-feature-badge {
    position: absolute;
    top: 10px; left: 10px;
}
.sidebar-feature-body { padding: 16px; background: #fff; }
.sidebar-feature-cat { font-size: .68rem; font-weight: 700; color: var(--rouge); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; }
.sidebar-feature-title { font-family: var(--font-h); font-size: 1rem; font-weight: 700; line-height: 1.3; margin-bottom: 12px; }
.sidebar-feature-cta { font-size: .8rem; color: var(--vert); font-weight: 600; display: flex; align-items: center; gap: 4px; }

/* Actualités historiques sidebar */
.sidebar-actu { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
.sidebar-actu-header {
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border);
}
.sidebar-actu-header h3 { font-size: .9rem; font-weight: 700; }
.sidebar-actu-more { font-size: .75rem; color: var(--vert); font-weight: 600; }
.sidebar-actu-item {
    display: flex;
    gap: 12px;
    padding: 14px 18px;
    border-bottom: 1px solid var(--gris-c);
    transition: background .18s;
}
.sidebar-actu-item:hover { background: var(--gris-c); }
.sidebar-actu-item:last-child { border-bottom: none; }
.sidebar-actu-thumb { width: 64px; height: 52px; border-radius: 6px; overflow: hidden; flex-shrink: 0; }
.sidebar-actu-thumb img { width: 100%; height: 100%; }
.sidebar-actu-title { font-size: .8rem; font-weight: 600; line-height: 1.35; margin-bottom: 6px; }
.sidebar-actu-time { font-size: .72rem; color: var(--gris-t); }

/* ══════════ GALERIE PHOTO & VIDÉO (Images 1, 5 bas) ══════════ */
.galerie-section {
    background: var(--vert);
    padding: 52px 0;
    margin-top: 40px;
}
.galerie-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
}
.galerie-header h2 {
    font-family: var(--font-h);
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
}
.galerie-btn {
    padding: 7px 18px;
    background: transparent;
    border: 1.5px solid rgba(255,255,255,.5);
    color: #fff;
    border-radius: 6px;
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .18s;
}
.galerie-btn:hover { background: var(--jaune); border-color: var(--jaune); color: #1a1a1a; }

/* Grille galerie 6 items — Images 1 & 5 */
.galerie-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: 200px 200px;
    gap: 8px;
}
.galerie-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
}
.galerie-item img { width: 100%; height: 100%; transition: transform .3s; }
.galerie-item:hover img { transform: scale(1.05); }
.galerie-item.large {
    grid-column: span 2;
}
.galerie-item-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,.35);
    display: flex; align-items: center; justify-content: center;
    opacity: 0;
    transition: opacity .25s;
}
.galerie-item:hover .galerie-item-overlay { opacity: 1; }
.galerie-item-play {
    width: 48px; height: 48px;
    background: rgba(255,255,255,.9);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.galerie-footer {
    text-align: center;
    margin-top: 24px;
}

/* ══════════ RESPONSIVE ══════════ */
@media (max-width: 900px) {
    .traditions-layout { grid-template-columns: 1fr; }
    .traditions-sidebar { order: -1; }
    .galerie-grid { grid-template-columns: repeat(2, 1fr); grid-template-rows: auto; }
    .galerie-item.large { grid-column: span 1; }
}
@media (max-width: 600px) {
    .culture-hero { height: 200px; }
    .culture-hero-content h1 { font-size: 1.9rem; }
    .featured-title { font-size: 1.3rem; }
    .gletons-card { flex-direction: column; text-align: center; }
    .gletons-cta { margin-left: 0; }
    .galerie-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush

@section('content')

{{-- ════ BANNIÈRE HERO (Image 1 haut) ════ --}}
<section class="culture-hero">
    <div class="culture-hero-bg"></div>
    <div class="culture-hero-pattern"></div>
    <div class="culture-hero-deco"></div>

    <div class="culture-hero-content">
        <h1>Culture & <span class="accent">Histoire</span></h1>
    </div>
</section>
<div class="pattern-strip pattern-strip-sm"></div>

{{-- ════ SECTION TRADITIONS + SIDEBAR (Image 1 milieu) ════ --}}
<section class="traditions-section">
    <div class="container">
        <div class="traditions-layout">

            {{-- ── COLONNE GAUCHE : Article featured ── --}}
            <div class="featured-article">
                <div class="featured-article-header">
                    <h2>Nos Traditions</h2>
                    <div class="featured-btns">
                        <button class="btn btn-jaune btn-sm">Suivre Culture</button>
                        <button class="btn btn-vert btn-sm">Lire Suite</button>
                    </div>
                </div>

                @php $featured = $articles->first(); @endphp

                @if($featured)
                {{-- Image principale --}}
                <div class="featured-image">
                    <img src="{{ $featured->medias->first()?->url ?? 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=900&q=80' }}"
                         alt="{{ $featured->titre }}">
                </div>

                {{-- Catégories & tags --}}
                <div class="featured-cats">
                    @foreach($featured->categories as $cat)
                    <span class="featured-cat">{{ strtoupper($cat->nom) }}</span>
                    @endforeach
                    @foreach($featured->tags->take(3) as $tag)
                    <span class="featured-cat" style="color:var(--vert);">{{ strtoupper($tag->nom) }}</span>
                    @endforeach
                </div>

                {{-- Titre (style Image 1) --}}
                <h2 class="featured-title">
                    Valorisons Nos<br>
                    <span class="accent">Traditions Culturelles</span>
                </h2>

                <div class="featured-body">
                    {{ Str::limit(strip_tags($featured->contenu), 420) }}
                </div>

                <a href="{{ route('culture.article', $featured->slug) }}" class="btn btn-vert">
                    Lire l'article complet →
                </a>
                @else
                <div style="padding:60px 20px;text-align:center;color:var(--gris-t);">
                    <div style="font-size:3rem;margin-bottom:12px;">🥁</div>
                    <p>Aucun article de tradition disponible.</p>
                </div>
                @endif

                {{-- ── Glétons du Bénin card (Image 1) ── --}}
                <div class="gletons-card">
                    <div class="gletons-icon">🌿</div>
                    <div class="gletons-text">
                        <h4>Glétons du Bénin</h4>
                        <p>Saveur emblématique de la cuisine béninoise depuis des générations. Une feuille, cent recettes.</p>
                    </div>
                    <a href="#" class="gletons-cta">Découvrir →</a>
                </div>
            </div>

            {{-- ── SIDEBAR DROITE (Image 1) ── --}}
            <aside class="traditions-sidebar">

                {{-- Carte mise en avant --}}
                <div class="sidebar-feature">
                    <div class="sidebar-feature-img">
                        <img src="https://images.unsplash.com/photo-1567016376408-0226e4d0c1ea?w=400&q=80"
                             alt="Palais Royal">
                        <div class="sidebar-feature-badge">
                            <span class="badge badge-rouge">ÉDITION SPÉCIALE</span>
                        </div>
                    </div>
                    <div class="sidebar-feature-body">
                        <div class="sidebar-feature-cat">Patrimoine Mondial</div>
                        <h4 class="sidebar-feature-title">
                            Valorisons nos Traditions les grandes<br>
                            Actu de l'heure contemporaine
                        </h4>
                        <p style="font-size:.78rem;color:var(--text-l);margin-bottom:12px;line-height:1.5;">
                            Les palais royaux d'Abomey, classés au patrimoine mondial de l'UNESCO,
                            témoignent de la grandeur du royaume du Dahomey.
                        </p>
                        <a href="#" class="sidebar-feature-cta">Découvrir → </a>
                    </div>
                </div>

                {{-- Actualités historiques --}}
                <div class="sidebar-actu">
                    <div class="sidebar-actu-header">
                        <h3>Actualité & Histori...</h3>
                        <a href="{{ route('culture.index') }}" class="sidebar-actu-more">+50 Obs</a>
                    </div>

                    @foreach($articles->skip(1)->take(3) as $article)
                    <a href="{{ route('culture.article', $article->slug) }}" class="sidebar-actu-item">
                        <div class="sidebar-actu-thumb">
                            <img src="{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=120&q=70' }}"
                                 alt="{{ $article->titre }}">
                        </div>
                        <div>
                            <div class="sidebar-actu-title">{{ Str::limit($article->titre, 65) }}</div>
                            <div class="sidebar-actu-time">{{ $article->created_at?->diffForHumans() }}</div>
                        </div>
                    </a>
                    @endforeach

                    @if($articles->skip(1)->take(3)->count() === 0)
                    @foreach(range(1,3) as $i)
                    <div class="sidebar-actu-item">
                        <div class="sidebar-actu-thumb">
                            <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=120&q=70" alt="">
                        </div>
                        <div>
                            <div class="sidebar-actu-title">Traditions ancestrales du Bénin — Article {{ $i }}</div>
                            <div class="sidebar-actu-time">Il y a 2 heures</div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ════ GALERIE PHOTO & VIDÉO (Images 1 & 5 bas) ════ --}}
<section class="galerie-section">
    <div class="container">
        <div class="galerie-header">
            <h2>Galerie Photo & Vidéo</h2>
            <button class="galerie-btn">Voir Désout <i class="fa-solid fa-arrow-down ms-2"></i></button>
        </div>

        <div class="galerie-grid">
            {{-- Grille 6 médias : item[0] = large, reste = normal --}}
            @php
                $galImages = $galleryMedia->count() > 0 ? $galleryMedia : collect([
                    ['url' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=800&q=80', 'type' => 'image'],
                    ['url' => 'https://images.unsplash.com/photo-1567016376408-0226e4d0c1ea?w=400&q=80', 'type' => 'image'],
                    ['url' => 'https://images.unsplash.com/photo-1594476522771-1b5c0cc5e556?w=400&q=80', 'type' => 'video'],
                    ['url' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=800&q=80', 'type' => 'image'],
                    ['url' => 'https://images.unsplash.com/photo-1580130732478-4e339fb33746?w=400&q=80', 'type' => 'image'],
                    ['url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80', 'type' => 'image'],
                ]);
            @endphp

            @foreach($galImages->take(6) as $i => $media)
            <div class="galerie-item {{ $i === 0 || $i === 3 ? 'large' : '' }}">
                <img src="{{ is_object($media) ? $media->url : $media['url'] }}"
                     alt="Galerie {{ $i+1 }}">
                <div class="galerie-item-overlay">
                    @if((is_object($media) ? $media->type : $media['type']) === 'video')
                    <div class="galerie-item-play">▶</div>
                    @else
                    <div style="color:#fff;font-size:1.5rem;">🔍</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="galerie-footer">
            <button class="btn btn-outline-blanc btn-lg">
                Voir Plus <i class="fa-solid fa-arrow-down ms-2"></i>
            </button>
        </div>
    </div>
</section>

@endsection