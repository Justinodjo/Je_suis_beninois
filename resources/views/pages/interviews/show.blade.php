{{-- ═══════════════════════════════════════════════════════
     INTERVIEW DÉTAILLÉE — Fidèle Image 2
     Hero photo + nom, contenu Q&A, sidebar "Dernières interviews",
     section "Vous pourriez aussi aimer"
     Route: GET /interviews/{slug} → InterviewController@show
     Variables: $interview, $recentInterviews, $suggestions
═══════════════════════════════════════════════════════ --}}
@extends('layouts.app')

@section('title', '{{ $interview->titre }} — Je Suis Béninois')

@push('styles')
<style>
/* ══════════ HERO INTERVIEW (Image 2 haut) ══════════ */
.interview-hero {
    position: relative;
    min-height: 380px;
    overflow: hidden;
    display: flex;
    align-items: center;
    background: #0a1a0a;
}
.interview-hero-bg {
    position: absolute; inset: 0;
}
.interview-hero-bg img {
    width: 100%; height: 100%;
    object-fit: cover;
    opacity: .5;
}
.interview-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(
        to right,
        rgba(10,26,10,.9) 0%,
        rgba(10,26,10,.6) 50%,
        transparent 100%
    );
}
.interview-hero-content {
    position: relative; z-index: 2;
    padding: 60px 0;
    max-width: 620px;
}
.interview-hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .75rem;
    color: rgba(255,255,255,.55);
    margin-bottom: 16px;
}
.interview-hero-breadcrumb a { color: var(--jaune); }
.interview-hero-breadcrumb span { color: rgba(255,255,255,.3); }
.interview-hero-tag {
    display: inline-block;
    background: rgba(255,215,0,.15);
    border: 1px solid rgba(255,215,0,.4);
    color: var(--jaune);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 14px;
}
.interview-hero-subtitle {
    font-size: .82rem;
    color: rgba(255,255,255,.7);
    margin-bottom: 8px;
}
.interview-hero-name {
    font-family: var(--font-h);
    font-size: 2.8rem;
    font-weight: 700;
    line-height: 1.1;
    color: #fff;
    margin-bottom: 8px;
}
.interview-hero-name .accent { color: var(--jaune); }
.interview-hero-role {
    font-size: 1.05rem;
    color: rgba(255,255,255,.75);
    font-style: italic;
    margin-bottom: 20px;
}
.interview-hero-desc {
    font-size: .86rem;
    color: rgba(255,255,255,.65);
    line-height: 1.7;
    max-width: 480px;
}

/* ══════════ LAYOUT ARTICLE + SIDEBAR ══════════ */
.interview-body-section { padding: 56px 0; }
.interview-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 48px;
    align-items: start;
}

/* ══════════ CONTENU INTERVIEW ══════════ */
.interview-content {}
.interview-intro {
    font-family: 'Lora', Georgia, serif;
    font-size: .95rem;
    line-height: 1.85;
    color: var(--text-l);
    border-left: 4px solid var(--vert);
    padding-left: 20px;
    margin-bottom: 32px;
    font-style: italic;
}

