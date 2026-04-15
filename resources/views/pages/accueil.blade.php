@extends('layouts.app')

@section('title', 'Accueil — Je Suis Béninois')

@push('styles')
<style>
/* ══════════ HERO SLIDER ══════════ */
.hero-slider {
    position: relative;
    height: 580px;
    overflow: hidden;
}

.hero-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.hero-slide.active {
    opacity: 1;
    z-index: 1;
}

.hero-slide-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
}

.hero-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to right,
        rgba(0,0,0,.75) 0%,
        rgba(0,0,0,.4) 50%,
        rgba(0,0,0,.1) 100%
    );
}

.hero-slide-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding-bottom: 80px;
}

.hero-slide-category {
    display: inline-block;
    background: var(--jaune);
    color: #1a1a1a;
    padding: 6px 16px;
    border-radius: 24px;
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 16px;
}

.hero-slide-title {
    font-family: var(--font-titre);
    font-size: 3.2rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.1;
    margin-bottom: 18px;
    max-width: 800px;
}

.hero-slide-excerpt {
    font-size: 1.05rem;
    color: rgba(255,255,255,.85);
    line-height: 1.6;
    margin-bottom: 28px;
    max-width: 600px;
}

/* Navigation slider */
.slider-nav {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    gap: 10px;
}

.slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,.4);
    border: 2px solid transparent;
    cursor: pointer;
    transition: all .3s;
}

.slider-dot.active {
    background: var(--jaune);
    width: 40px;
    border-radius: 6px;
}

.slider-arrows {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    z-index: 10;
    display: flex;
    justify-content: space-between;
    padding: 0 30px;
    pointer-events: none;
}

.slider-arrow {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all .3s;
    pointer-events: all;
}

.slider-arrow:hover {
    background: var(--jaune);
    color: #1a1a1a;
    transform: scale(1.1);
}

/* ══════════ CATEGORIES TABS ══════════ */
.categories-section {
    background: var(--gris-c);
    padding: 48px 0;
}

.category-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 36px;
    flex-wrap: wrap;
    border-bottom: 2px solid var(--border);
    padding-bottom: 12px;
}

.category-tab {
    padding: 10px 24px;
    border-radius: 8px;
    font-size: .88rem;
    font-weight: 600;
    color: var(--text-l);
    background: #fff;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.category-tab:hover {
    border-color: var(--vert);
    color: var(--vert);
}

.category-tab.active {
    background: var(--vert);
    color: #fff;
    border-color: var(--vert);
}

.category-content {
    display: none;
}

