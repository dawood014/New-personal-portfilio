@extends('layouts.site')
@php
    $profile=$content['profile']??collect();
    $hero=$content['hero']??collect();
    $settings=$content['settings']??collect();
    $typingTitles=$hero['typing_titles']??'Full-Stack Web Developer|Software Engineer|MERN Stack Developer|Laravel Developer|Backend Developer';
@endphp
@section('body')
<section class="hero-section" id="home" data-background="{{ $settings['hero_background_type']??'particles' }}">
    @if(($settings['hero_background_type']??'')==='video' && !empty($hero['background_video']))
        <video class="hero-media" autoplay muted loop playsinline poster="{{ !empty($hero['background']) ? asset('storage/'.$hero['background']) : '' }}">
            <source src="{{ asset('storage/'.$hero['background_video']) }}">
        </video>
    @elseif(!empty($hero['background']))
        <div class="hero-media hero-image" style="background-image:url('{{ asset('storage/'.$hero['background']) }}')"></div>
    @else
        <div class="hero-media hero-pattern"></div>
    @endif
    @if(($settings['particles']??'1')==='1')
        <div class="particles" aria-hidden="true">
            @for($i=0;$i<18;$i++)<span style="--i:{{ $i }}"></span>@endfor
        </div>
    @endif
    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 hero-copy reveal">
                @if(($settings['available_for_work']??'1')==='1')
                    <span class="availability"><i></i>{{ $hero['eyebrow']??'AVAILABLE FOR PROJECTS' }}</span>
                @else
                    <span class="eyebrow">{{ $hero['eyebrow']??'PORTFOLIO' }}</span>
                @endif
                <h1>{{ $hero['heading']??'Building digital experiences that work.' }}</h1>
                <p class="hero-role"><span class="typing-text" data-phrases="{{ $typingTitles }}">{{ explode('|',$typingTitles)[0] }}</span></p>
                <p class="hero-intro">{{ $hero['intro']??'' }}</p>
                <div class="hero-actions">
                    <a class="btn btn-gold magnetic" href="#projects">{{ $hero['button_text']??'View Projects' }}</a>
                    <a class="btn btn-outline-gold magnetic" href="#contact">Hire Me</a>
                    @if(!empty($profile['cv']))
                        <a class="text-link" href="{{ asset('storage/'.$profile['cv']) }}" download>Download CV <span>↓</span></a>
                    @endif
                </div>
                <div class="hero-social">
                    @foreach(['github'=>'GitHub','linkedin'=>'LinkedIn'] as $key=>$label)
                        @if(!empty($profile[$key]))<a href="{{ $profile[$key] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}">{{ $label }}</a>@endif
                    @endforeach
                </div>
            </div>
            <div class="col-lg-5 text-center reveal reveal-delay">
                <div class="portrait-wrap">
                    @if(!empty($hero['personal_image']) || !empty($profile['photo']))
                        <img class="hero-portrait" src="{{ asset('storage/'.(!empty($hero['personal_image']) ? $hero['personal_image'] : $profile['photo'])) }}" alt="{{ $profile['name']??'Muhammad Dawood' }} professional portrait" loading="eager">
                    @else
                        <div class="portrait-placeholder"><span>MD</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about" class="section-space">
    <div class="container">
        <div class="section-heading reveal"><span>01 · Introduction</span><h2>About Me</h2></div>
        <div class="row align-items-center g-5 about-grid">
            <div class="col-lg-5 reveal">
                @if(!empty($profile['photo']))
                    <img class="about-image" src="{{ asset('storage/'.$profile['photo']) }}" alt="{{ $profile['name']??'Muhammad Dawood' }}" loading="lazy">
                @else
                    <div class="about-placeholder">Muhammad Dawood</div>
                @endif
            </div>
            <div class="col-lg-7 reveal reveal-delay">
                <p class="about-title">{{ $profile['title']??'Full-Stack Web Developer | Software Engineer' }}</p>
                <p class="about-bio">{{ $profile['bio']??'' }}</p>
                <div class="info-grid">
                    @if(!empty($profile['location']))<div><span>Location</span><strong>{{ $profile['location'] }}</strong></div>@endif
                    @if(!empty($profile['email']))<div><span>Email</span><strong><a href="mailto:{{ $profile['email'] }}">{{ $profile['email'] }}</a></strong></div>@endif
                    @if(!empty($profile['phone']))<div><span>Phone</span><strong>{{ $profile['phone'] }}</strong></div>@endif
                </div>
            </div>
        </div>
    </div>
</section>

<section id="skills" class="section-space section-muted">
    <div class="container">
        <div class="section-heading reveal"><span>02 · Expertise</span><h2>Technical Skills</h2></div>
        <div class="row g-4 mt-2">
            @forelse($items->get('skill',collect()) as $item)
                @php($level=min(100,max(0,(int)$item->subtitle)))
                <div class="col-md-6 col-lg-4 reveal">
                    <article class="skill-card">
                        <div class="skill-head"><h3>{{ $item->title }}</h3><span>{{ $level }}%</span></div>
                        @if($item->subtitle)<div class="skill-track"><span data-level="{{ $level }}"></span></div>@endif
                        @if($item->description)<p>{{ $item->description }}</p>@endif
                    </article>
                </div>
            @empty
                <p class="empty-state">Skills coming soon.</p>
            @endforelse
        </div>
    </div>
</section>

