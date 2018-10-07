jQuery(document).ready(function () {

    jQuery('.portdesign-meta-fields p').each(function () {
        var classname = jQuery(this).attr('class');
        var maxHeight = Math.max.apply(null, jQuery("." + classname).map(function () {
            return jQuery(this).height();
        }).get());
        jQuery("." + classname + " span").height(maxHeight);
    });

    // For isotpes
    if (jQuery('.portfolio_filter_gallery').length > 0) {
        var $layoutMode = 'fitRows';
        if (jQuery('.portfolio_gallery').hasClass('masonry')) {
            $layoutMode = 'masonry';
        }
        jQuery('.portfolio_filter_class .portfolio_gallery').isotope({
            itemSelector: '.pdl_single_wrapp',
            layoutMode: $layoutMode,
            containerStyle: {
                position: 'relative',
                overflow: 'visible'
            },
            getSortData: {
                category: '[data-slug]'
            }
        });
        jQuery('.portfolio_filter_gallery_ul').on('click', 'li', function () {
            jQuery('.portfolio_filter_gallery_ul li a').removeClass('portfolio_gallery_selected');
            jQuery(this).children('a').addClass('portfolio_gallery_selected');
            var filterValue = jQuery(this).attr('data-filter');
            jQuery(this).closest('.portfolio_filter_class').find('.portfolio_gallery').isotope({filter: filterValue});
        });
    }

    if (jQuery('.portfolio_gallery_masonary').length > 0) {

        jQuery('.portfolio_gallery_masonary').imagesLoaded(function () {
            jQuery('.portfolio_gallery_masonary').masonry({
                itemSelector: '.pdl_single_wrapp',
                animate: true,
                animationOptions: {
                    duration: 700,
                    queue: true
                }
            });
            jQuery('.portfolio_gallery_masonary').masonry('reload').masonry('layout');
        });
    }

    // Fancybox Setup
    jQuery('.port_fancybox').fancybox({
        titlePosition: 'over',
        margin: 60,
        centerOnScroll: true
    });

    // whatsapp share only in mobile
    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    if (!isMobile.any()) {
        jQuery('.social-component .pdl-whatsapp-share, .social-component .whatsapp-share').remove();
    }
    jQuery(document).on("click", '.pdl-whatsapp-share, .whatsapp-share', function () {
        if (isMobile.any()) {

            var text = jQuery(this).attr("data-text");
            var url = jQuery(this).attr("data-link");
            var message = encodeURIComponent(text) + " - " + encodeURIComponent(url);
            var whatsapp_url = "whatsapp://send?text=" + message;
            window.location.href = whatsapp_url;
        } else {
            alert(portdesigner_front_translations.whatsapp_mobile_alert);
        }
    });
    pdl_image_content_height();
});

jQuery(document).resize(function () {
    pdl_image_content_height();

    // For isotpes
    if (jQuery('.portfolio_filter_gallery').length > 0) {
        var $layoutMode = 'fitRows';
        if (jQuery('.portfolio_gallery').hasClass('masonry')) {
            $layoutMode = 'masonry';
        }
        jQuery('.portfolio_filter_class .portfolio_gallery').isotope({
            itemSelector: '.pdl_single_wrapp',
            layoutMode: $layoutMode,
            containerStyle: {
                position: 'relative',
                overflow: 'visible'
            },
            getSortData: {
                category: '[data-slug]'
            }
        });
        jQuery('.portfolio_filter_gallery_ul').on('click', 'li', function () {
            jQuery('.portfolio_filter_gallery_ul li a').removeClass('portfolio_gallery_selected');
            jQuery(this).children('a').addClass('portfolio_gallery_selected');
            var filterValue = jQuery(this).attr('data-filter');
            jQuery(this).closest('.portfolio_filter_class').find('.portfolio_gallery').isotope({filter: filterValue});
        });
    }

    if (jQuery('.portfolio_gallery_masonary').length > 0) {

        jQuery('.portfolio_gallery_masonary').masonry({
            itemSelector: '.pdl_single_wrapp',
            animate: true,
            animationOptions: {
                duration: 700,
                queue: true
            }
        });
    }
});

jQuery(document).load(function () {
    // For isotpes
    if (jQuery('.portfolio_filter_gallery').length > 0) {
        var $layoutMode = 'fitRows';
        if (jQuery('.portfolio_gallery').hasClass('masonry')) {
            $layoutMode = 'masonry';
        }
        jQuery('.portfolio_filter_class .portfolio_gallery').isotope({
            itemSelector: '.pdl_single_wrapp',
            layoutMode: $layoutMode,
            containerStyle: {
                position: 'relative',
                overflow: 'visible'
            },
            getSortData: {
                category: '[data-slug]'
            }
        });
        jQuery('.portfolio_filter_gallery_ul').on('click', 'li', function () {
            jQuery('.portfolio_filter_gallery_ul li a').removeClass('portfolio_gallery_selected');
            jQuery(this).children('a').addClass('portfolio_gallery_selected');
            var filterValue = jQuery(this).attr('data-filter');
            jQuery(this).closest('.portfolio_filter_class').find('.portfolio_gallery').isotope({filter: filterValue});
        });
    }

    if (jQuery('.portfolio_gallery_masonary').length > 0) {

        jQuery('.portfolio_gallery_masonary').imagesLoaded(function () {
            jQuery('.portfolio_gallery_masonary').masonry({
                itemSelector: '.pdl_single_wrapp',
                animate: true,
                animationOptions: {
                    duration: 700,
                    queue: true
                }
            });
            jQuery('.portfolio_gallery_masonary').masonry('reload').masonry('layout');
        });
    }
});

function pdl_image_content_height() {

    if (jQuery('.portfolio_gallery').hasClass('column-layput-1')) {
        jQuery('.pdl_single_wrapp').each(function () {
            var mask_height = Math.max.apply(null, jQuery(this).find(jQuery('.portfolio-image-content')).map(function () {
                return jQuery(this).height();
            }).get());

            jQuery(this).find(jQuery('.portfolio-image-content')).css('min-height', mask_height);
        });
    } else {
        if (jQuery('.pdl_single_wrapp').hasClass('left_side') || jQuery('.pdl_single_wrapp').hasClass('right_side')) {
            var mask_height = Math.max.apply(null, jQuery('.portfolio-image-content').map(function () {
                return jQuery(this).height();
            }).get());

            jQuery('.pdl_single_wrapp .portfolio-image-content').css('min-height', mask_height);
            jQuery('.pdl_single_wrapp .portfolio-image-content > a').css('min-height', mask_height);
            jQuery('.pdl_single_wrapp .portfolio-image-content > a img').css('min-height', mask_height);
        }
    }

}