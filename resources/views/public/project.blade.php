@extends('layouts.site')
@section('title',$item->title)
@section('body')
<main class="section-space project-detail-page">
    <div class="container">
        <a class="project-back" href="{{ route('home') }}#projects">← All projects</a>
        <header class="project-detail-header reveal">
            @if($item->subtitle)<span class="project-category">{{ $item->subtitle }}</span>@endif
            <h1>{{ $item->title }}</h1>
        </header>
        @if($item->image)
            <img class="project-detail-main reveal" src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}" loading="lazy">
        @endif
        @if($item->images->count())
            <div class="row g-3 my-4">
                @foreach($item->images as $image)
                    <div class="col-md-4">
                        <a href="{{ asset('storage/'.$image->path) }}" target="_blank" rel="noopener">
                            <img class="project-detail-gallery" loading="lazy" src="{{ asset('storage/'.$image->path) }}" alt="{{ $item->title }} screenshot">
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
        @if($item->description)<p class="project-detail-copy">{{ $item->description }}</p>@endif
        @if($item->meta)<p class="project-detail-meta"><strong>Technologies:</strong> {{ $item->meta }}</p>@endif
        <div class="hero-actions">
            @if($item->url)<a class="btn btn-gold" target="_blank" rel="noopener noreferrer" href="{{ $item->url }}">Live Demo</a>@endif
            @if($item->secondary_url)<a class="btn btn-outline-gold" target="_blank" rel="noopener noreferrer" href="{{ $item->secondary_url }}">GitHub Repository</a>@endif
        </div>
    </div>
</main>
@endsection
