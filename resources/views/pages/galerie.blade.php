{{-- ═══════════════════════════════════════════════════════
     GALERIE
     Route: GET /galerie → MediaController@index
     Variables: $medias (paginé)
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', 'Galerie — Je Suis Béninois')

@php
    // Split de la page courante par type — évite une requête supplémentaire
    $videos = $medias->getCollection()->where('type', 'video')->values();
    $images = $medias->getCollection()->where('type', 'image')->values();

    $videosJson = $videos->map(fn($v) => [
        'url'   => $v->url,
        'thumb' => $v->url_thumbnail ?? $v->url,
        'titre' => $v->titre ?? $v->nom ?? 'Vidéo',
    ]);
@endphp

@push('styles')
<style>
.galerie-hero { background: var(--vert); padding: 44px 0; }
.galerie-hero h1 { font-family: var(--font-titre); font-size: clamp(1.7rem,3vw,2.3rem); color: #fff; }
.galerie-hero p { color: rgba(255,255,255,.75); font-size: .9rem; margin-top: 8px; }

.galerie-nav {
    display: flex;
    gap: 8px;
    margin: 28px 0 8px;
}
.galerie-nav a {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: .82rem;
    font-weight: 600;
    border: 1.5px solid var(--border);
    color: var(--text-l);
    transition: all .2s;
}
.galerie-nav a:hover { border-color: var(--vert); color: var(--vert); }

.galerie-section-title {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    margin: 40px 0 18px;
}
.galerie-section-title h2 {
    font-family: var(--font-titre);
    font-size: 1.4rem;
    color: var(--text);
}
.galerie-section-title span {
    font-size: .82rem;
    color: var(--gris-t);
}

/* ═══════════════════════════════════════════
   SLIDER VIDÉOS — style YouTube
═══════════════════════════════════════════ */
.video-slider { margin-bottom: 12px; }

.video-slider-main {
    position: relative;
    background: #0b0d0b;
    border-radius: 14px;
    overflow: hidden;
    aspect-ratio: 16/9;
    max-height: 560px;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
}
.video-slider-main img {
    width: 100%; height: 100%; object-fit: cover;
    opacity: .75;
    transition: opacity .25s;
}
.video-slider-main:hover img { opacity: .6; }

.video-slider-playbtn {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    width: 76px; height: 76px;
    border-radius: 50%;
    background: rgba(255,255,255,.92);
    display: flex; align-items: center; justify-content: center;
    color: var(--vert);
    font-size: 1.7rem;
    cursor: pointer;
    transition: transform .2s, background .2s;
}
.video-slider-playbtn:hover { transform: translate(-50%,-50%) scale(1.08); background: #fff; }

.video-slider-caption {
    position: absolute; left: 0; right: 0; bottom: 0;
    padding: 20px 24px;
    background: linear-gradient(transparent, rgba(0,0,0,.65));
    color: #fff;
}
.video-slider-caption h3 { font-size: 1.05rem; font-weight: 600; }
.video-slider-caption span { font-size: .78rem; opacity: .8; }

.video-slider-arrow {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 44px; height: 44px;
    border-radius: 50%;
    background: rgba(0,0,0,.4);
    color: #fff;
    border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 1rem;
    transition: background .2s;
    z-index: 2;
}
.video-slider-arrow:hover { background: rgba(0,0,0,.65); }
.video-slider-arrow.prev { left: 14px; }
.video-slider-arrow.next { right: 14px; }

.video-slider-thumbs {
    display: flex;
    gap: 10px;
    margin-top: 14px;
    overflow-x: auto;
    scrollbar-width: thin;
    padding-bottom: 6px;
}
.video-slider-thumb {
    flex: 0 0 auto;
    width: 140px; aspect-ratio: 16/9;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    position: relative;
    border: 2px solid transparent;
    opacity: .6;
    transition: all .2s;
}
.video-slider-thumb.active { opacity: 1; border-color: var(--vert); }
.video-slider-thumb:hover { opacity: 1; }
.video-slider-thumb img { width: 100%; height: 100%; object-fit: cover; }
.video-slider-thumb i {
    position: absolute; inset: 0; margin: auto;
    width: fit-content; height: fit-content;
    color: #fff; font-size: 1.1rem;
    text-shadow: 0 1px 4px rgba(0,0,0,.5);
}

/* ═══════════════════════════════════════════
   BANDE PHOTOS — défilement horizontal
═══════════════════════════════════════════ */
.photo-strip-wrap { position: relative; }

.photo-strip {
    display: flex;
    gap: 14px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scrollbar-width: thin;
    padding: 4px 4px 14px;
    cursor: grab;
}
.photo-strip:active { cursor: grabbing; }
.photo-strip-item {
    flex: 0 0 auto;
    width: 240px; aspect-ratio: 1/1;
    border-radius: 12px;
    overflow: hidden;
    scroll-snap-align: start;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(15,61,20,.08);
}
.photo-strip-item img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .3s;
}
.photo-strip-item:hover img { transform: scale(1.06); }

.photo-strip-arrow {
    position: absolute; top: 40%; transform: translateY(-50%);
    width: 40px; height: 40px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,.15);
    border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: var(--vert);
    z-index: 2;
}
.photo-strip-arrow.prev { left: -18px; }
.photo-strip-arrow.next { right: -18px; }

.galerie-empty { text-align: center; padding: 48px 20px; color: var(--text-l); }
.galerie-empty i { font-size: 2.4rem; color: var(--border); margin-bottom: 12px; display: block; }

.galerie-lightbox {
    display: none;
    position: fixed; inset: 0; z-index: 999;
    background: rgba(0,0,0,.92);
    align-items: center; justify-content: center;
}
.galerie-lightbox.open { display: flex; }
.galerie-lightbox img, .galerie-lightbox video { max-width: 90vw; max-height: 85vh; border-radius: 8px; }
.galerie-lightbox-close {
    position: absolute; top: 24px; right: 32px;
    color: #fff; font-size: 2rem; cursor: pointer; background: none; border: none;
}

@media (max-width: 900px) {
    .video-slider-main { max-height: 340px; }
    .photo-strip-arrow { display: none; }
}
@media (max-width: 640px) {
    .video-slider-thumb { width: 108px; }
    .photo-strip-item { width: 180px; }
    .video-slider-playbtn { width: 60px; height: 60px; font-size: 1.3rem; }
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

    <div class="galerie-nav">
        <a href="#videos-section">Vidéos</a>
        <a href="#photos-section">Photos</a>
    </div>

    {{-- ═══ SLIDER VIDÉOS ═══ --}}
    <div id="videos-section">
        <div class="galerie-section-title">
            <h2>Vidéos</h2>
            <span>{{ $videos->count() }} vidéo{{ $videos->count() !== 1 ? 's' : '' }}</span>
        </div>

        @if($videos->isNotEmpty())
        <div class="video-slider" id="videoSlider" data-videos='@json($videosJson)'>
            <div class="video-slider-main" id="videoMain" onclick="playCurrentVideo()">
                <button class="video-slider-arrow prev" onclick="event.stopPropagation(); prevVideo()">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <img id="videoMainImg" src="{{ $videos->first()->url_thumbnail ?? $videos->first()->url }}" alt="">
                <div class="video-slider-playbtn"><i class="fa-solid fa-play"></i></div>
                <button class="video-slider-arrow next" onclick="event.stopPropagation(); nextVideo()">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <div class="video-slider-caption">
                    <h3 id="videoMainTitle">{{ $videos->first()->titre ?? $videos->first()->nom ?? 'Vidéo' }}</h3>
                    <span id="videoMainCount">1 / {{ $videos->count() }}</span>
                </div>
            </div>

            <div class="video-slider-thumbs" id="videoThumbs">
                @foreach($videos as $i => $video)
                <div class="video-slider-thumb {{ $i === 0 ? 'active' : '' }}" onclick="goToVideo({{ $i }})">
                    <img src="{{ $video->url_thumbnail ?? $video->url }}" alt="">
                    <i class="fa-solid fa-circle-play"></i>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="galerie-empty">
            <i class="fa-solid fa-video"></i>
            <p>Aucune vidéo disponible pour le moment.</p>
        </div>
        @endif
    </div>

    {{-- ═══ BANDE PHOTOS ═══ --}}
    <div id="photos-section">
        <div class="galerie-section-title">
            <h2>Photos</h2>
            <span>{{ $images->count() }} photo{{ $images->count() !== 1 ? 's' : '' }}</span>
        </div>

        @if($images->isNotEmpty())
        <div class="photo-strip-wrap">
            <button class="photo-strip-arrow prev" onclick="scrollPhotos(-1)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="photo-strip" id="photoStrip">
                @foreach($images as $image)
                <div class="photo-strip-item" onclick="openLightbox('image', '{{ $image->url }}')">
                    <img src="{{ $image->url }}" alt="{{ $image->titre ?? $image->nom ?? 'Photo' }}" loading="lazy">
                </div>
                @endforeach
            </div>

            <button class="photo-strip-arrow next" onclick="scrollPhotos(1)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
        @else
        <div class="galerie-empty">
            <i class="fa-solid fa-image"></i>
            <p>Aucune photo disponible pour le moment.</p>
        </div>
        @endif
    </div>

    <div style="margin:40px 0 48px;">
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
// ═══ SLIDER VIDÉOS ═══
const videoSliderEl = document.getElementById('videoSlider');
const videos   = videoSliderEl ? JSON.parse(videoSliderEl.dataset.videos) : [];
let currentVideoIndex = 0;

function renderVideoSlide() {
    if (!videos.length) return;
    const v = videos[currentVideoIndex];
    document.getElementById('videoMainImg').src   = v.thumb;
    document.getElementById('videoMainTitle').textContent = v.titre;
    document.getElementById('videoMainCount').textContent = `${currentVideoIndex + 1} / ${videos.length}`;

    document.querySelectorAll('.video-slider-thumb').forEach((el, i) => {
        el.classList.toggle('active', i === currentVideoIndex);
    });

    const activeThumb = document.querySelectorAll('.video-slider-thumb')[currentVideoIndex];
    activeThumb?.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
}

function nextVideo() {
    currentVideoIndex = (currentVideoIndex + 1) % videos.length;
    renderVideoSlide();
}
function prevVideo() {
    currentVideoIndex = (currentVideoIndex - 1 + videos.length) % videos.length;
    renderVideoSlide();
}
function goToVideo(i) {
    currentVideoIndex = i;
    renderVideoSlide();
}
function playCurrentVideo() {
    if (!videos.length) return;
    openLightbox('video', videos[currentVideoIndex].url);
}

// ═══ BANDE PHOTOS ═══
function scrollPhotos(direction) {
    const strip = document.getElementById('photoStrip');
    strip.scrollBy({ left: direction * 320, behavior: 'smooth' });
}

// ═══ LIGHTBOX PARTAGÉE ═══
function openLightbox(type, src) {
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
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLightbox();
    if (document.getElementById('galerieLightbox').classList.contains('open')) return;
    if (e.key === 'ArrowRight') nextVideo();
    if (e.key === 'ArrowLeft')  prevVideo();
});
</script>
@endpush