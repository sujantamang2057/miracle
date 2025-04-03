window.swal = require('sweetalert2/dist/sweetalert2.all');
$(function () {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });

    document.querySelectorAll('.nav-link').forEach((link) =>
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        })
    );

    jQuery('img.svg').each(function () {
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        jQuery.get(
            imgURL,
            function (data) {
                // Get the SVG tag, ignore the rest
                var $svg = jQuery(data).find('svg');

                // Add replaced image's ID to the new SVG
                if (typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                // Add replaced image's classes to the new SVG
                if (typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass + ' replaced-svg');
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                $svg = $svg.removeAttr('xmlns:a');

                // Replace image with new SVG
                $img.replaceWith($svg);
            },
            'xml'
        );
    });

    //sticky header
    $(window).scroll(function () {
        if ($(window).scrollTop() >= 200) {
            $('header').addClass('header-top-sticky');
        } else {
            $('header').removeClass('header-top-sticky');
        }
    });

    // banner background image src
    $('.isBGImg').each(function () {
        var imgURL = $(this).find('img').attr('src');
        $(this).css('background-image', 'url(' + imgURL + ')');
    });

    $('.activity-page .hover-effect figure').each(function () {
        $(this)
            .on('mouseover', function () {
                $('body').addClass('hover');
                $('.tour-category-list a').removeClass('active');
                $(this).parent().addClass('active');
            })
            .on('mouseleave', function () {
                $('body').removeClass('hover');
            });
    });

    $('.activity-box').slice(0, 8).show();
    $('#loadMore').on('click', function (e) {
        e.preventDefault();
        $('.activity-box:hidden').slice(0, 4).slideDown();
        if ($('.activity-box:hidden').length == 0) {
            $('#loadMore').text('No Content').addClass('noContent');
        }
    });
});

$(function () {
    initScrollToTop();
    mouseHoverEffect();
});
// End $(function)

function initScrollToTop() {
    //Check to see if the window is top if not then display button
    $(window).on('scroll', function () {
        var scrollTop = $(window).scrollTop(),
            docHeight = $(document).height(),
            winHeight = $(window).height(),
            scrollPercent = scrollTop / (docHeight - winHeight),
            scrollPercentRounded = Math.round(scrollPercent * 100);
        if (scrollPercentRounded > 15) {
            $('.topTop').css({
                opacity: 1,
                transform: 'translateY(-50%)',
            });
        } else {
            $('.topTop').css({
                opacity: 0,
                transform: 'translateY(50%)',
            });
        }
    });

    // Click event to scroll to top
    $('.topTop').on('click', function () {
        $('html, body').animate(
            {
                scrollTop: 0,
            },
            1200
        );
        return false;
    });
}

function mouseHoverEffect() {
    $('.activity-page .hover-effect figure').each(function () {
        $(this)
            .on('mouseenter', function () {
                $('body').addClass('hover');
                $('.tour-category-list a').removeClass('active');
                $(this).parent().addClass('active');

                var imgSrc = $(this).find('img').attr('src');
                $('.bg-hover-img.bg-hover-img-gallery img')
                    .attr('src', imgSrc)
                    .css('visibility', 'visible')
                    .css('opacity', 1);
                $('.bg-hover-img.bg-hover-img-gallery')
                    .css('visibility', 'visible')
                    .css('opacity', 1);
                $('section.sc-about-our-tours').css(
                    'background',
                    'transparent'
                );
            })
            .on('mouseleave', function () {
                $('body').removeClass('hover');
                $('.bg-hover-img.bg-hover-img-gallery')
                    .css('visibility', 'hidden')
                    .css('opacity', 0);
            });
    });
}
