{{-- ═══════════════════════════════════════════════════════
     TOUTES LES ACTUALITÉS
     Route: GET /actualites → HomeController@actualites
     Variables: $articles (paginé), $categories, $activeCategory
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', ($activeCategory ? $activeCategory->nom . ' — ' : '') . 'Actualités — Je Suis Béninois')

@push('styles')
<style>
.actu-hero {
    background: var(--vert);
    padding: 44px 0;
}
.actu-hero h1 {
    font-family: var(--font-titre);
    font-size: clamp(1.7rem, 3vw, 2.3rem);
    font-weight: 700;
    color: #fff;
}
.actu-hero p {
    color: rgba(255,255,255,.75);
    font-size: .9rem;
    margin-top: 8px;
}

.actu-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 32px 0 28px;
}
.actu-filter-pill {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: .82rem;
    font-weight: 600;
    border: 1.5px solid var(--border);
    color: var(--text-l);
    transition: all .2s;
}
.actu-filter-pill:hover { border-color: var(--vert); color: var(--vert); }
.actu-filter-pill.active { background: var(--vert); border-color: var(--vert); color: #fff; }

.actu-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 26px;
    margin-bottom: 40px;
}
.actu-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(15,61,20,.06);
    transition: transform .25s, box-shadow .25s;
}
.actu-card:hover { transform: translateY(-5px); box-shadow: 0 16px 32px rgba(15,61,20,.12); }
.actu-card-img { position: relative; height: 190px; overflow: hidden; }
.actu-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
.actu-card:hover .actu-card-img img { transform: scale(1.06); }
.actu-card-badge {
    position: absolute; top: 12px; left: 12px;
    background: rgba(27,94,32,.9);
    color: #fff;
    padding: 3px 11px;
    border-radius: 20px;
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
}
.actu-card-body { padding: 18px 20px; }
.actu-card-title {
    font-family: var(--font-titre);
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.35;
    margin-bottom: 8px;
    color: var(--text);
}
.actu-card-title a:hover { color: var(--vert); }
.actu-card-excerpt { font-size: .82rem; color: var(--text-l); line-height: 1.6; margin-bottom: 12px; }
.actu-card-meta { display: flex; gap: 14px; font-size: .74rem; color: var(--gris-t); }
.actu-card-meta i { margin-right: 4px; color: var(--vert); }

.actu-empty { text-align: center; padding: 60px 20px; color: var(--text-l); grid-column: 1/-1; }
.actu-empty i { font-size: 2.6rem; color: var(--border); margin-bottom: 14px; display: block; }

@media (max-width: 1024px) { .actu-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .actu-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

<section class="actu-hero">
    <div class="container">
        <h1>{{ $activeCategory ? $activeCategory->nom : 'Toutes les actualités' }}</h1>
        <p>{{ $articles->total() }} article{{ $articles->total() !== 1 ? 's' : '' }} publié{{ $articles->total() !== 1 ? 's' : '' }}</p>
    </div>
</section>

<div class="container">

    {{-- Filtres catégories --}}
    <div class="actu-filters">
        <a href="{{ route('actualites') }}" class="actu-filter-pill {{ !$activeCategory ? 'active' : '' }}">
            Toutes
        </a>
        @foreach($categories as $category)
        <a href="{{ route('actualites') }}?category={{ $category->id }}"
           class="actu-filter-pill {{ $activeCategory?->id === $category->id ? 'active' : '' }}">
            {{ $category->nom }} <span style="opacity:.7;">({{ $category->articles_count ?? 0 }})</span>
        </a>
        @endforeach
    </div>

    {{-- Grille articles --}}
    <div class="actu-grid">
        @forelse($articles as $article)
        <article class="actu-card">
            <div class="actu-card-img">
                <a href="{{ route('culture.article', $article->slug) }}">
                    <img src="{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=600&q=80' }}"
                         alt="{{ $article->titre }}">
                </a>
                @if($article->categories->first())
                <span class="actu-card-badge">{{ $article->categories->first()->nom }}</span>
                @endif
            </div>
            <div class="actu-card-body">
                <h3 class="actu-card-title">
                    <a href="{{ route('culture.article', $article->slug) }}">{{ Str::limit($article->titre, 65) }}</a>
                </h3>
                <p class="actu-card-excerpt">
                    {{ Str::limit($article->extrait ?? strip_tags($article->contenu), 100) }}
                </p>
                <div class="actu-card-meta">
                    <span><i class="fa-regular fa-calendar"></i>{{ $article->created_at?->diffForHumans() }}</span>
                    <span><i class="fa-regular fa-eye"></i>{{ number_format($article->nb_vues) }}</span>
                    <span><i class="fa-solid fa-heart"></i>{{ $article->nb_likes }}</span>
                </div>
            </div>
        </article>
        @empty
        <div class="actu-empty">
            <i class="fa-solid fa-inbox"></i>
            <p>Aucun article {{ $activeCategory ? 'dans cette catégorie' : 'publié' }} pour le moment.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div style="margin-bottom:48px;">
        {{ $articles->links() }}
    </div>

</div>

@endsection