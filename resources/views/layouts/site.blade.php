<!doctype html>
<html lang="en" data-theme="{{ \App\Models\Content::getValue('settings','theme','dark') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="{{ \App\Models\Content::getValue('settings','meta_description','Muhammad Dawood professional developer portfolio') }}">
    <meta property="og:title" content="@yield('title', \App\Models\Content::getValue('settings','site_title','Muhammad Dawood'))">
    <meta property="og:description" content="{{ \App\Models\Content::getValue('settings','meta_description','Full-Stack Web Developer and Software Engineer') }}">
    <meta property="og:type" content="website">
    @if($og=\App\Models\Content::getValue('settings','og_image'))
        <meta property="og:image" content="{{ asset('storage/'.$og) }}">
    @endif
    <title>@yield('title', \App\Models\Content::getValue('settings','site_title','Muhammad Dawood'))</title>
    @if($favicon=\App\Models\Content::getValue('settings','favicon'))
        <link rel="icon" href="{{ asset('storage/'.$favicon) }}">
    @endif
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <style>
        :root{
            --accent:{{ preg_match('/^#[0-9a-fA-F]{6}$/',\App\Models\Content::getValue('settings','accent')) ? \App\Models\Content::getValue('settings','accent') : '#C6A77B' }};
            --secondary:{{ preg_match('/^#[0-9a-fA-F]{6}$/',\App\Models\Content::getValue('settings','secondary_accent')) ? \App\Models\Content::getValue('settings','secondary_accent') : '#222222' }};
        }
    </style>
</head>
<body>
@if(\App\Models\Content::getValue('settings','preloader','1')==='1')
<div class="site-preloader" aria-hidden="true"><div class="preloader-mark"></div></div>
@endif
<div class="scroll-progress" aria-hidden="true"><span></span></div>
<nav class="navbar navbar-expand-lg fixed-top" aria-label="Primary navigation">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}" aria-label="Go to homepage">
            @if($logo=\App\Models\Content::getValue('settings','logo'))
                <img src="{{ asset('storage/'.$logo) }}" alt="{{ \App\Models\Content::getValue('profile','name','Muhammad Dawood') }}" class="brand-logo">
            @else
                {{ \App\Models\Content::getValue('profile','name','Muhammad Dawood') }}
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primaryNav" aria-controls="primaryNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="primaryNav" class="collapse navbar-collapse">
            <div class="navbar-nav ms-auto align-items-lg-center">
                <a class="nav-link" href="{{ route('home') }}#about">About</a>
                <a class="nav-link" href="{{ route('home') }}#skills">Skills</a>
                <a class="nav-link" href="{{ route('home') }}#projects">Projects</a>
                <a class="nav-link" href="{{ route('home') }}#services">Services</a>
                <a class="nav-link" href="{{ route('home') }}#contact">Contact</a>
                @if(\App\Models\Content::getValue('settings','dark_mode','1')==='1')
                    <button class="theme-toggle" type="button" aria-label="Toggle light and dark theme" title="Toggle theme"><span class="theme-icon">◐</span></button>
                @endif
                <a class="admin-link" href="{{ auth()->check() && auth()->user()->is_admin ? route('admin.dashboard') : route('login') }}">
                    {{ auth()->check() && auth()->user()->is_admin ? 'Dashboard' : 'Admin' }}
                </a>
            </div>
        </div>
    </div>
</nav>
<main>@yield('body')</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                @if($logo=\App\Models\Content::getValue('settings','logo'))
                    <img src="{{ asset('storage/'.$logo) }}" alt="{{ \App\Models\Content::getValue('profile','name','Muhammad Dawood') }}" class="footer-logo">
                @else
                    <h3 class="footer-name">{{ \App\Models\Content::getValue('profile','name','Muhammad Dawood') }}</h3>
                @endif
                <p>{{ \App\Models\Content::getValue('profile','title','Full-Stack Web Developer') }}</p>
            </div>
            <nav aria-label="Footer navigation">
                <a href="{{ route('home') }}#about">About</a>
                <a href="{{ route('home') }}#projects">Projects</a>
                <a href="{{ route('home') }}#contact">Contact</a>
                @if(auth()->check() && auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}">Dashboard</a>@endif
            </nav>
            <div class="footer-social">
                @foreach(['github'=>'GitHub','linkedin'=>'LinkedIn','facebook'=>'Facebook','instagram'=>'Instagram','x'=>'X','youtube'=>'YouTube'] as $key=>$label)
                    @php($url=$key==='github'||$key==='linkedin' ? \App\Models\Content::getValue('profile',$key) : \App\Models\Content::getValue('settings','social_'.$key))
                    @if($url)<a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}">{{ strtoupper(substr($label,0,1)) }}</a>@endif
                @endforeach
            </div>
        </div>
        <div class="footer-bottom">
            <span>{{ \App\Models\Content::getValue('settings','footer','Built with Laravel') }}</span>
            <span>© {{ date('Y') }} {{ \App\Models\Content::getValue('profile','name','Muhammad Dawood') }}</span>
        </div>
    </div>
</footer>
@if(\App\Models\Content::getValue('settings','whatsapp_enabled','1')==='1' && ($whatsapp=\App\Models\Content::getValue('profile','whatsapp')))
    <a class="whatsapp-float" href="https://wa.me/{{ preg_replace('/\D+/', '', $whatsapp) }}" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">WA</a>
@endif
<button class="back-to-top" type="button" aria-label="Back to top">↑</button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