<section id="projects" class="section-space">
    <div class="container">
        <div class="section-heading reveal"><span>03 · Selected Work</span><h2>Featured Projects</h2></div>
        @php($projects=$items->get('project',collect()))
        @if($projects->isNotEmpty())
            @php($categories=$projects->pluck('subtitle')->filter()->map(fn($value)=>trim(explode('·',$value)[0]))->unique()->values())
            @if($categories->count()>1)
                <div class="project-filters reveal" aria-label="Filter projects">
                    <button class="active" type="button" data-filter="all">All</button>
                    @foreach($categories as $category)<button type="button" data-filter="{{ $category }}">{{ $category }}</button>@endforeach
                </div>
            @endif
            <div class="row g-4 mt-2">
                @foreach($projects as $item)
                    <div class="col-md-6 col-lg-4 reveal project-card-wrap" data-category="{{ $item->subtitle ? trim(explode('·',$item->subtitle)[0]) : 'All' }}">
                        <article class="project-card" data-tilt>
                            <a class="project-image" href="{{ route('project.show',$item) }}">
                                @if($item->image)<img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}" loading="lazy">@else<div class="project-placeholder">PROJECT</div>@endif
                            </a>
                            <div class="project-content">
                                @if($item->subtitle)<span class="project-category">{{ $item->subtitle }}</span>@endif
                                <h3><a href="{{ route('project.show',$item) }}">{{ $item->title }}</a></h3>
                                @if($item->description)<p>{{ \Illuminate\Support\Str::limit($item->description,120) }}</p>@endif
                                <a class="project-link" href="{{ route('project.show',$item) }}">View Project <span>→</span></a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <p class="empty-state">Projects coming soon.</p>
        @endif
    </div>
</section>

<section id="services" class="section-space section-muted">
    <div class="container">
        <div class="section-heading reveal"><span>04 · Services</span><h2>How I Can Help</h2></div>
        <div class="row g-4 mt-2">
            @foreach($items->get('service',collect()) as $index=>$item)
                <div class="col-md-6 col-lg-4 reveal">
                    <article class="service-card">
                        <span class="service-number">{{ str_pad($index+1,2,'0',STR_PAD_LEFT) }}</span>
                        <h3>{{ $item->title }}</h3>
                        <p>{{ $item->description }}</p>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>

@foreach(['experience'=>'05 · Journey','education'=>'06 · Education'] as $type=>$label)
    @if(($items->get($type,collect()))->isNotEmpty())
        <section class="section-space timeline-section">
            <div class="container">
                <div class="section-heading reveal"><span>{{ $label }}</span><h2>{{ ucfirst($type) }}</h2></div>
                <div class="timeline">
                    @foreach($items->get($type) as $item)
                        <article class="timeline-item reveal">
                            <span class="timeline-dot"></span>
                            <div class="timeline-card"><span>{{ $item->subtitle }}</span><h3>{{ $item->title }}</h3><p>{{ $item->description }}</p></div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endforeach

@if(($items->get('testimonial',collect()))->isNotEmpty())
<section class="section-space section-muted">
    <div class="container">
        <div class="section-heading reveal"><span>07 · Testimonials</span><h2>Kind Words</h2></div>
        <div class="testimonial-track reveal">
            @foreach($items->get('testimonial') as $item)
                <article class="testimonial-card"><div class="quote-mark">“</div><p>{{ $item->description }}</p><h3>{{ $item->title }}</h3>@if($item->subtitle)<span>{{ $item->subtitle }}</span>@endif</article>
            @endforeach
        </div>
    </div>
</section>
@endif

<section id="contact" class="section-space contact-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-5 reveal">
                <div class="section-heading"><span>08 · Contact</span><h2>Let’s Work Together</h2></div>
                <p class="contact-copy">Have a project or opportunity in mind? Send a message and let’s discuss the next step.</p>
                @if(!empty($profile['email']))<a class="contact-detail" href="mailto:{{ $profile['email'] }}"><span>Email</span><strong>{{ $profile['email'] }}</strong></a>@endif
                @if(!empty($profile['phone']))<div class="contact-detail"><span>Phone</span><strong>{{ $profile['phone'] }}</strong></div>@endif
                @if(!empty($profile['location']))<div class="contact-detail"><span>Location</span><strong>{{ $profile['location'] }}</strong></div>@endif
            </div>
            <div class="col-lg-7 reveal reveal-delay">
                <div class="contact-form-wrap">
                    @if(session('success'))<div class="form-alert success">{{ session('success') }}</div>@endif
                    @if($errors->any())<div class="form-alert error">Please check the highlighted fields and try again.</div>@endif
                    <form action="{{ route('contact.submit') }}" method="post" class="contact-form">
                        @csrf
                        <input type="text" name="website" tabindex="-1" autocomplete="off" class="visually-hidden">
                        <div class="row g-4">
                            <div class="col-md-6 floating-field"><input class="form-control" name="name" id="contact-name" value="{{ old('name') }}" required maxlength="120" placeholder=" "><label for="contact-name">Name</label></div>
                            <div class="col-md-6 floating-field"><input type="email" class="form-control" name="email" id="contact-email" value="{{ old('email') }}" required maxlength="190" placeholder=" "><label for="contact-email">Email</label></div>
                            <div class="col-12 floating-field"><input class="form-control" name="subject" id="contact-subject" value="{{ old('subject') }}" required maxlength="190" placeholder=" "><label for="contact-subject">Subject</label></div>
                            <div class="col-12 floating-field"><textarea class="form-control" name="message" id="contact-message" rows="5" required maxlength="5000" placeholder=" ">{{ old('message') }}</textarea><label for="contact-message">Message</label></div>
                            <div class="col-12"><button class="btn btn-gold submit-button" type="submit"><span>Send Message</span></button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
