document.addEventListener("DOMContentLoaded", function () {

    const leaderSwiper = new Swiper('.leader-swiper', {

        slidesPerView: 1,
        spaceBetween: 10,
        mousewheel: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        pagination: {
            el: ".swiper-pagination",
            // type: "progressbar",
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30
            }
        }


    });

    const colleagueSwiper = new Swiper('.colleagues-swiper', {
        direction: "vertical",
        slidesPerView: 2,
        spaceBetween: 30,
        mousewheel: true,
    });

    // Enable autoplay on mouse enter and disable on mouse leave
    const swiperContainer = document.querySelector('.colleagues-swiper');

    swiperContainer.addEventListener('mouseenter', function () {
        colleagueSwiper.autoplay.start(); // Start autoplay when mouse enters the swiper
    });

    swiperContainer.addEventListener('mouseleave', function () {
        colleagueSwiper.autoplay.stop(); // Stop autoplay when mouse leaves the swiper
    });

    const friendSwiper = new Swiper('.friends-swiper', {
        direction: "vertical",
        slidesPerView: 2,
        spaceBetween: 30,
        mousewheel: true,
    });

    // Enable autoplay on mouse enter and disable on mouse leave
    const friendSwiperContainer = document.querySelector('.friends-swiper');

    friendSwiperContainer.addEventListener('mouseenter', function () {
        friendSwiper.autoplay.start(); // Start autoplay when mouse enters the swiper
    });

    friendSwiperContainer.addEventListener('mouseleave', function () {
        friendSwiper.autoplay.stop(); // Stop autoplay when mouse leaves the swiper
    });

});
