document.addEventListener('DOMContentLoaded', () => {
    const toggler = document.querySelector('.navbar-toggler');
    const collapse = document.querySelector('.navbar-collapse');

    if (toggler) {
        toggler.addEventListener('click', () => {
            collapse.classList.toggle('active');
            toggler.classList.toggle('active');
        });
    }

    // Reusable Carousel Logic
    const setupCarousel = (trackSelector, prevBtnSelector, nextBtnSelector, slidesConfig) => {
        const track = document.querySelector(trackSelector);
        if (!track) return;

        const slides = Array.from(track.children);
        const nextButton = document.querySelector(nextBtnSelector);
        const prevButton = document.querySelector(prevBtnSelector);
        
        let currentIndex = 0;
        const autoScrollInterval = 3000;
        let autoScrollTimer;

        const getSlidesPerView = () => {
            const w = window.innerWidth;
            if (w >= 1200) return slidesConfig.xl;
            if (w >= 992) return slidesConfig.lg;
            if (w >= 768) return slidesConfig.md;
            return slidesConfig.sm;
        };

        const updateCarousel = () => {
            if (slides.length === 0) return;
            const slideWidth = slides[0].getBoundingClientRect().width;
            const gap = parseFloat(getComputedStyle(track).gap) || 0;
            const amountToMove = (slideWidth + gap) * currentIndex;
            track.style.transform = `translateX(-${amountToMove}px)`;
        };

        const moveNext = () => {
            const slidesPerView = getSlidesPerView();
            const maxIndex = Math.max(0, slides.length - slidesPerView);
            
            if (currentIndex >= maxIndex) {
                currentIndex = 0;
            } else {
                currentIndex++;
            }
            updateCarousel();
        };

        const movePrev = () => {
            const slidesPerView = getSlidesPerView();
            const maxIndex = Math.max(0, slides.length - slidesPerView);

            if (currentIndex <= 0) {
                currentIndex = maxIndex;
            } else {
                currentIndex--;
            }
            updateCarousel();
        };

        if (nextButton && prevButton) {
            nextButton.addEventListener('click', () => {
                moveNext();
                resetTimer();
            });

            prevButton.addEventListener('click', () => {
                movePrev();
                resetTimer();
            });
        }

        const startTimer = () => {
            if (autoScrollTimer) clearInterval(autoScrollTimer);
            autoScrollTimer = setInterval(moveNext, autoScrollInterval);
        };

        const resetTimer = () => {
            clearInterval(autoScrollTimer);
            startTimer();
        };

        window.addEventListener('resize', () => {
            currentIndex = 0;
            updateCarousel();
        });

        // Start auto-scroll
        startTimer();
        
        // Pause on hover
        track.addEventListener('mouseenter', () => clearInterval(autoScrollTimer));
        track.addEventListener('mouseleave', startTimer);
    };

    // 1. Jobs Carousel
    setupCarousel(
        '.professional-search .carousel-track', 
        '.professional-search .prev-btn', 
        '.professional-search .next-btn',
        { xl: 5, lg: 4, md: 3, sm: 2 }
    );

    // 2. Cities Carousel
    /*const cities = [
        "Annecy", "Le Havre", "Nantes", "Reims",
        "Angers", "Lille", "Nice", "Saint-Étienne",
        "Bordeaux", "Lyon", "Nîmes", "Strasbourg",
        "Dijon", "Marseille", "Paris", "Toulon",
        "Grenoble", "Montpellier", "Rennes", "Toulouse"
    ];*/
    const cities = [
        "Annecy", "Nantes"
    ];
    const citiesTrack = document.querySelector('.cities-track');
    if (citiesTrack) {
        cities.forEach(city => {
            const slide = document.createElement('div');
            slide.className = 'city-slide';
            
            const cityCard = document.createElement('div');
            cityCard.className = 'city-card';
            cityCard.innerHTML = "<h3>" + city + "</h3>";
            cityCard.style.backgroundImage = `url("img/cities/${city.toLowerCase().replace(/ /g, "-").replace(/é/g, "e").replace(/î/g, "i")}.jpg")`;
            
            slide.appendChild(cityCard);
            citiesTrack.appendChild(slide);
        });

        setupCarousel(
            '.cities-track', 
            '.cities-prev', 
            '.cities-next',
            { xl: 4, lg: 3, md: 3, sm: 2 }
        );
    }
});