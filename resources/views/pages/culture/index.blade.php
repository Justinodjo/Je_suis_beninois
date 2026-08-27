@extends('layouts.app')

@section('title', 'Culture & Histoire — Je Suis Béninois')

@push('styles')
<style>
/* Réutilise le CSS de patrimoine mais adapte si tu veux */
.culture-hero {
    position: relative; height: 320px; display: flex; align-items: center; overflow: hidden;
}
.culture-hero-bg { position:absolute; inset:0; background:url('https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=1400&q=80') center/cover no-repeat; }
.culture-hero-overlay { position:absolute; inset:0; background:linear-gradient(135deg, rgba(27,94,32,.75), rgba(0,0,0,.5)); }
.culture-hero-content { position:relative; z-index:2; color:#fff; padding:0 20px; }
.culture-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px; }
.culture-grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.culture-card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:var(--shadow); transition:.3s; }
.culture-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-lg);}
.culture-card img { width:100%; object-fit:cover; transition:.3s; }
.culture-card:hover img { transform:scale(1.05); }
.culture-card-body { padding:15px; }
.culture-card-title { font-weight:700; margin-bottom:8px; }
.culture-card-excerpt { font-size:.85rem; color:var(--text-l); margin-bottom:10px; }
.culture-card-footer { display:flex; justify-content:space-between; font-size:.75rem; color:var(--gris-t);}
.culture-card-footer i { margin-right: 4px; color: var(--vert); }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="culture-hero">
    <div class="culture-hero-bg"></div>
    <div class="culture-hero-overlay"></div>
    <div class="container culture-hero-content">
        <h1>Culture & Histoire</h1>
        <p>Explorez les traditions, événements et figures qui font l’identité culturelle du Bénin.</p>
    </div>
</section>

<div class="container" style="margin-top:40px;">

    {{-- 2 grandes cartes --}}
    <div class="culture-grid-2">
        @php
            $allItems = $articles->count() > 0 ? $articles : collect([]);
            $fallbacks = [
                ['titre'=>'Les Palais Royaux d\'Abomey','extrait'=>'Découvrez les palais royaux d’Abomey, patrimoine mondial de l’UNESCO.','url'=>'https://images.unsplash.com/photo-1567016376408-0226e4d0c1ea?w=700&q=80','cat'=>'Patrimoine'],
                ['titre'=>'Fêtes et Cérémonies Traditionnelles','extrait'=>'Les rituels et fêtes béninoises sont un héritage vivant transmis depuis des siècles.','url'=>'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=700&q=80','cat'=>'Traditions'],
            ];
        @endphp
        @foreach([0,1] as $idx)
            @php
                $item = $allItems->get($idx);
                $fb   = $fallbacks[$idx];
            @endphp
            <article class="culture-card">
                <div>
                    <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}">
                        <img src="{{ $item?->medias->first()?->url ?? $fb['url'] }}" alt="{{ $item?->titre ?? $fb['titre'] }}">
                    </a>
                </div>
                <div class="culture-card-body">
                    <h3 class="culture-card-title">
                        <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}">{{ $item?->titre ?? $fb['titre'] }}</a>
                    </h3>
                    <p class="culture-card-excerpt">{{ Str::limit($item?->extrait ?? $fb['extrait'], 140) }}</p>
                    <div class="culture-card-footer">
                        <span><i class="fa-regular fa-eye"></i>{{ number_format($item?->nb_vues ?? rand(100,800)) }}</span>
                        <span><i class="fa-solid fa-heart"></i>{{ $item?->nb_likes ?? rand(10,150) }}</span>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    {{-- 3 petites cartes --}}
    <div class="culture-grid-3">
        @php
            $smallFallbacks = [
                ['titre'=>'Musique et Danses Traditionnelles','cat'=>'Culture','url'=>'https://images.unsplash.com/photo-1580130732478-4e339fb33746?w=400&q=80'],
                ['titre'=>'Artisanat et Savoir-Faire Local','cat'=>'Arts','url'=>'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80'],
                ['titre'=>'Monuments Historiques','cat'=>'Histoire','url'=>'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=400&q=80'],
            ];
        @endphp
        @foreach([2,3,4] as $idx)
            @php
                $item = $allItems->get($idx);
                $fb   = $smallFallbacks[$idx-2];
            @endphp
            <article class="culture-card">
                <div>
                    <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}">
                        <img src="{{ $item?->medias->first()?->url ?? $fb['url'] }}" alt="{{ $item?->titre ?? $fb['titre'] }}">
                    </a>
                </div>
                <div class="culture-card-body">
                    <h3 class="culture-card-title">
                        <a href="{{ $item ? route('culture.article', $item->slug) : '#' }}">{{ Str::limit($item?->titre ?? $fb['titre'], 75) }}</a>
                    </h3>
                </div>
            </article>
        @endforeach
    </div>

</div>

@endsection