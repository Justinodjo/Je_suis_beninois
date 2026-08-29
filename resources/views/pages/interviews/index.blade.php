@extends('layouts.app')

@section('title', 'Interviews Béninoises — Je Suis Béninois')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.css" rel="stylesheet">
<style>
/* Tu peux réutiliser les mêmes classes CSS que pour patrimoine si tu veux le même style */

/* ── Hero devenu slider ── */
.interview-hero {
    position: relative;
    height: 320px;
    overflow: hidden;
}
.interview-slide {
    position: absolute; inset: 0;
    display: flex; align-items: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 1.1s ease;
    z-index: 1;
}
.interview-slide.active { opacity: 1; visibility: visible; z-index: 2; }

.interview-hero-bg {
    position: absolute; inset: 0;
    background-size: cover;
    background-position: center;
    transform: scale(1.04);
    transition: transform 7s cubic-bezier(.16,1,.3,1);
}
.interview-slide.active .interview-hero-bg { transform: scale(1); }

.interview-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(0,0,0,.3), rgba(0,0,0,.7));
}
.interview-hero-content {
    position: relative; z-index: 2;
    color: #fff;
    padding: 0 20px;
}
.interview-hero-content h1 { font-family: var(--font-titre); }
.interview-hero-content .interview-slide-cat {
    display: inline-block;
    font-size: .68rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--jaune); margin-bottom: 8px;
}

.interview-dots {
    position: absolute; bottom: 16px; right: 24px; z-index: 3;
    display: flex; gap: 8px;
}
.interview-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,.35); border: none; cursor: pointer; padding: 0;
    transition: background .25s, transform .25s;
}
.interview-dot.active { background: var(--jaune); transform: scale(1.25); }

@media (prefers-reduced-motion: reduce) {
    .interview-slide, .interview-hero-bg { transition: none !important; }
}

/* ── Cartes interviews ── */
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

.interview-card-footer i {
    margin-right: 4px;
    color: var(--vert);
}

</style>
@endpush

@section('content')

@php
    $heroSlides = collect($interviews->items())->take(4)->values();
@endphp

{{-- HERO SLIDER --}}
<section class="interview-hero" id="interviewHero">
    @forelse($heroSlides as $i => $interview)
    <div class="interview-slide {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}">
        <div class="interview-hero-bg" style="background-image:url('{{ $interview->medias->first()?->url ?? 'https://images.unsplash.com/photo-1599058917217-9c8f5a5408c2?w=1400&q=80' }}');"></div>
        <div class="interview-hero-overlay"></div>
        <div class="container interview-hero-content">
            <div data-aos="fade-up" data-aos-duration="600">
                <span class="interview-slide-cat">Interview</span>
                <h1>{{ Str::limit($interview->titre, 60) }}</h1>
                <p>{{ Str::limit($interview->extrait ?? strip_tags($interview->contenu), 120) }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="interview-slide active">
        <div class="interview-hero-bg" style="background-image:url('https://images.unsplash.com/photo-1599058917217-9c8f5a5408c2?w=1400&q=80');"></div>
        <div class="interview-hero-overlay"></div>
        <div class="container interview-hero-content">
            <h1>Interviews</h1>
            <p>Rencontrez les acteurs et personnalités qui font vivre la culture béninoise.</p>
        </div>
    </div>
    @endforelse

    @if($heroSlides->count() > 1)
    <div class="interview-dots">
        @foreach($heroSlides as $i => $interview)
        <button type="button" class="interview-dot {{ $i === 0 ? 'active' : '' }}" data-slide-btn="{{ $i }}" aria-label="Interview {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif
</section>

<div class="container" style="margin-top:40px;">

    <div class="interview-grid">
        @forelse($interviews as $index => $interview)
            <article class="interview-card" data-aos="fade-up" data-aos-delay="{{ min($index, 8) * 80 }}">
                <a href="{{ route('interviews.show', $interview->slug) }}">
                    <img src="{{ $interview->medias->first()?->url ?? 'https://placehold.co/400x200/1B5E20/FFD700?text=Interview' }}"
                         alt="{{ $interview->titre }}">
                </a>
                <div class="interview-card-body">
                    <h3 class="interview-card-title">
                        <a href="{{ route('interviews.show', $interview->slug) }}">{{ $interview->titre }}</a>
                    </h3>
                    <p class="interview-card-excerpt">{{ Str::limit($interview->extrait ?? $interview->contenu, 120) }}</p>
                    <div class="interview-card-footer">
                        <span><i class="fa-regular fa-eye"></i>{{ number_format($interview->nb_vues ?? rand(100,800)) }}</span>
                        <span><i class="fa-solid fa-heart"></i>{{ $interview->nb_likes ?? rand(10,150) }}</span>
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    AOS.init({
        duration: 700,
        easing: 'ease-out-cubic',
        once: true,
        offset: 60
    });

    // ══════════ HERO SLIDER (rotation + rejeu des transitions AOS) ══════════
    const hero = document.getElementById('interviewHero');
    if (!hero) return;

    const slides = Array.from(hero.querySelectorAll('.interview-slide'));
    const dots   = Array.from(hero.querySelectorAll('.interview-dot'));
    if (slides.length <= 1) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const DELAY = 6000;
    let current = 0;
    let timer = null;

    // Rejoue l'animation AOS d'un slide (retire puis remet la classe aos-animate)
    function replayAOS(slideEl) {
        const el = slideEl.querySelector('[data-aos]');
        if (!el) return;
        el.classList.remove('aos-animate');
        void el.offsetWidth; // force le reflow pour relancer la transition
        el.classList.add('aos-animate');
    }

    function goToSlide(index) {
        slides[current]?.classList.remove('active');
        dots[current]?.classList.remove('active');

        current = (index + slides.length) % slides.length;

        slides[current]?.classList.add('active');
        dots[current]?.classList.add('active');
        replayAOS(slides[current]);
    }

    function next() { goToSlide(current + 1); }

    function startAutoplay() {
        if (prefersReducedMotion) return;
        stopAutoplay();
        timer = setInterval(next, DELAY);
    }
    function stopAutoplay() {
        if (timer) clearInterval(timer);
        timer = null;
    }

    dots.forEach((dot, i) => dot.addEventListener('click', () => { goToSlide(i); startAutoplay(); }));
    hero.addEventListener('mouseenter', stopAutoplay);
    hero.addEventListener('mouseleave', startAutoplay);

    // Premier slide : s'assure que l'animation joue dès le chargement
    replayAOS(slides[current]);
    startAutoplay();
});
</script>
@endpush