/* Q&A style (Image 2) */
.interview-qa { display: flex; flex-direction: column; gap: 28px; }
.interview-question {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.interview-question::before {
    content: '?';
    width: 30px; height: 30px;
    background: var(--vert);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: .9rem;
    flex-shrink: 0;
    margin-top: 2px;
}
.interview-question p {
    font-size: .9rem;
    font-weight: 600;
    color: var(--text);
    line-height: 1.5;
}
.interview-answer {
    font-size: .88rem;
    color: var(--text-l);
    line-height: 1.8;
    padding-left: 42px;
}

/* ══════════ SIDEBAR (Image 2 droite) ══════════ */
.interview-sidebar {}

/* Dernières interviews */
.sidebar-interviews {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 24px;
}
.sidebar-interviews-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.sidebar-interviews-header h3 {
    font-size: .92rem;
    font-weight: 700;
}
.sidebar-interview-item {
    display: flex;
    gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid var(--gris-c);
    transition: background .18s;
}
.sidebar-interview-item:hover { background: var(--gris-c); }
.sidebar-interview-item:last-child { border-bottom: none; }
.sidebar-interview-photo {
    width: 52px; height: 52px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}
.sidebar-interview-photo img { width: 100%; height: 100%; object-fit: cover; }
.sidebar-interview-name { font-size: .82rem; font-weight: 700; margin-bottom: 2px; }
.sidebar-interview-role { font-size: .72rem; color: var(--gris-t); margin-bottom: 4px; }
.sidebar-interview-time { font-size: .7rem; color: var(--vert); font-weight: 600; }

/* CTA Contact expert */
.sidebar-cta-expert {
    background: var(--jaune);
    border-radius: var(--radius);
    padding: 20px;
    text-align: center;
}
.sidebar-cta-expert h4 { font-size: .92rem; font-weight: 700; margin-bottom: 4px; }
.sidebar-cta-expert p { font-size: .78rem; color: rgba(0,0,0,.6); margin-bottom: 12px; line-height: 1.5; }
.sidebar-cta-expert .btn { width: 100%; justify-content: center; }

/* ══════════ SUGGESTIONS "Vous pourriez aussi aimer" (Image 2 bas) ══════════ */
.suggestions-section {
    padding: 52px 0;
    border-top: 1px solid var(--border);
}
.suggestions-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 28px;
}
.suggestion-card {
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: transform .2s, box-shadow .2s;
}
.suggestion-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
.suggestion-card-img { position: relative; }
.suggestion-card-img img { width: 100%; height: 180px; }
.suggestion-card-photo {
    position: absolute;
    bottom: -24px; left: 20px;
    width: 52px; height: 52px;
    border-radius: 50%;
    border: 3px solid #fff;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,.2);
}
.suggestion-card-photo img { width: 100%; height: 100%; }
.suggestion-card-body { padding: 32px 20px 20px; }
.suggestion-card-name { font-family: var(--font-h); font-size: 1rem; font-weight: 700; margin-bottom: 2px; }
.suggestion-card-role { font-size: .72rem; color: var(--gris-t); margin-bottom: 8px; }
.suggestion-card-excerpt { font-size: .8rem; color: var(--text-l); line-height: 1.6; margin-bottom: 14px; }
.suggestion-card-cta { font-size: .78rem; font-weight: 600; color: var(--vert); display: flex; align-items: center; gap: 4px; }

/* ══════════ RESPONSIVE ══════════ */
@media (max-width: 900px) {
    .interview-layout { grid-template-columns: 1fr; }
    .interview-sidebar { order: -1; }
    .suggestions-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
    .interview-hero { min-height: 280px; }
    .interview-hero-name { font-size: 2rem; }
    .suggestions-grid { grid-template-columns: 1fr; }
    .interview-layout { gap: 28px; }
}
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

{{-- ════ HERO INTERVIEW (Image 2 haut) ════ --}}
<section class="interview-hero">
    <div class="interview-hero-bg">
        <img src="{{ $interview->medias->first()?->url ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1400&q=80' }}"
             alt="{{ $interview->titre }}">
    </div>
    <div class="interview-hero-overlay"></div>

    <div class="container">
        <div class="interview-hero-content">
            {{-- Breadcrumb --}}
            <div class="interview-hero-breadcrumb">
                <a href="{{ route('home') }}">Accueil</a>
                <span>/</span>
                <a href="{{ route('interviews.index') }}">Interviews</a>
                <span>/</span>
                <span>{{ Str::limit($interview->titre, 30) }}</span>
            </div>

            <div class="interview-hero-tag">🎤 Interview</div>
            <div class="interview-hero-subtitle">Rencontre avec</div>

            <h1 class="interview-hero-name">
                <span class="accent">{{ Str::before($interview->titre, ',') ?: Str::words($interview->titre, 3) }},</span>
            </h1>
            <div class="interview-hero-role">
                {{ $interview->categories->first()?->nom ?? 'entrepreneur inspirant' }}
            </div>
            <p class="interview-hero-desc">
                {{ Str::limit($interview->extrait ?? strip_tags($interview->contenu), 200) }}
            </p>
        </div>
    </div>