.category-content.active {
    display: block;
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

/* ══════════ RESPONSIVE ══════════ */
@media (max-width: 1024px) {
    .articles-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero-slider {
        height: 400px;
    }
    
    .hero-slide-title {
        font-size: 2rem;
    }
    
    .slider-arrows {
        display: none;
    }
    
    .articles-grid {
        grid-template-columns: 1fr;
    }
    
    .category-tabs {
        overflow-x: auto;
        flex-wrap: nowrap;
        -webkit-overflow-scrolling: touch;
    }
}
</style>
@endpush

@section('content')

{{-- ════ HERO SLIDER ════ --}}
<section class="hero-slider" id="heroSlider">
    @foreach($featuredArticles as $index => $article)
    <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
        <div class="hero-slide-bg" style="background-image: url('{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1400&q=80' }}');"></div>
        <div class="hero-slide-overlay"></div>
        
        <div class="hero-slide-content">
            <div class="container">
                <span class="hero-slide-category">
                    <i class="fa-solid fa-star"></i>
                    {{ $article->categories->first()?->nom ?? 'À la Une' }}
                </span>
                <h2 class="hero-slide-title">{{ $article->titre }}</h2>
                <p class="hero-slide-excerpt">
                    {{ Str::limit($article->extrait ?? strip_tags($article->contenu), 180) }}
                </p>
                <a href="{{ route('culture.article', $article->slug) }}" class="btn btn-jaune btn-lg">
                    <i class="fa-solid fa-arrow-right"></i> Lire l'article
                </a>
            </div>
        </div>
    </div>
    @endforeach
    
    {{-- Navigation --}}
    <div class="slider-arrows">
        <button class="slider-arrow slider-prev" onclick="prevSlide()">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button class="slider-arrow slider-next" onclick="nextSlide()">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>
    
    <div class="slider-nav">
        @foreach($featuredArticles as $index => $article)
        <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></div>
        @endforeach
    </div>
</section>

{{-- ════ SECTIONS PAR CATÉGORIE ════ --}}
<section class="categories-section">
    <div class="container">
        <div class="section-header" style="margin-bottom: 32px;">
            <h2 class="section-title">
                <i class="fa-solid fa-newspaper" style="color:var(--vert);"></i>
                Dernières <span class="accent">Actualités</span> par catégorie
            </h2>
        </div>
        
        {{-- Tabs --}}
        <div class="category-tabs">
            @foreach($categories as $index => $category)
            <button class="category-tab {{ $index === 0 ? 'active' : '' }}" 
                    onclick="switchCategory('cat-{{ $category->id }}')"
                    data-category="cat-{{ $category->id }}">
                @if($category->icone)
                    {{ $category->icone }}
                @else
                    <i class="fa-solid fa-folder"></i>
                @endif
                {{ $category->nom }}
                <span style="background:rgba(0,0,0,.1);padding:2px 8px;border-radius:12px;font-size:.7rem;">
                    {{ $category->articles_count ?? 0 }}
                </span>
            </button>
            @endforeach
        </div>
        
        {{-- Contenus par catégorie --}}
        @foreach($categories as $index => $category)
        <div class="category-content {{ $index === 0 ? 'active' : '' }}" id="cat-{{ $category->id }}">
            <div class="articles-grid">
                @forelse($articlesByCategory[$category->id] ?? [] as $article)
                <article class="article-card">
                    <div class="article-card-img">
                        <a href="{{ route('culture.article', $article->slug) }}">
                            <img src="{{ $article->medias->first()?->url ?? 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=600&q=80' }}"
                                 alt="{{ $article->titre }}"
                                 style="height: 200px;">
                        </a>
                        <span class="badge badge-vert" style="position:absolute;top:12px;left:12px;">
                            {{ $category->nom }}
                        </span>
                    </div>
                    <div class="article-card-body">
                        <h3 class="article-card-title">
                            <a href="{{ route('culture.article', $article->slug) }}">
                                {{ Str::limit($article->titre, 70) }}
                            </a>
                        </h3>
                        <p class="article-card-excerpt">
                            {{ Str::limit($article->extrait ?? strip_tags($article->contenu), 100) }}
                        </p>
                        <div class="article-card-meta">
                            <span><i class="fa-regular fa-calendar"></i> {{ $article->created_at?->diffForHumans() }}</span>
                            <span><i class="fa-regular fa-eye"></i> {{ number_format($article->nb_vues) }}</span>
                        </div>
                    </div>
                </article>
                @empty
                <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-l);">
                    <i class="fa-solid fa-inbox" style="font-size:3rem;margin-bottom:12px;display:block;color:var(--gris-t);"></i>
                    <p>Aucun article dans cette catégorie pour le moment.</p>
                </div>
                @endforelse
            </div>
            
            @if(count($articlesByCategory[$category->id] ?? []) >= 10)
            <div style="text-align:center;margin-top:32px;">
                <a href="{{ route('actualites') }}?category={{ $category->id }}" class="btn btn-secondary">
                    <i class="fa-solid fa-plus"></i> Voir tous les articles de {{ $category->nom }}
                </a>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</section>

@endsection

@push('scripts')
<script>
// ══════════ SLIDER AUTO ══════════
let currentSlide = 0;
const slides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.slider-dot');
let autoSlideInterval;

function goToSlide(index) {
    slides[currentSlide].classList.remove('active');
    dots[currentSlide].classList.remove('active');
    
    currentSlide = index;
    
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
    
    resetAutoSlide();
}

function nextSlide() {
    let next = (currentSlide + 1) % slides.length;
    goToSlide(next);
}

function prevSlide() {
    let prev = (currentSlide - 1 + slides.length) % slides.length;
    goToSlide(prev);
}

function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, 5000); // Change toutes les 5 secondes
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}

// Démarrage auto
startAutoSlide();

// Pause au hover
document.getElementById('heroSlider')?.addEventListener('mouseenter', () => {
    clearInterval(autoSlideInterval);
});

document.getElementById('heroSlider')?.addEventListener('mouseleave', () => {
    startAutoSlide();
});

// ══════════ CATEGORY TABS ══════════
function switchCategory(categoryId) {
    // Retirer active de tous
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.category-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Activer la sélection
    const selectedTab = document.querySelector(`[data-category="${categoryId}"]`);
    const selectedContent = document.getElementById(categoryId);
    
    if (selectedTab) selectedTab.classList.add('active');
    if (selectedContent) selectedContent.classList.add('active');
}
</script>
@endpush