// jQuery(document).ready(function ($) {
//     function initializeCarousels() {
//         $('.my-slick-carousel').each(function () {
//             var $carousel = $(this);
//             var settings = $carousel.data('slick') || {};
//             console.log('Initial settings:', settings);

//             try {
//                 // Convert string values to proper types
//                 settings.autoplay = settings.autoplay === 'true' || settings.autoplay === true;
//                 settings.arrows = settings.arrows === 'true' || settings.arrows === true;
//                 settings.dots = settings.dots === 'true' || settings.dots === true;
//                 settings.fade = settings.fade === 'true' || settings.fade === true;
//                 settings.infinite = settings.infinite === 'true' || settings.infinite === true;
//                 settings.lazyLoad = settings.lazyLoad === 'ondemand' ? 'ondemand' : false;
//                 settings.speed = parseInt(settings.speed) || 300;
//                 settings.autoplaySpeed = parseInt(settings.autoplaySpeed) || 3000;

//                 var totalSlides = $carousel.find('.slick-slide-item').length;

//                 // Parse & validate slide counts
//                 settings.slidesToShow = Math.min(parseInt(settings.slidesToShow) || 1, totalSlides);
//                 settings.slidesToScroll = Math.min(parseInt(settings.slidesToScroll) || 1, totalSlides);

//                 $carousel.slick({
//                     slidesToShow: settings.slidesToShow,
//                     slidesToScroll: settings.slidesToScroll,
//                     arrows: settings.arrows,
//                     dots: settings.dots,
//                     autoplay: settings.autoplay,
//                     autoplaySpeed: settings.autoplaySpeed,
//                     speed: settings.speed,
//                     fade: settings.fade,
//                     infinite: settings.infinite,
//                     lazyLoad: settings.lazyLoad,
//                     adaptiveHeight: true
//                 });

//                 console.log('Carousel initialized successfully');
//             } catch (e) {
//                 console.error('Error initializing carousel:', e);
//             }
//         });
//     }

//     // Wait for Slick to be ready
//     if (typeof $.fn.slick === 'function') {
//         initializeCarousels();
//     } else {
//         setTimeout(function () {
//             if (typeof $.fn.slick === 'function') {
//                 initializeCarousels();
//             } else {
//                 console.error('Slick slider not loaded!');
//                 $.getScript('https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', function () {
//                     initializeCarousels();
//                 });
//             }
//         }, 500);
//     }
// });



jQuery(document).ready(function($) {
    function initializeCarousels() {
        $('.my-slick-carousel').not('.slick-initialized').each(function() {
            var $carousel = $(this);
            
            try {
                // Parse settings from data attribute
              var settings = JSON.parse($carousel.attr('data-slick') || '{}');
                // Convert settings to proper types
                var convertedSettings = {
                    slidesToShow: Math.max(1, parseInt(settings.slidesToShow) || 1),
                    slidesToScroll: Math.max(1, parseInt(settings.slidesToScroll) || 1),
                    arrows: settings.arrows === '1',
                    dots: settings.dots === '1',
                    autoplay: settings.autoplay === '1',
                    autoplaySpeed: Math.max(300, parseInt(settings.autoplaySpeed) || 3000),
                    speed: Math.max(100, parseInt(settings.speed) || 300),
                    fade: settings.fade === '1',
                    infinite: settings.infinite === '1',
                    lazyLoad: settings.lazyLoad === 'ondemand' ? 'ondemand' : false,
                    cssEase: settings.cssEase || 'ease',
                    adaptiveHeight: true,
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                };

                    // Special handling for linear scrolling
            if (convertedSettings.cssEase === 'linear') {
                convertedSettings.speed = 1000; 
                convertedSettings.autoplaySpeed = 0; 
                convertedSettings.pauseOnHover = false;
                convertedSettings.pauseOnFocus = false;
                $carousel.addClass('linear-scroll-mode');
            } else {
                // Reset to default behavior for 'ease'
                convertedSettings.speed = settings.speed || 300;
                convertedSettings.autoplaySpeed = settings.autoplaySpeed || 3000;
                convertedSettings.pauseOnHover = true;
                convertedSettings.pauseOnFocus = true;
                $carousel.removeClass('linear-scroll-mode');
            }
                
                // Initialize the carousel
                $carousel.slick(convertedSettings);

                 $carousel.on('lazyLoaded', function(event, slick, image, imageSource) {
                    $(image).addClass('loaded');
                });
                
            } catch (e) {
                console.error('Error initializing carousel:', e);
                // Fallback to basic initialization
                $carousel.slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    adaptiveHeight: true
                });
            }
        });
    }

    // Check if Slick is loaded
    if (typeof $.fn.slick === 'function') {
        initializeCarousels();
    } else {
        var checkSlick = setInterval(function() {
            if (typeof $.fn.slick === 'function') {
                clearInterval(checkSlick);
                initializeCarousels();
            }
        }, 100);
    }
});

