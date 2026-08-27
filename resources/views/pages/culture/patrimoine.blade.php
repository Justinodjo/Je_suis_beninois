{{-- ═══════════════════════════════════════════════════════
     CULTURE & PATRIMOINE — Fidèle Image 3
     Hero, grille 2 grandes + 3 petites cartes, CTA bas
     Route: GET /culture/patrimoine → CultureController@patrimoine
     Variables: $patrimoines (type=patrimoine)
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Culture & Patrimoine Béninois — Je Suis Béninois')

@push('styles')
<style>
/* ══════════ HERO IMAGE 3 ══════════ */
.patrimoine-hero {
    position: relative;
    height: 320px;
    overflow: hidden;
    display: flex;
    align-items: center;
}
.patrimoine-hero-bg {
    position: absolute; inset: 0;
    background: url('https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=1400&q=80') center/cover no-repeat;
}
.patrimoine-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(
        135deg,
        rgba(27,94,32,.75) 0%,
        rgba(0,0,0,.5) 60%,
        transparent 100%
    );
}
/* Motif géométrique africain */
.patrimoine-hero-pattern {
    position: absolute; inset: 0;
    background-image:
        repeating-linear-gradient(0deg, transparent, transparent 40px,
            rgba(255,215,0,.06) 40px, rgba(255,215,0,.06) 41px),
        repeating-linear-gradient(90deg, transparent, transparent 40px,
            rgba(255,215,0,.06) 40px, rgba(255,215,0,.06) 41px);
}
.patrimoine-hero-content {
    position: relative; z-index: 2;
}
.patrimoine-hero-content h1 {
    font-family: var(--font-h);
    font-size: 2.8rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
}
.patrimoine-hero-content h1 .accent { color: var(--jaune); }
.patrimoine-hero-content p {
    color: rgba(255,255,255,.8);
    font-size: .9rem;
    margin-top: 10px;
    max-width: 480px;
}

/* ══════════ SECTION PATRIMOINE & TRADITION ══════════ */
.patrimoine-section { padding: 60px 0; }
.patrimoine-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 36px;
}
.patrimoine-section-header h2 {
    font-family: var(--font-h);
    font-size: 1.55rem;
    font-weight: 700;
}

/* Grille 2 grandes cartes (Image 3 haut) */
.patrimoine-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

/* Grille 3 petites cartes (Image 3 bas) */
.patrimoine-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

/* Card patrimoine */
.patrimoine-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: transform .2s, box-shadow .2s;
}
.patrimoine-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}
.patrimoine-card-img {
    position: relative;
    overflow: hidden;
}
.patrimoine-card-img img {
    width: 100%;
    transition: transform .4s;
}
.patrimoine-card:hover .patrimoine-card-img img { transform: scale(1.04); }
.patrimoine-card-img.tall img { height: 260px; }
.patrimoine-card-img.medium img { height: 185px; }

.patrimoine-card-badge {
    position: absolute;
    top: 12px; left: 12px;
    display: flex;
    gap: 6px;
}
.patrimoine-card-body { padding: 18px 20px; }
.patrimoine-card-meta {
    display: flex;
    gap: 10px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}
.patrimoine-card-cat {
    font-size: .68rem;
    font-weight: 700;
    color: var(--vert);
    text-transform: uppercase;
    letter-spacing: .06em;
}
.patrimoine-card-time { font-size: .68rem; color: var(--gris-t); }
.patrimoine-card-title {
    font-family: var(--font-h);
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.35;
    margin-bottom: 8px;
    color: var(--text);
}
.patrimoine-card-title a:hover { color: var(--vert); }
.patrimoine-card-excerpt {
    font-size: .8rem;
    color: var(--text-l);
    line-height: 1.6;
    margin-bottom: 14px;
}
.patrimoine-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.patrimoine-card-link {
    font-size: .78rem;
    font-weight: 600;
    color: var(--vert);
    display: flex;
    align-items: center;
    gap: 4px;
}
.patrimoine-card-link:hover { text-decoration: underline; }
.patrimoine-card-stats {
    display: flex;
    gap: 10px;
    font-size: .72rem;
    color: var(--gris-t);
}
.patrimoine-card-stats i { margin-right: 4px; }