</section>

{{-- ════ CORPS DE L'INTERVIEW + SIDEBAR ════ --}}
<section class="interview-body-section">
    <div class="container">
        <div class="interview-layout">

            {{-- ── CONTENU PRINCIPAL ── --}}
            <div class="interview-content">

                {{-- Introduction --}}
                <p class="interview-intro">
                    {{ $interview->extrait ?? Str::limit(strip_tags($interview->contenu), 280) }}
                </p>

                {{-- Q&A — on parse le contenu en blocs --}}
                <div class="interview-qa">
                    @php
                        // Simulation de questions/réponses depuis le contenu
                        $paragraphs = array_filter(explode("\n\n", strip_tags($interview->contenu)));
                        $qaFallback = [
                            ['q'=>'Comment avez-vous commencé votre parcours dans l\'entrepreneuriat?', 'a'=>'Tout a commencé avec une idée simple : combler un vide dans le marché béninois. J\'ai observé les besoins de ma communauté et j\'ai décidé d\'agir avec les moyens dont je disposais.'],
                            ['q'=>'Quelles difficultés avez-vous rencontrées dans votre parcours?', 'a'=>'Les défis ont été nombreux : le financement initial, la confiance des partenaires, et surtout convaincre les gens que les Béninois pouvaient innover.'],
                            ['q'=>'Quelles sont vos ambitions pour les prochaines années?', 'a'=>'Je veux créer 500 emplois d\'ici 2027 et exporter notre savoir-faire béninois à travers toute l\'Afrique de l\'Ouest.'],
                            ['q'=>'Quel message adresseriez-vous aux jeunes entrepreneurs béninois?', 'a'=>'Croyez en vous et en votre pays. Le Bénin a tout ce qu\'il faut pour réussir. Votre culture, votre histoire, votre créativité — ce sont vos plus grands atouts.'],
                            ['q'=>'Quelle est votre vision pour le développement du Bénin?', 'a'=>'Le Bénin doit valoriser son patrimoine culturel tout en embrassant l\'innovation technologique. C\'est dans cette synergie que réside notre force.'],
                        ];
                    @endphp

                    @if(count($paragraphs) >= 4)
                        @foreach(array_slice($paragraphs, 0, 5) as $i => $para)
                        @if($i % 2 === 0)
                        <div class="interview-question"><p>{{ $qaFallback[$i/2]['q'] ?? $para }}</p></div>
                        @else
                        <p class="interview-answer">{{ $para }}</p>
                        @endif
                        @endforeach
                    @else
                        @foreach($qaFallback as $qa)
                        <div class="interview-question"><p>{{ $qa['q'] }}</p></div>
                        <p class="interview-answer">{{ $qa['a'] }}</p>
                        @endforeach
                    @endif
                </div>

                {{-- Tags --}}
                @if($interview->tags->count() > 0)
                <div style="margin-top:32px;display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach($interview->tags as $tag)
                    <span class="badge badge-outline-vert"># {{ $tag->nom }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Partage social --}}
                <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border);display:flex;align-items:center;gap:12px;">
                    <span style="font-size:.82rem;font-weight:600;color:var(--text-l);">Partager :</span>
                    <a href="https://www.facebook.com/sharer?u={{ urlencode(request()->url()) }}" target="_blank"
                       style="width:36px;height:36px;border-radius:50%;background:#1877F2;color:#fff;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;">f</a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank"
                       style="width:36px;height:36px;border-radius:50%;background:#000;color:#fff;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;">𝕏</a>
                    <a href="https://wa.me/?text={{ urlencode($interview->titre . ' ' . request()->url()) }}" target="_blank"
                       style="width:36px;height:36px;border-radius:50%;background:#25D366;color:#fff;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;">W</a>
                </div>
            </div>

            {{-- ── SIDEBAR DROITE (Image 2) ── --}}
            <aside class="interview-sidebar">

                {{-- Dernières interviews --}}
                <div class="sidebar-interviews">
                    <div class="sidebar-interviews-header">
                        <h3>Les Dernières Interviews</h3>
                    </div>

                    @forelse($recentInterviews as $recent)
                    <a href="{{ route('interviews.show', $recent->slug) }}" class="sidebar-interview-item">
                        <div class="sidebar-interview-photo">
                            <img src="{{ $recent->medias->first()?->url ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=70' }}"
                                 alt="{{ $recent->titre }}">
                        </div>
                        <div>
                            <div class="sidebar-interview-name"> {{ Str::words($recent->titre, 4) }}</div>
                            <div class="sidebar-interview-role"> {{ $recent->categories->first()?->nom ?? 'Entrepreneur' }}</div>
                            <div class="sidebar-interview-time"> {{ $recent->created_at?->diffForHumans() }}</div>
                        </div>
                    </a>
                    @empty
                    @foreach([['Loic Hofman','Coach Leadership'],['Tao N\'Kotola','Entrepreneur Tech'],['Caro Hofana','Startup Employ.']] as $p)
                    <div class="sidebar-interview-item">
                        <div class="sidebar-interview-photo">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=70" alt="{{ $p[0] }}">
                        </div>
                        <div>
                            <div class="sidebar-interview-name">{{ $p[0] }}</div>
                            <div class="sidebar-interview-role">{{ $p[1] }}</div>
                            <div class="sidebar-interview-time">Il y a 2 jours</div>
                        </div>
                    </div>
                    @endforeach
                    @endforelse
                </div>

                {{-- CTA Contact expert --}}
                <div class="sidebar-cta-expert">
                    <h4>Contacter un expert</h4>
                    <p>Vous souhaitez être interviewé ou collaborer avec nous?</p>
                    <a href="#contact" class="btn btn-vert">Nous écrire →</a>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ════ "VOUS POURRIEZ AUSSI AIMER" (Image 2 bas) ════ --}}
