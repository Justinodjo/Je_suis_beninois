@extends('layouts.app')

@section('title', 'Financer — Je Suis Béninois')

@section('content')
<section class="contact-hero pattern-bg">
    <div class="container" style="max-width:760px;margin:0 auto;padding:80px 20px;text-align:center;">
        <h1>Soutenir Je Suis Béninois</h1>
        <p>Cette page est en cours de construction. Pour contribuer ou devenir partenaire,
           contactez-nous.</p>
        <p><a href="{{ route('contact') }}">Nous écrire</a></p>
    </div>
</section>
@endsection