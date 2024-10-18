const totalReviews = document.querySelectorAll(".review-ext .review-slider .review");

if(totalReviews.length === 0 || totalReviews.length === 1) {
  const reviewSliderWrapper = document.querySelector(".review-slider-wrapper");
  reviewSliderWrapper.classList.add("review-single");
}
else if(totalReviews.length === 2) {
  const swiper = new Swiper(".swiper", {
    // Optional parameters
    loop: true,
    slidesPerView: 1.1,
    spaceBetween: 20,
    // centeredSlides: true, 
    breakpoints: {
      // when window width is >= 
      980: {
        slidesPerView: 2,
      },
    },
    // Navigation arrows
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
}
else {
  const swiper = new Swiper(".swiper", {
    // Optional parameters
    loop: true,
    slidesPerView: 1.1,
    spaceBetween: 20,
    // centeredSlides: true, 
    breakpoints: {
      // when window width is >= 
      980: {
        slidesPerView: 2,
      },
      // when window width is >= 
      1340: {
        slidesPerView: 3,
      },
    },
  
    // Navigation arrows
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
}
