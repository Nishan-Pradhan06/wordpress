(function ($) {
  "use strict";

  var $main_window = $(window);

  /*====================================
  preloader js
  ======================================*/
  $main_window.on("load", function () {
    $(".preloader").fadeOut("slow");
  });

  // Home Slider 
    $('.home-slider').owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        nav: false,
        dots: true,
        autoplay: true,
        autoplayHoverPause: true,
        smartSpeed:2000
    })

function accessgymnas() {
$( document ).on( 'keydown', function( e ) {
    if ( $( window ).width() > 992 ) {
        return;
    }
    var activeElement = document.activeElement;
    var menuItems = $( '#nav-content .menu-item > a' );
    var firstEl = $( '.menu-toggle' );
    var lastEl = menuItems[ menuItems.length - 1 ];
    var tabKey = event.keyCode === 9;
    var shiftKey = event.shiftKey;
    if ( ! shiftKey && tabKey && lastEl === activeElement ) {
        event.preventDefault();
        firstEl.focus();
    }
} );
}
accessgymnas();

  /*====================================
  scroll to top js
  ======================================*/
  $(window).on("scroll", function () {
    if ($(this).scrollTop() > 250) {
      $("#c-scroll").fadeIn(200);
    } else {
      $("#c-scroll").fadeOut(200);
    }
  });
  $("#c-scroll").on("click", function () {
    $("html, body").animate({
        scrollTop: 0
      },
      "slow"
    );
    return false;
  });

  /*====================================
     sticky menu js
  ======================================*/

  $main_window.on('scroll', function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 200) {
      $(".affix").addClass("sticky-menu");
    } else {
      $(".affix").removeClass("sticky-menu");
    }
  });



  /*====================================
  toggle search
  ======================================*/
  $('.menu-search a').on("click", function () {
    $('.menu-search-form').toggleClass('s-active');
  });


  /*====================================
      navigation mobile menu
  ======================================*/

  function mainmenu() {
    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
      if (!$(this).next().hasClass('show')) {
        $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
      }
      var $subMenu = $(this).next(".dropdown-menu");
      $subMenu.toggleClass('show');

      return false;
    });
  }
  mainmenu();
  
/*================================================
              Start Testimonilas
=================================================*/

$(function () {
    'use strict';
    $(".testimonials-content").owlCarousel({
        dots:true,
        nav:false,
        loop: true,
        autoplay: true,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
            },
            600:{
                items:1,
                nav:false
            },

            800:{
                items:2,
            },
            1500:{
                items:3,
                nav:false
            }
        }
    });
});

})(jQuery);