@extends('layouts.app')

@section('title', 'Tourisme au Bénin')

@section('content')
<section style="padding: 60px 0; background: var(--gris-c);">
    <div class="container">
        <div class="section-header" style="margin-bottom: 40px;">
            <h1 class="section-title">
                <i class="fa-solid fa-plane-departure" style="color:var(--vert);"></i>
                Tourisme au <span class="accent">Bénin</span>
            </h1>
            <p style="color:var(--text-l);font-size:1.05rem;max-width:700px;margin-top:12px;">
                Découvrez les merveilles touristiques du Bénin : sites UNESCO, parcs nationaux, plages paradisiaques et patrimoine culturel unique.
            </p>
        </div>

        <div class="actu-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
            @forelse($articles as $article)
            <article class="article-card">
                <!-- Même structure que dans accueil.blade.php -->
            </article>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px;">
                <i class="fa-solid fa-suitcase-rolling" style="font-size:4rem;color:var(--gris-t);margin-bottom:16px;display:block;"></i>
                <p>Aucun article sur le tourisme pour le moment.</p>
            </div>
            @endforelse
        </div>

        {{ $articles->links() }}
    </div>
</section>
@endsection