<section class="suggestions-section">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h2 class="section-title">Vous pourriez aussi aimer</h2>
                <div class="section-divider"></div>
            </div>
            <a href="{{ route('interviews.index') }}" class="section-link">Tout voir →</a>
        </div>

        <div class="suggestions-grid">
            @forelse($suggestions as $sug)
            <article class="suggestion-card">
                <div class="suggestion-card-img">
                    <img src="{{ $sug->medias->first()?->url ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&q=80' }}"
                         alt="{{ $sug->titre }}">
                    <div class="suggestion-card-photo">
                        <img src="{{ $sug->medias->first()?->url ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=70' }}"
                             alt="{{ $sug->titre }}">
                    </div>
                </div>
                <div class="suggestion-card-body">
                    <div class="suggestion-card-name">{{ Str::words($sug->titre, 3) }}</div>
                    <div class="suggestion-card-role">{{ $sug->categories->first()?->nom ?? 'Entrepreneur' }} · {{ $sug->created_at?->diffForHumans() }}</div>
                    <p class="suggestion-card-excerpt">{{ Str::limit($sug->extrait ?? strip_tags($sug->contenu), 100) }}</p>
                    <a href="{{ route('interviews.show', $sug->slug) }}" class="suggestion-card-cta">Lire l'interview →</a>
                </div>
            </article>
            @empty
            @foreach([['Virginie Touko','Présidente d\'une ONG','https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=500&q=80'],['Asim Diasso','Directeur d\'une startup','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&q=80'],['Henri Agbata','Fondateur d\'une entreprise','https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=500&q=80']] as $sg)
            <article class="suggestion-card">
                <div class="suggestion-card-img">
                    <img src="{{ $sg[2] }}" alt="{{ $sg[0] }}">
                    <div class="suggestion-card-photo">
                        <img src="{{ $sg[2] }}" alt="{{ $sg[0] }}">
                    </div>
                </div>
                <div class="suggestion-card-body">
                    <div class="suggestion-card-name">{{ $sg[0] }}</div>
                    <div class="suggestion-card-role">{{ $sg[1] }} · Il y a 5 jours</div>
                    <p class="suggestion-card-excerpt">Un parcours inspirant qui montre que la réussite est possible pour tout Béninois déterminé.</p>
                    <a href="#" class="suggestion-card-cta">Lire l'interview →</a>
                </div>
            </article>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

@endsection