@extends('layouts.app')

@section('title', 'Interviews Béninoises — Je Suis Béninois')

@push('styles')
<style>
/* Tu peux réutiliser les mêmes classes CSS que pour patrimoine si tu veux le même style */
.interview-hero {
    position: relative;
    height: 320px;
    display: flex; align-items: center; overflow: hidden;
}
.interview-hero-bg {
    position: absolute; inset: 0;
    background: url('https://images.unsplash.com/photo-1599058917217-9c8f5a5408c2?w=1400&q=80') center/cover no-repeat;
}
.interview-hero-overlay {
    position: absolute; inset:0;
    background: linear-gradient(135deg, rgba(0,0,0,.3), rgba(0,0,0,.7));
}
.interview-hero-content {
    position: relative; z-index:2; color:#fff; padding: 0 20px;
}
.interview-card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:var(--shadow); transition: .3s; }
.interview-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-lg);}
.interview-card img { width:100%; height:200px; object-fit:cover; transition: transform .3s;}
.interview-card:hover img { transform:scale(1.05);}
.interview-card-body { padding:15px; }
.interview-card-title { font-size:1rem; font-weight:700; margin-bottom:8px;}
.interview-card-excerpt { font-size:.85rem; color:var(--text-l); margin-bottom:10px;}
.interview-card-footer { display:flex; justify-content:space-between; align-items:center; font-size:.75rem; color:var(--gris-t);}
.interview-grid { display:grid; gap:24px; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); }
.mobile-tab i,
.article-card-meta i,
.hero-actions i,
.video-play-btn i {
    margin-right: 6px;
}

.article-card-meta i {
    color: var(--vert);
    font-size: .75rem;
}

.mobile-tab i {
    font-size: .85rem;
}

</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="interview-hero">
    <div class="interview-hero-bg"></div>
    <div class="interview-hero-overlay"></div>
    <div class="container interview-hero-content">
        <h1>Interviews</h1>
        <p>Rencontrez les acteurs et personnalités qui font vivre la culture béninoise.</p>
    </div>
</section>

<div class="container" style="margin-top:40px;">

    <div class="interview-grid">
        @forelse($interviews as $interview)
            <article class="interview-card">
                <a href="{{ route('interviews.show', $interview->slug) }}">
                    <img src="{{ $interview->medias->first()?->url ?? 'https://via.placeholder.com/400x200' }}" 
                         alt="{{ $interview->titre }}">
                </a>
                <div class="interview-card-body">
                    <h3 class="interview-card-title">
                        <a href="{{ route('interviews.show', $interview->slug) }}">{{ $interview->titre }}</a>
                    </h3>
                    <p class="interview-card-excerpt">{{ Str::limit($interview->extrait ?? $interview->contenu, 120) }}</p>
                    <div class="interview-card-footer">
                        <span>👁️ {{ number_format($interview->nb_vues ?? rand(100,800)) }}</span>
                        <span>❤️ {{ $interview->nb_likes ?? rand(10,150) }}</span>
                    </div>
                </div>
            </article>
        @empty
            <p>Aucune interview disponible pour le moment.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div style="margin-top:30px;">
        {{ $interviews->links() }}
    </div>

</div>

@endsection