/* ══════════ CTA BAS — "En avant notre culture" (Image 3) ══════════ */
.patrimoine-cta {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    margin: 48px 0;
    padding: 56px 48px;
    display: flex;
    align-items: center;
    gap: 48px;
    background: linear-gradient(135deg, var(--vert) 0%, var(--vert-l) 100%);
}
.patrimoine-cta::before {
    content: '';
    position: absolute; right: 0; top: 0; bottom: 0;
    width: 55%;
    background: url('https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=800&q=70') center/cover;
    opacity: .25;
    mask-image: linear-gradient(to right, transparent 0%, black 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 100%);
}
.patrimoine-cta-text {
    position: relative; z-index: 2;
    flex: 1;
}
.patrimoine-cta-text h2 {
    font-family: var(--font-h);
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.25;
    margin-bottom: 14px;
}
.patrimoine-cta-text h2 .accent { color: var(--jaune); }
.patrimoine-cta-text p {
    font-size: .88rem;
    color: rgba(255,255,255,.78);
    line-height: 1.7;
    margin-bottom: 28px;
    max-width: 420px;
}
.patrimoine-cta-btns { display: flex; gap: 12px; flex-wrap: wrap; }

/* ══════════ RESPONSIVE ══════════ */
@media (max-width: 900px) {
    .patrimoine-grid-2 { grid-template-columns: 1fr; }
    .patrimoine-grid-3 { grid-template-columns: 1fr 1fr; }
    .patrimoine-cta { flex-direction: column; padding: 36px 24px; }
    .patrimoine-cta::before { display: none; }
}
@media (max-width: 600px) {
    .patrimoine-hero { height: 220px; }
    .patrimoine-hero-content h1 { font-size: 1.9rem; }
    .patrimoine-grid-3 { grid-template-columns: 1fr; }
    .patrimoine-cta { padding: 28px 20px; }
    .patrimoine-cta-text h2 { font-size: 1.5rem; }
}
</style>
@endpush

@section('content')

{{-- ════ HERO IMAGE 3 ════ --}}
<section class="patrimoine-hero">
    <div class="patrimoine-hero-bg"></div>
    <div class="patrimoine-hero-overlay"></div>
    <div class="patrimoine-hero-pattern"></div>
    <div class="container">
        <div class="patrimoine-hero-content">
            <h1>Culture &<br><span class="accent">Patrimoine</span></h1>
            <p>Découvrez les sites, monuments et traditions qui font la richesse du Bénin.</p>
        </div>
    </div>
</section>
<div class="pattern-strip pattern-strip-sm"></div>

