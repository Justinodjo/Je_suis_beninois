{{-- ═══════════════════════════════════════════════════════
     PARTIAL — EMPLACEMENT PUBLICITAIRE (slider)
     Usage : @include('layouts.partials.ad-slot', ['emplacement' => 'banniere_principale'])
     Emplacements : banniere_principale | laterale | banniere_contenu
     N'affiche rien s'il n'y a aucune publicité active.
═══════════════════════════════════════════════════════ --}}
@php
    $meta = \App\Models\Publicite::EMPLACEMENTS[$emplacement] ?? null;
    $ads  = $meta ? \App\Models\Publicite::actives()->emplacement($emplacement)->get() : collect();
@endphp

@if($ads->isNotEmpty())
<div class="ad-slot ad-slot--{{ $emplacement }}" style="--ad-ratio: {{ $meta['ratio'] }};" role="complementary" aria-label="Publicité">
    <span class="ad-tag">Publicité</span>

    <div class="ad-stage">
        @foreach($ads as $i => $ad)
            @php $alt = $ad->texte_alt ?: $ad->nom; @endphp

            @if($ad->lien)
                <a class="ad-item {{ $i === 0 ? 'is-active' : '' }}"
                   href="{{ route('publicite.clic', $ad) }}"
                   target="_blank" rel="sponsored noopener"
                   data-turbo="false">
                    <img src="{{ $ad->image_url }}" alt="{{ $alt }}" loading="lazy">
                </a>
            @else
                <div class="ad-item {{ $i === 0 ? 'is-active' : '' }}">
                    <img src="{{ $ad->image_url }}" alt="{{ $alt }}" loading="lazy">
                </div>
            @endif
        @endforeach
    </div>

    @if($ads->count() > 1)
    <div class="ad-dots" aria-hidden="true">
        @foreach($ads as $i => $ad)
            <span class="ad-dot {{ $i === 0 ? 'is-active' : '' }}"></span>
        @endforeach
    </div>
    @endif
</div>
@endif

@once
@push('styles')
<style>
.ad-slot { position: relative; margin: 24px auto; width: 100%; }
.ad-slot--banniere_principale,
.ad-slot--banniere_contenu { max-width: 970px; }
.ad-slot--laterale { max-width: 300px; margin: 16px auto; }

.ad-stage {
    position: relative;
    aspect-ratio: var(--ad-ratio);
    border-radius: 10px;
    overflow: hidden;
    background: var(--gris-c);
    box-shadow: 0 1px 3px rgba(0,0,0,.10);
}
.ad-item {
    position: absolute; inset: 0;
    display: block;
    opacity: 0; pointer-events: none;
    transition: opacity .6s ease;
}
.ad-item.is-active { opacity: 1; pointer-events: auto; z-index: 1; }
.ad-item img { width: 100%; height: 100%; object-fit: cover; }

.ad-tag {
    position: absolute; top: 6px; left: 6px; z-index: 3;
    font-size: .6rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase;
    background: rgba(0,0,0,.55); color: #fff;
    padding: 2px 7px; border-radius: 4px;
    pointer-events: none;
}
.ad-dots {
    display: flex; justify-content: center; gap: 6px;
    margin-top: 8px;
}
.ad-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: rgba(0,0,0,.22);
    transition: background .3s, transform .3s;
}
.ad-dot.is-active { background: var(--vert); transform: scale(1.25); }
</style>
@endpush

@push('scripts')
<script>
(function () {
    // Démarre la rotation de chaque emplacement ayant plusieurs publicités
    function initAdSlots() {
        document.querySelectorAll('.ad-slot').forEach(function (slot) {
            if (slot.dataset.ready) return;
            slot.dataset.ready = '1';

            const items = slot.querySelectorAll('.ad-item');
            const dots  = slot.querySelectorAll('.ad-dot');
            if (items.length < 2) return;

            let index = 0;
            let timer = null;

            function show(n) {
                items.forEach((el, k) => el.classList.toggle('is-active', k === n));
                dots.forEach((el, k)  => el.classList.toggle('is-active', k === n));
            }
            function stop()  { clearInterval(timer); timer = null; }
            function start() {
                stop();
                timer = setInterval(function () {
                    if (!slot.isConnected) { stop(); return; }   // page quittée (Turbo)
                    index = (index + 1) % items.length;
                    show(index);
                }, 6000);
            }

            slot.addEventListener('mouseenter', stop);
            slot.addEventListener('mouseleave', start);
            start();
        });
    }

    // Écouteur global : enregistré une seule fois (le script est rejoué à chaque visite Turbo)
    if (!window.__adSlotsBound) {
        window.__adSlotsBound = true;
        document.addEventListener('turbo:load', initAdSlots);
    }
    initAdSlots();
})();
</script>
@endpush
@endonce
