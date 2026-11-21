@extends('layouts.app')

@section('title', 'Mon espace contributeur')

@section('content')
<div class="container" style="padding:40px 0;">
    <h1>Mon espace contributeur</h1>
    <p>Bienvenue {{ auth('api')->user()->name }}.</p>
</div>
@endsection
