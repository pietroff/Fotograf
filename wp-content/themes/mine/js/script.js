jQuery(document).ready(function () {

    jQuery('.main-navigation div').meanmenu({
        meanScreenWidth: 1024
    });

    // Scroll to top
    jQuery(".arrow-up").click(function () {
        jQuery("html, body").animate({scrollTop: 0}, 1000);
    });

    jQuery('[data-toggle="tooltip"]').tooltip();

    jQuery(window).scroll(function () {
        var scroll = jQuery(window).scrollTop();
        if (scroll > 150) {
            jQuery(".arrow-up").fadeIn();
        } else {
            jQuery(".arrow-up").fadeOut();
        }
    });

    /* --------- header menu search and cart icon start ---------*/
    jQuery(".menu-search-li a").click(function () {
        jQuery(".menu-search-li .search-form").slideToggle("slow");
    });

    /*Cart Slide up and down*/
    jQuery(document).delegate(".dropdowncartwidget", 'mouseenter', function () {
        jQuery(".dropdowncartwidget").stop(true, true).slideDown();
    });
    jQuery(document).delegate(".dropdowncartwidget", 'mouseleave', function () {
        jQuery(".dropdowncartwidget").stop(true, true).delay(10).slideUp();
    });

    /* --------- header menu search and cart icon end ---------*/

    // loop through each item to check when it animates
    var wintop = jQuery(window).scrollTop();
    var winheight = jQuery(window).height();
    
    var $titles_width = 0;
    jQuery('.author_skill .progressbar').each(function () {        
        var $title_width = jQuery(this).find('.progress_title').width();
        if($titles_width < $title_width) {
            $titles_width = $title_width;
        }        
    });
    jQuery('.author_skill .progressbar .progress_title').css('width', $titles_width + 'px');
    
    
    jQuery('.progress_inner').each(function () {
        
        if (wintop > jQuery(this).offset().top - winheight) {            
            var width = jQuery(this).attr('data-width');
            jQuery(this).animate({
                width: width
            }, 1000);
        }
    });  
    
});

    
jQuery(window).scroll(function () {
    
    var wintop = jQuery(window).scrollTop();
    var winheight = jQuery(window).height();
    jQuery('.progress_inner').each(function () {
        if (wintop > jQuery(this).offset().top - winheight) {
            
            var width = jQuery(this).attr('data-width');
            jQuery(this).animate({
                width: width
            }, 1000);
        }
    });  
    
});



jQuery(window).load(function () {
    jQuery(".loader").fadeOut("slow");
});