(() => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const preloader = document.querySelector('.site-preloader');
    window.addEventListener('load', () => {
        if (preloader) setTimeout(() => preloader.classList.add('loaded'), 220);
    });

    const navbar = document.querySelector('.navbar');
    const progress = document.querySelector('.scroll-progress span');
    const backTop = document.querySelector('.back-to-top');
    const updateScroll = () => {
        const y = window.scrollY;
        if (navbar) navbar.classList.toggle('scrolled', y > 24);
        if (backTop) backTop.classList.toggle('visible', y > 700);
        if (progress) {
            const height = document.documentElement.scrollHeight - window.innerHeight;
            progress.style.width = (height > 0 ? Math.min(100, y / height * 100) : 0) + '%';
        }
    };
    window.addEventListener('scroll', updateScroll, { passive: true });
    updateScroll();
    if (backTop) backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reducedMotion ? 'auto' : 'smooth' }));

    const savedTheme = localStorage.getItem('portfolio-theme');
    if (savedTheme) document.body.classList.toggle('dark', savedTheme === 'dark');
    else if (document.documentElement.dataset.theme === 'dark') document.body.classList.add('dark');
    document.querySelector('.theme-toggle')?.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        localStorage.setItem('portfolio-theme', document.body.classList.contains('dark') ? 'dark' : 'light');
    });

    const revealItems = document.querySelectorAll('.reveal');
    if (!reducedMotion && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealItems.forEach(item => observer.observe(item));
    } else {
        revealItems.forEach(item => item.classList.add('visible'));
    }

    document.querySelectorAll('.skill-track span').forEach(bar => {
        const observer = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) {
                bar.style.width = bar.dataset.level + '%';
                observer.disconnect();
            }
        }, { threshold: 0.5 });
        observer.observe(bar);
    });

    const typing = document.querySelector('.typing-text');
    if (typing && !reducedMotion) {
        const phrases = (typing.dataset.phrases || '').split('|').filter(Boolean);
        let phraseIndex = 0, charIndex = 0, deleting = false;
        const type = () => {
            if (!phrases.length) return;
            const phrase = phrases[phraseIndex];
            typing.textContent = deleting ? phrase.slice(0, --charIndex) : phrase.slice(0, ++charIndex);
            let delay = deleting ? 38 : 72;
            if (!deleting && charIndex === phrase.length) { deleting = true; delay = 1500; }
            else if (deleting && charIndex === 0) { deleting = false; phraseIndex = (phraseIndex + 1) % phrases.length; delay = 350; }
            setTimeout(type, delay);
        };
        typing.textContent = '';
        setTimeout(type, 300);
    }

    document.querySelectorAll('.project-filters button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.project-filters button').forEach(item => item.classList.remove('active'));
            button.classList.add('active');
            document.querySelectorAll('.project-card-wrap').forEach(card => {
                card.classList.toggle('is-hidden', button.dataset.filter !== 'all' && card.dataset.category !== button.dataset.filter);
            });
        });
    });

    if (!reducedMotion && window.matchMedia('(pointer:fine)').matches) {
        document.querySelectorAll('[data-tilt]').forEach(card => {
            card.addEventListener('mousemove', event => {
                const rect = card.getBoundingRect ? card.getBoundingRect() : card.getBoundingClientRect();
                const x = (event.clientX - rect.left) / rect.width - 0.5;
                const y = (event.clientY - rect.top) / rect.height - 0.5;
                card.style.transform = `perspective(900px) rotateY(${x * 4}deg) rotateX(${y * -4}deg) translateY(-4px)`;
            });
            card.addEventListener('mouseleave', () => { card.style.transform = ''; });
        });
    }

    document.querySelectorAll('.magnetic').forEach(button => {
        button.addEventListener('mousemove', event => {
            if (reducedMotion) return;
            const rect = button.getBoundingClientRect();
            button.style.transform = `translate(${(event.clientX - rect.left - rect.width / 2) * .08}px,${(event.clientY - rect.top - rect.height / 2) * .08}px)`;
        });
        button.addEventListener('mouseleave', () => { button.style.transform = ''; });
    });

    const sections = document.querySelectorAll('main section[id]');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    if ('IntersectionObserver' in window) {
        const sectionObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    navLinks.forEach(link => link.classList.toggle('active', link.getAttribute('href')?.endsWith('#' + entry.target.id)));
                }
            });
        }, { rootMargin: '-35% 0px -55%' });
        sections.forEach(section => sectionObserver.observe(section));
    }

    document.querySelector('.contact-form')?.addEventListener('submit', event => {
        const button = event.currentTarget.querySelector('.submit-button');
        if (button) {
            button.disabled = true;
            button.querySelector('span').textContent = 'Sending…';
        }
    });
})();