{{-- ════ SECTION PATRIMOINE & TRADITION (Image 3 milieu) ════ --}}
<section class="patrimoine-section">
    <div class="container">
        <div class="patrimoine-section-header">
            <div>
                <h2>Patrimoine & <span style="color:var(--vert)">Tradition</span></h2>
                <div class="section-divider"></div>
            </div>
            <a href="{{ route('culture.index') }}" class="section-link">Tout voir →</a>
        </div>

        {{-- 2 GRANDES CARTES (Image 3 haut) --}}
        <div class="patrimoine-grid-2">
            @php
                $allItems = $patrimoines->count() > 0 ? $patrimoines : collect([]);
                $fallbacks = [
                    ['titre'=>'Les Palais Royaux d\'Abomey, Patrimoine mondial de l\'UNESCO','extrait'=>'Les palais royaux d\'Abomey témoignent de la grandeur du royaume du Dahomey entre le XVIIe et le XIXe siècle.','url'=>'https://images.unsplash.com/photo-1567016376408-0226e4d0c1ea?w=700&q=80','cat'=>'Patrimoine UNESCO'],
                    ['titre'=>'Participation active à la défense de notre culture et traditions','extrait'=>'La culture béninoise est un patrimoine vivant transmis de génération en génération dans nos cérémonies et fêtes.','url'=>'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=700&q=80','cat'=>'Traditions Vivantes'],
                ];
            @endphp

            @foreach([0,1] as $idx)
            @php
                $item = $allItems->get($idx);
                $fb   = $fallbacks[$idx] ?? $fallbacks[0];
            @endphp
            <article class="patrimoine-card">
                <div class="patrimoine-card-img tall">
                    <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}">
                        <img src="{{ $item?->medias->first()?->url ?? $fb['url'] }}"
                             alt="{{ $item?->titre ?? $fb['titre'] }}">
                    </a>
                    <div class="patrimoine-card-badge">
                        <span class="badge badge-vert">{{ $fb['cat'] }}</span>
                    </div>
                </div>
                <div class="patrimoine-card-body">
                    <div class="patrimoine-card-meta">
                        <span class="patrimoine-card-cat">{{ $fb['cat'] }}</span>
                        <span class="patrimoine-card-time">{{ $item?->created_at?->diffForHumans() ?? 'Il y a 3 jours' }}</span>
                    </div>
                    <h3 class="patrimoine-card-title">
                        <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}">
                            {{ $item?->titre ?? $fb['titre'] }}
                        </a>
                    </h3>
                    <p class="patrimoine-card-excerpt">
                        {{ Str::limit($item?->extrait ?? $fb['extrait'], 140) }}
                    </p>
                    <div class="patrimoine-card-footer">
                        <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}" class="patrimoine-card-link">
                            Lire l'article →
                        </a>
                        <div class="patrimoine-card-stats">
                            <span><i class="fa-regular fa-eye"></i>{{ number_format($item?->nb_vues ?? rand(100,800)) }}</span>
                            <span><i class="fa-solid fa-heart"></i>{{ $item?->nb_likes ?? rand(20,150) }}</span>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- 3 PETITES CARTES (Image 3 bas) --}}
        @php
            $smallFallbacks = [
                ['titre'=>'S\'affirme en faisant la connexion du Bénin moderne avec ses racines','cat'=>'Société','url'=>'https://images.unsplash.com/photo-1580130732478-4e339fb33746?w=400&q=80'],
                ['titre'=>'Comment la danse Zouglou promeut l\'identité culturelle au quotidien','cat'=>'Culture','url'=>'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80'],
                ['titre'=>'Saison Creative de tout Dakar jusqu\'au nouveau Bénin culturel','cat'=>'Arts','url'=>'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=400&q=80'],
            ];
        @endphp
        <div class="patrimoine-grid-3">
            @foreach([2,3,4] as $idx)
            @php
                $item = $allItems->get($idx);
                $fb   = $smallFallbacks[$idx - 2] ?? $smallFallbacks[0];
            @endphp
            <article class="patrimoine-card">
                <div class="patrimoine-card-img medium">
                    <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}">
                        <img src="{{ $item?->medias->first()?->url ?? $fb['url'] }}"
                             alt="{{ $item?->titre ?? $fb['titre'] }}">
                    </a>
                    <div class="patrimoine-card-badge">
                        <span class="badge badge-jaune" style="color:#1a1a1a;">{{ $fb['cat'] }}</span>
                    </div>
                </div>
                <div class="patrimoine-card-body">
                    <div class="patrimoine-card-meta">
                        <span class="patrimoine-card-cat">{{ $fb['cat'] }}</span>
                        <span class="patrimoine-card-time">{{ $item?->created_at?->diffForHumans() ?? 'Il y a 1 semaine' }}</span>
                    </div>
                    <h3 class="patrimoine-card-title">
                        <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}">
                            {{ Str::limit($item?->titre ?? $fb['titre'], 75) }}
                        </a>
                    </h3>
                    <div class="patrimoine-card-footer">
                        <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}" class="patrimoine-card-link">
                            Lire →
                        </a>
                        <div class="patrimoine-card-stats">
                            <span><i class="fa-solid fa-heart"></i>{{ $item?->nb_likes ?? rand(10,80) }}</span>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>

{{-- ════ CTA "En avant notre culture et notre histoire!" (Image 3 bas) ════ --}}
<div class="container">
    <div class="patrimoine-cta">
        <div class="patrimoine-cta-text">
            <h2>
                En avant notre culture<br>
                et notre <span class="accent">histoire !</span>
            </h2>
            <p>
                Rejoignez notre communauté de passionnés de la culture béninoise.
                Partagez, valorisez et transmettez notre patrimoine aux générations futures.
            </p>
            <div class="patrimoine-cta-btns">
                <a href="#" class="btn btn-jaune btn-lg">Rejoindre le mouvement</a>
                <a href="{{ route('culture.traditions') }}" class="btn btn-outline-blanc btn-lg">Nos Traditions</a>
            </div>
        </div>
    </div>
</div>

@endsection