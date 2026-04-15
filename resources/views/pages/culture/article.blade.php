@extends('layouts.app')

@section('title', $article->titre)

@section('content')

<div class="container" style="max-width:900px;margin-top:40px">

    {{-- titre --}}
    <h1 style="margin-bottom:10px;">
        {{ $article->titre }}
    </h1>

    {{-- meta --}}
    <div style="color:#777;font-size:14px;margin-bottom:20px">
        ✍️ {{ $article->user->name ?? 'Rédaction' }}
        • 📅 {{ $article->created_at->format('d M Y') }}
        • 👁️ {{ $article->nb_vues }}
    </div>

    {{-- image --}}
    @if($article->medias->first())
        <img 
            src="{{ $article->medias->first()->url }}"
            style="width:100%;border-radius:10px;margin-bottom:25px">
    @endif

    {{-- contenu --}}
    <div style="line-height:1.9;font-size:16px;margin-bottom:40px">
        {!! $article->contenu !!}
    </div>

    {{-- tags --}}
    @if($article->tags->count())
    <div style="margin-bottom:40px">
        <strong>Tags :</strong>

        @foreach($article->tags as $tag)
            <span style="
                background:#f1f1f1;
                padding:6px 10px;
                border-radius:6px;
                font-size:13px;
                margin-right:5px;">
                #{{ $tag->nom }}
            </span>
        @endforeach
    </div>
    @endif

    {{-- articles similaires --}}
    @if($relatedArticles->count())
    <h3 style="margin-bottom:20px">Articles similaires</h3>

    <div style="
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:20px">

        @foreach($relatedArticles as $rel)

        <div style="
            border:1px solid #eee;
            border-radius:10px;
            overflow:hidden">

            <a href="{{ route('culture.article',$rel->slug) }}">

                <img 
                    src="{{ $rel->medias->first()->url ?? 'https://via.placeholder.com/400x250' }}"
                    style="width:100%;height:160px;object-fit:cover">

                <div style="padding:10px">

                    <h4 style="font-size:15px">
                        {{ Str::limit($rel->titre,60) }}
                    </h4>

                </div>

            </a>

        </div>

        @endforeach

    </div>
    @endif

</div>

@endsection