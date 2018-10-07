jQuery(window).load(function () {    
    // deactivation popup code
    var pdl_plugin_admin = jQuery('.documentation_pdl_plugin').closest('div').find('.deactivate').find('a');
    jQuery('.pdl-deactivation').on('click', function() {
        window.location.href = pdl_plugin_admin.attr('href');
    });
    pdl_plugin_admin.click(function (event) {
        event.preventDefault();
        jQuery('#deactivation_thickbox_pdl').trigger('click');
        jQuery('#TB_window').removeClass('thickbox-loading');
        change_thickbox_size();
    });
    jQuery('.pdl-deactivation').on('click', function() {
        window.location.href = pdl_plugin_admin.attr('href');
    });    
    checkOtherDeactivate();
    jQuery('.sol_deactivation_reasons').click(function () {
        checkOtherDeactivate();
    });
    jQuery('#sbtDeactivationFormClosepdl').click(function (event) {
        event.preventDefault();
        jQuery("#TB_closeWindowButton").trigger('click');
    })
    function checkOtherDeactivate() {
        var selected_option_de = jQuery('input[name=sol_deactivation_reasons_pdl]:checked', '#frmDeactivationpdl').val();
        if (selected_option_de == '8') {
            jQuery('.sol_deactivation_reason_other_pdl').val('');
            jQuery('.sol_deactivation_reason_other_pdl').show();
        }
        else {
            jQuery('.sol_deactivation_reason_other_pdl').val('');
            jQuery('.sol_deactivation_reason_other_pdl').hide();
        }
    }
    
    function change_thickbox_size() {
        jQuery(document).find('#TB_window').width('750').height('450').css('margin-left', -700 / 2);
        jQuery(document).find('#TB_ajaxContent').width('700');
        var doc_height = jQuery(window).height();
        var doc_space = doc_height - 450;
        if (doc_space > 0) {
            jQuery(document).find('#TB_window').css('margin-top', doc_space / 2);
        }
    }
});

jQuery(document).ready(function () {

    /* Licence Page Version, Newsletter, and Update history. */
    jQuery('.port-info-block .port-info-heading').click(function () {
        if (jQuery(this).parent('div.port-info-block').hasClass('closed')) {
            jQuery(this).parent('div.port-info-block').removeClass('closed');
        } else {
            jQuery(this).parent('div.port-info-block').addClass('closed');
        }
    });

    pdl_click_disable();

    jQuery('#portfolio_title_font, #portfolio_content_font, #portfolio_meta_font, #portfolio_button_font').change(function () {
        var $name = jQuery(this).attr('name');
        var selected = jQuery(':selected', this);
        var $label = selected.closest('optgroup').attr('label');
        jQuery('#' + $name + '_type').val($label);
    });

    jQuery('.port-setting-handle > li').click(function (event) {
        if (jQuery(this).hasClass('clickDisable')) {
            pdl_click_disable();
        } else {
            var section = jQuery(this).data('show');
            jQuery('.port-setting-handle > li').removeClass('port-active-tab');
            jQuery.post(ajaxurl, {
                action: 'pd_show_selected_tab',
                closed: section,
                page: jQuery('.portfoliooriginalpage').val()
            });
            jQuery(this).addClass('port-active-tab');
            jQuery('.pdl-settings-wrappers .postbox').hide();
            jQuery('#' + section).show();
        }
    });
    jQuery('.pdl_theme_plugin li a').click(function (e) {
        e.preventDefault();
        jQuery('.pdl_theme_plugin li').removeClass('active');
        var $name = jQuery(this).attr('data-toggle');
        jQuery(this).parent('li').addClass('active');
        jQuery('.pdl-our-other-work .pdl-info-content > div').hide();
        jQuery('#' + $name).show();
    });
    
    /* Portfolio Filter Apply */
    if(jQuery('input[name=portfolio_enable_filter]:checked').val() == 1) {
        jQuery('.filter_data_tr').show();
        jQuery('.portfolio_number_post_tr').hide();
        jQuery('.portdesignpagination').addClass('clickDisable');
    } else {
        jQuery('.filter_data_tr').hide();
        jQuery('.portfolio_number_post_tr').show();
        jQuery('.portdesignpagination').removeClass('clickDisable');
    }

    jQuery('input[name=portfolio_enable_filter]').click(function () {
        if (jQuery(this).val() == 1) {
            jQuery('.filter_data_tr').fadeIn();
            jQuery('.portfolio_number_post_tr').hide();
            jQuery('.portdesignpagination').addClass('clickDisable');
        } else {
            jQuery('.filter_data_tr').fadeOut();
            jQuery('.portfolio_number_post_tr').show();
            jQuery('.portdesignpagination').removeClass('clickDisable');
        }
    });

    /* Script for input type number start */
    jQuery('<div class="input-number-nav"><div class="input-number-button number-up">+</div><div class="input-number-button number-down">-</div></div>').insertAfter('.input-number-cover input');
    jQuery('.input-number-cover').each(function () {
        var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.number-up'),
                btnDown = spinner.find('.number-down'),
                min = input.attr('min'),
                max = input.attr('max');

        btnUp.click(function () {
            var oldValue = parseFloat(input.val());
            if (oldValue >= max) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue + 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
        });

        btnDown.click(function () {
            var oldValue = parseFloat(input.val());
            if (oldValue <= min) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue - 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
        });

    });

    /* Script for input type number end */
    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {allow_single_deselect: true},
        '.chosen-select-no-single': {disable_search_threshold: 10},
        '.chosen-select-no-results': {no_results_text: portdesigner_admin_translations.no_found},
        '.chosen-select-width': {width: "95%"}
    }

    for (var selector in config) {
        jQuery(selector).chosen(config[selector]);
    }

    /* Add slider for font family */
    jQuery("#portfolio_title_font_size_slider,#portfolio_content_font_size_slider,#portfolio_meta_font_size_slider,#portfolio_button_font_size_slider,#portfolio_button_radius_slider,#portfolio_border_radius_slider").slider({
        range: "min",
        value: 1,
        step: 1,
        min: 0,
        max: 200,
        slide: function (event, ui) {
            jQuery(this).closest('tr').find('input.range-slider__value').val(ui.value);
        }
    });
    jQuery("#portfolio_button_radius_slider, #portfolio_border_radius_slider").slider({
        range: "min",
        value: 1,
        step: 1,
        min: 0,
        max: 100,
        slide: function (event, ui) {
            jQuery(this).closest('tr').find('input.range-slider__value').val(ui.value);
        }
    });

    var portfolio_title_font_size = jQuery("#portfolio_title_font_size").val(),
            portfolio_content_font_size = jQuery("#portfolio_content_font_size").val(),
            portfolio_meta_font_size = jQuery("#portfolio_meta_font_size").val(),
            portfolio_button_font_size = jQuery("#portfolio_button_font_size").val(),
            portfolio_button_radius = jQuery("#portfolio_button_radius").val();
            portfolio_border_radius = jQuery("#portfolio_border_radius").val();
    jQuery("#portfolio_title_font_size_slider").slider("value", portfolio_title_font_size);
    jQuery("#portfolio_content_font_size_slider").slider("value", portfolio_content_font_size);
    jQuery("#portfolio_meta_font_size_slider").slider("value", portfolio_meta_font_size);
    jQuery("#portfolio_button_font_size_slider").slider("value", portfolio_button_font_size);
    jQuery("#portfolio_button_radius_slider").slider("value", portfolio_button_radius);
    jQuery("#portfolio_border_radius_slider").slider("value", portfolio_border_radius);

    jQuery(".range-slider__value").change(function () {
        var value = this.value;
        var max = 200;
        if (value > max) {
            jQuery(this).parents('.font_size_cover').find('.range_slider_fontsize').slider("value", '200');
            jQuery(this).val('200');
        } else {
            jQuery(this).parents('.font_size_cover').find('.range_slider_fontsize').slider("value", parseInt(value));
        }
    });

    /* Setting Tab Open Close */
    jQuery('.pdltab .handlediv, .pdltab .hndle').click(function (event) {
        if (jQuery(this).siblings('.inside').is(':visible')) {
            jQuery(this).siblings('.inside').slideUp().parent('div.postbox').addClass('tab_closed');
        } else {
            jQuery(this).siblings('.inside').slideDown().parent('div.postbox').removeClass('tab_closed');
        }
    });

    jQuery('.buttonset').buttonset();

    /*validation for number*/
    jQuery(".numberOnly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                                (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });


    /* For Color Picker */
    jQuery('.portfolio-color-picker').wpColorPicker({
        // a callback to fire whenever the color changes to a valid color
        change: function (event, ui) {
            // Change only if the color picker is the user choice
        },
        // a callback to fire when the input is emptied or an invalid color
        clear: function () {
        },
        // hide the color picker controls on load
        hide: true,
        // show a group of common colors beneath the square
        // or, supply an array of colors to customize further
        palettes: true
    });

    if (jQuery('#portfolio_categories').length == 0) {
        jQuery('.portfolio_categories_tr').hide();
    }

    /* Get Portfolio taxonomy*/
    jQuery(document).on('change', '#portfolio_layout_post', function () {

        var portfolio_post = jQuery(this).val();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_portfolio_taxonomy',
                posttype: portfolio_post,
            },
            success: function (response) {
                jQuery('.portfolio_taxonomy_tr').html(response);
                if (response == 0 || response == '') {
                    jQuery('.portfolio_taxonomy_tr').hide();
                    jQuery('.portfolio_categories_tr').hide();
                } else {
                    jQuery('.portfolio_taxonomy_tr').show();
                }
            }
        });
        var posttype = portfolio_post;
        if (posttype != 'page') {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'get_portfolio_terms_from_posts',
                    posttype: posttype,
                },
                success: function (response) {
                    jQuery('.portfolio_categories_tr').html(response);
                    for (var selector in config) {
                        jQuery(selector).chosen(config[selector]);
                    }
                    if (response == 0) {
                        jQuery('.portfolio_categories_tr').hide();
                    } else {
                        jQuery('.portfolio_categories_tr').show();
                    }

                    var $post_type = jQuery('#portfolio_layout_post').val();
                    var $taxonomy = jQuery('#portfolio_taxonomy').val();
                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: 'get_portfolio_posts',
                            post_type: $post_type,
                            taxonomy: $taxonomy
                        },
                        success: function (response) {
                            jQuery('.portfolio_post_td').html(response);
                            for (var selector in config) {
                                jQuery(selector).chosen(config[selector]);
                            }
                        }
                    });
                    jQuery(".select-cover select").chosen({no_results_text: portdesigner_admin_translations.no_found});
                }
            });

        } else {
            jQuery('.portfolio_categories_tr').html();
            jQuery('.portfolio_categories_tr').hide();

            var $post_type = jQuery('#portfolio_layout_post').val();
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'get_portfolio_posts',
                    post_type: $post_type,
                },
                success: function (response) {
                    jQuery('.portfolio_post_td').html(response);
                    for (var selector in config) {
                        jQuery(selector).chosen(config[selector]);
                    }
                    jQuery(".select-cover select").chosen({no_results_text: portdesigner_admin_translations.no_found});
                }
            });
        }
        get_custom_post();
    });

    /* Get Portfolio Terms*/
    jQuery(document).on('change', '#portfolio_taxonomy', function () {
        var portfolio_taxonomy = jQuery(this).val();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_portfolio_terms',
                posttaxonomy: portfolio_taxonomy,
            },
            success: function (response) {
                jQuery('.portfolio_categories_tr').html(response);
                for (var selector in config) {
                    jQuery(selector).chosen(config[selector]);
                }
                if (response == 0) {
                    jQuery('.portfolio_categories_tr').hide();
                } else {
                    jQuery('.portfolio_categories_tr').show();
                }

                var $post_type = jQuery('#portfolio_layout_post').val();
                var $taxonomy = jQuery('#portfolio_taxonomy').val();
                jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'get_portfolio_posts',
                        post_type: $post_type,
                        taxonomy: $taxonomy,
                    },
                    success: function (response) {
                        jQuery('.portfolio_post_td').html(response);
                        for (var selector in config) {
                            jQuery(selector).chosen(config[selector]);
                        }
                    }
                });
            }
        });

    });

    jQuery(document).on('change', '#portfolio_categories', function () {
        var $post_type = jQuery('#portfolio_layout_post').val();
        var $taxonomy = jQuery('#portfolio_taxonomy').val();
        var $categories = jQuery('#portfolio_categories').val();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_portfolio_posts',
                post_type: $post_type,
                taxonomy: $taxonomy,
                categories: $categories,
            },
            success: function (response) {
                jQuery('.portfolio_post_td').html(response);
                for (var selector in config) {
                    jQuery(selector).chosen(config[selector]);
                }
            }
        });
    });

    pdl_get_terms();

    /* Portfolio Layout Changes */
    var $layout_type = jQuery('#portfolio_layout_type').val();
    jQuery(".display_type_portfolio b").addClass("hide");
    jQuery("#" + $layout_type).removeClass("hide");

    if ($layout_type == 'slider') {
        jQuery('.portdesignfilter').addClass('clickDisable');
        jQuery('.portdesignpagination').addClass('clickDisable');
        pdl_click_disable();
    }

    jQuery('#portfolio_layout_type').change(function () {
        if (jQuery(this).val() == 'grid') {
            jQuery(".display_type_portfolio b").addClass("hide");
            jQuery("#grid").removeClass("hide");
            jQuery('.portdesignfilter').removeClass('clickDisable');
            jQuery('.portdesignpagination').removeClass('clickDisable');
            pdl_click_disable();
        }
        if (jQuery(this).val() == 'masonary') {
            jQuery(".display_type_portfolio b").addClass("hide");
            jQuery("#masonary").removeClass("hide");
            jQuery('.portdesignfilter').removeClass('clickDisable');
            jQuery('.portdesignpagination').removeClass('clickDisable');

            pdl_click_disable();
        }
    });

    var portfolio_type = jQuery('#portfolio_layout_type').val();
    jQuery('.portfolio_simple_layout').hide();
    jQuery('.portfolio_slider_layout').hide();
    if (portfolio_type == 'slider') {
        jQuery('.portfolio_simple_layout').hide();
        jQuery('.portfolio_slider_layout').fadeIn();
    } else {
        jQuery('.portfolio_slider_layout').hide();
        jQuery('.portfolio_simple_layout').fadeIn();
    }

    jQuery('#portfolio_layout_type').on('change', function () {
        var portfolio_type = jQuery(this).val();
        if (portfolio_type == 'slider') {
            jQuery('.portfolio_simple_layout').hide();
            jQuery('.portfolio_slider_layout').fadeIn();
        } else {
            jQuery('.portfolio_slider_layout').hide();
            jQuery('.portfolio_simple_layout').fadeIn();
        }
    });

    /*Custom category Taxonomy Changes*/
    jQuery('.prtfolio_taxonomy_category_tr').hide();
    if (jQuery('#prtfolio_taxonomy_category').prop('checked') == true) {
        jQuery('.prtfolio_taxonomy_category_tr').show();
    } else {
        jQuery('.prtfolio_taxonomy_category_tr').hide();
    }

    /*Custom Tag Taxonomy Changes*/
    jQuery('.prtfolio_taxonomy_tag_tr').hide();
    if (jQuery('#prtfolio_taxonomy_tag').prop('checked') == true) {
        jQuery('.prtfolio_taxonomy_tag_tr').show();
    } else {
        jQuery('.prtfolio_taxonomy_tag_tr').hide();
    }
    /* popup closed */
    jQuery('.ui-widget-overlay').live("click", function () {
        jQuery("#pdl-advertisement-popup").dialog('close');
    });

    /* Portfolio Layout Delete */
    jQuery('.pdl-portfolio-delete').on('click', function () {
        var confirm = window.confirm(portdesigner_admin_translations.portfolio_layout_delete);
        if (!confirm) {
            return;
        } else {
            var delete_data = jQuery(this).attr('data-delete');
            window.location.href = delete_data;
        }

    });

    /* Portfolio Layout Duplicate */
    jQuery('.pdl-portfolio-duplicate').on('click', function () {
        var confirm = window.confirm(portdesigner_admin_translations.portfolio_layout_duplicate);
        if (!confirm) {
            return;
        } else {
            var duplicate_data = jQuery(this).attr('data-delete');
            window.location.href = duplicate_data;
        }
    });

    /* Portfolio Effects Add */
    jQuery('.portfolio_summary_tr').show();
    jQuery('.portfolio_border_radius_tr').hide();
    if (jQuery('#portfolio_content_position').val() == 'overlay_image') {
        var $excerpt_null_array = ['skate_top', 'skate_bottom', 'shift_top', 'shift_bottom', 'door_slide', 'reducer', 'retard_top', 'retard_bottom'];
        var $image_effect = jQuery('#portfolio_image_effect').val();
        jQuery('.portfolio_border_radius_tr').fadeIn();
        jQuery('.portfolio_summary_tr').hide();
        if (jQuery.inArray($image_effect, $excerpt_null_array) !== -1) {
            jQuery('.portfolio_summary_tr').fadeOut();
        } else {
            jQuery('.portfolio_summary_tr').fadeIn();
        }
        jQuery('#portfolio_image_effect').on('change', function () {
            $image_effect = jQuery(this).val();
            if (jQuery.inArray($image_effect, $excerpt_null_array) !== -1) {
                jQuery('.portfolio_summary_tr').fadeOut();
            } else {
                jQuery('.portfolio_summary_tr').fadeIn();
            }
        });
    }    

    jQuery(document).on('change', '#portfolio_content_position', function () {
        if (jQuery(this).val() == 'overlay_image') {
            var $excerpt_null_array = ['skate_top', 'skate_bottom', 'shift_top', 'shift_bottom', 'door_slide', 'reducer', 'retard_top', 'retard_bottom'];
            var $image_effect = jQuery('#portfolio_image_effect').val();

            jQuery('.portfolio_summary_tr').hide();
            jQuery('.portfolio_border_radius_tr').fadeIn();
            if (jQuery.inArray($image_effect, $excerpt_null_array) !== -1) {
                jQuery('.portfolio_summary_tr').fadeOut();
            } else {
                jQuery('.portfolio_summary_tr').fadeIn();
            }
            jQuery('#portfolio_image_effect').on('change', function () {
                $image_effect = jQuery(this).val();
                if (jQuery.inArray($image_effect, $excerpt_null_array) !== -1) {
                    jQuery('.portfolio_summary_tr').fadeOut();
                } else {
                    jQuery('.portfolio_summary_tr').fadeIn();
                }
            });
        } else {
            jQuery('.portfolio_border_radius_tr').fadeOut();
            jQuery('.portfolio_summary_tr').fadeIn();
        }
    });


    jQuery(document).on('change', '#portfolio_image_effect', function () {
        if (jQuery('#portfolio_content_position').val() == 'overlay_image') {
            var $excerpt_null_array = ['skate_top', 'skate_bottom', 'shift_top', 'shift_bottom', 'door_slide', 'reducer', 'retard_top', 'retard_bottom'];
            var $image_effect = jQuery('#portfolio_image_effect').val();
            $image_effect = jQuery(this).val();
            if (jQuery.inArray($image_effect, $excerpt_null_array) !== -1) {
                if (jQuery('#portfolio_image_effect').is(':visible')) {
                    jQuery('.portfolio_summary_tr').fadeOut();
                }
            } else {
                if (jQuery('#portfolio_image_effect').is(':hidden')) {
                    jQuery('.portfolio_summary_tr').fadeIn();
                }
            }
            jQuery('.portfolio_border_radius_tr').fadeIn();
        } else {
            jQuery('.portfolio_border_radius_tr').fadeOut();
            if (jQuery('#portfolio_image_effect').is(':hidden')) {
                jQuery('.portfolio_summary_tr').fadeIn();
            }
        }
    });


    /* Portfolio Pagination Type Change */
    jQuery('.portfolio_pagination_tr').hide();

    if (jQuery('#portfolio_enable_pagination').prop('checked') == true) {
        jQuery('.portfolio_pagination_tr').show();
    } else {
        jQuery('.portfolio_pagination_tr').hide();
    }

    jQuery('#portfolio_enable_pagination').on('click', function () {
        if (jQuery(this).prop('checked') == true) {
            jQuery('.portfolio_pagination_tr').fadeIn();
        } else {
            jQuery('.portfolio_pagination_tr').fadeOut();
        }
    });

    if (jQuery('#portfolio_enable_overlay').prop('checked') == true) {
        jQuery('.portfolio_overlay_tr').show();
    } else {
        jQuery('.portfolio_overlay_tr').hide();
    }
    jQuery('#portfolio_enable_overlay').on('click', function () {
        if (jQuery(this).prop('checked') == true) {
            jQuery('.portfolio_overlay_tr').fadeIn();

        } else {
            jQuery('.portfolio_overlay_tr').fadeOut();
        }
    });
    jQuery('button.notice-dismiss').on('click', function () {
        jQuery('#notice-2').remove();
    });
    jQuery('.show_portfolio_save').click(function (e) {
        e.preventDefault();
        jQuery('#addPortfolioDesigner').trigger('click');
    });

    jQuery(document).on('click', ".paging-navigation .pagination a.page-numbers, .pdl_single_wrapp a.port_fancybox", function (e) {
        e.preventDefault();
    });

    /* Upload Image Section */
    jQuery(document).on('click', '.pdl-upload_image_button', function (event) {
        event.preventDefault();
        var frame;
        var $el = jQuery(this);
        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create the media frame.
        frame = wp.media({
            // Set the title of the modal.
            title: $el.data('choose'),
            // Customize the submit button.
            button: {
                // Set the text of the button.
                text: $el.data('update'),
                // Tell the button not to close the modal, since we're
                // going to refresh the page when the image is selected.
                close: false,
            },
            multiple: false,
            library: {
                type: 'image'
            },
        });

        // When an image is selected, run a callback.
        frame.on('select', function () {
            // Grab the selected attachment.
            var attachment = frame.state().get('selection').first();
            frame.close(attachment);
            if (attachment.attributes.type == 'image') {
                jQuery('span.portfolio_default_image_holder').empty().hide().append('<img src="' + attachment.attributes.url + '">').slideDown('fast');
                jQuery('#portfolio_default_image_id').val(attachment.attributes.id);
                jQuery('#portfolio_default_image_src').val(attachment.attributes.url);
                $el.removeClass('pdl-upload_image_button');
                $el.addClass('pdl-remove_image_button');
                $el.val('');
                $el.val('Remove Image');
            }
        });

        // Finally, open the modal.
        frame.open();
    });
    //Remove uploaded image
    jQuery(document).on('click', '.pdl-remove_image_button', function (event) {
        event.preventDefault();
        var $el = jQuery(this);
        jQuery('.portfolio_default_image_holder > img').slideDown().remove();
        jQuery('#portfolio_default_image_id').val('');
        jQuery('#portfolio_default_image_src').val('');
        $el.addClass('pdl-upload_image_button');
        $el.removeClass('pdl-remove_image_button');
        $el.val('');
        $el.val('Upload Image');
    });

    jQuery(".select-cover select").chosen({
        no_results_text: portdesigner_admin_translations.no_found
    });


    /*Reset Settings*/
    jQuery('#pdl-btn-reset-submit').on('click', function () {
        if (confirm(portdesigner_admin_translations.reset_data)) {
            pdl_default_value();
            jQuery('#addPortfolioDesigner').trigger('click');
        } else {
            return false;
        }

    });

    /*Add popup for pro version*/
    jQuery('.pro-feature, .pro-feature ul, .pro-feature input, .pro-feature a, .pro-feature .bdp-upload_image_button, #portfolio-show-preview').on('click', function (e) {
        e.preventDefault();

        jQuery("#pdl-advertisement-popup").dialog({
            resizable: false,
            draggable: false,
            modal: true,
            height: "auto",
            width: 'auto',
            maxWidth: '100%',
            dialogClass: 'pdl-advertisement-ui-dialog',
            buttons: [
                {
                    text: 'x',
                    "class": 'pdl-btn pdl-btn-gray',
                    click: function () {
                        jQuery(this).dialog("close");
                    }
                }
            ],
            open: function (event, ui) {
                jQuery(this).parent().children('.ui-dialog-titlebar').hide();
            },
            hide: {
                effect: "fadeOut",
                duration: 500
            },
            close: function (event, ui) {
                jQuery('#portfolio-template-search').val('');
                jQuery("#pdl-advertisement-popup").dialog('close');
            },
        });
    });

    get_custom_post();
});

function pdl_click_disable() {
    jQuery('.clickDisable').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
    });
}

function pdl_get_terms() {
    var portfolio_taxonomy = jQuery('#portfolio_taxonomy').val();
    if (portfolio_taxonomy != '' && portfolio_taxonomy != undefined) {
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'get_portfolio_terms',
                posttaxonomy: portfolio_taxonomy,
            },
            success: function (response) {
                if (response == 0) {
                    jQuery('.portfolio_categories_tr').hide();
                } else {
                    jQuery('.portfolio_categories_tr').show();
                }
            }
        });
    }
    if (jQuery('#portfolio_taxonomy').length == 0) {
        jQuery('.portfolio_categories_tr').hide();
    }
}

function pdl_default_value() {
 //   jQuery('#portfolio_layout_post').val('sol_portfolio');
  //  jQuery('#portfolio_taxonomy').val('portfolio-category');
    jQuery('#portfolio_number_post').val('10');
    jQuery('#portfolio_order').val('ASC');
    jQuery('#portfolio_order_by').val('date');
    jQuery('#portfolio_layout_type').val('masonary');
    jQuery('#portfolio_column_space').val('5');
    jQuery('#portfolio_row_space').val('5');
    jQuery('#portfolio_border_radius').val('0');    
    jQuery('#portfolio_thumb_size').val('full');
    jQuery('#portfolio_enable_overlay').prop('checked', true);
    jQuery('#portfolio_overlay_tr').show();
    jQuery('#portfolio_content_position').val('overlay_image');
    jQuery('#portfolio_image_effect').val('effect_1');
    jQuery('#portfolio_summary').val('0');
    jQuery('#portfolio_enable_popup_link').prop('checked', true);
    jQuery('#portfolio_image_link').val('new_tab');
    jQuery('#portfolio_enable_filter').prop('checked', false);
    jQuery('.filter_data_tr').hide;
    jQuery('#portfolio_enable_pagination').prop('checked', true);
    jQuery('#portfolio_enable_social_share_settings').prop('checked', true);
    jQuery('#portfolio_social_icon_alignment').val('left');
    jQuery('#portfolio_social_icon_style_1').prop('checked', true);
    jQuery('#portfolio_social_icon_style_0').prop('checked', false);
    jQuery('#portfolio_social_icon_style_1').prop('checked', false);
    jQuery('#portfolio_social_icon_style_0').prop('checked', true);
    jQuery('#portfolio_facebook_link_1').prop('checked', true);
    jQuery('#portfolio_facebook_link_0').prop('checked', false);
    jQuery('#portfolio_twitter_link_1').prop('checked', true);
    jQuery('#portfolio_twitter_link_0').prop('checked', false);
    jQuery('#portfolio_google_link_1').prop('checked', true);
    jQuery('#portfolio_google_link_0').prop('checked', false);
    jQuery('#portfolio_linkedin_link_1').prop('checked', false);
    jQuery('#portfolio_linkedin_link_0').prop('checked', true);
    jQuery('#portfolio_pinterest_link_1').prop('checked', false);
    jQuery('#portfolio_pinterest_link_0').prop('checked', true);
    jQuery('#portfolio_title_font_color').iris('color', '');
    jQuery('#portfolio_title_font_size').val('0');
    jQuery('#portfolio_content_font_color').iris('color', '');
    jQuery('#portfolio_content_font_size').val('0');
    jQuery('#portfolio_meta_font_color').iris('color', '#222222');
    jQuery('#portfolio_meta_font_size').val('0');
    jQuery('#portfolio_button_font_color').iris('color', '');
    jQuery('#portfolio_button_font_size').val('0');
    jQuery('#content_background_color').iris('color', '');
    jQuery('.select-cover select').trigger("chosen:updated");
}

if (jQuery('.set_custom_images').length > 0) {
    if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
        jQuery('.wrap').on('click', '.set_custom_images', function (e) {
            e.preventDefault();
            var button = jQuery(this);
            var id = button.prev();
            wp.media.editor.send.attachment = function (props, attachment) {
                console.log();
                id.val(attachment.sizes.full.url);

            };
            wp.media.editor.open(button);
            return false;
        });
    }
}

if (jQuery('input[name="portfolio_single_facebook_link"]:checked').length == 0) {
    jQuery("#portfolio_single_facebook_link_1").prop("checked", true)
}
if (jQuery('input[name="portfolio_single_twitter_link"]:checked').length == 0) {
    jQuery("#portfolio_single_twitter_link_1").prop("checked", true)
}
if (jQuery('input[name="portfolio_single_google_link"]:checked').length == 0) {
    jQuery("#portfolio_single_google_link_1").prop("checked", true)
}
if (jQuery('input[name="portfolio_single_linkedin_link"]:checked').length == 0) {
    jQuery("#portfolio_single_linkedin_link_1").prop("checked", true)
}
if (jQuery('input[name="portfolio_single_pinterest_link"]:checked').length == 0) {
    jQuery("#portfolio_single_pinterest_link_1").prop("checked", true)
}
if (jQuery('input[name="portfolio_single_share_via_mail"]:checked').length == 0) {
    jQuery("#portfolio_single_share_via_mail_1").prop("checked", true)
}
if (jQuery('input[name="portfolio_single_whatsapp_link"]:checked').length == 0) {
    jQuery("#portfolio_single_whatsapp_link_1").prop("checked", true)
}

if (jQuery('input[name="custom_posttype_icon"]:checked').length == 0) {
    jQuery("#wordpress_icon").prop("checked", true)
}

if (jQuery('#wordpress_icon').is(':checked')) {
    jQuery('.wordpress_icon_value').removeClass("hide");
    jQuery('.process_custom_images, .set_custom_images,.custom_icon_message').addClass("hide");
}
if (jQuery('#custom_icon').is(':checked')) {
    jQuery('.process_custom_images, .set_custom_images,.custom_icon_message').removeClass("hide");
    jQuery('.wordpress_icon_value,.wordpress_icon_message').addClass("hide");
}
jQuery('#wordpress_icon').click(function () {
    jQuery('.wordpress_icon_value,.wordpress_icon_message').removeClass("hide");
    jQuery('.process_custom_images, .set_custom_images,.custom_icon_message').addClass("hide");
});
jQuery('#custom_icon').click(function () {
    jQuery('.process_custom_images, .set_custom_images,.custom_icon_message').removeClass("hide");
    jQuery('.wordpress_icon_value,.wordpress_icon_message').addClass("hide");
});

// share social add portfolio
jQuery('#portfolio_enable_social_share_settings').click(function () {
    if (jQuery(this).attr('checked') == 'checked') {
        jQuery(this).closest('#pdlsocial').find('tr').removeClass('hide');
    } else {
        jQuery(this).closest('#pdlsocial').find('tr').addClass('hide');
        jQuery('.keep-it-on').removeClass("hide");
    }
});

if (jQuery('#portfolio_enable_social_share_settings').attr('checked') == 'checked') {
    jQuery('#portfolio_enable_social_share_settings').closest('#pdlsocial').find('tr').removeClass('hide');
} else {
    jQuery('#portfolio_enable_social_share_settings').closest('#pdlsocial').find('tr').addClass('hide');
    jQuery('.keep-it-on').removeClass("hide");
}

if (jQuery('#portfolio_facebook_link_1').is(':checked')) {
    jQuery('.portfolio_facebook_link_with_count').removeClass("hide");
} else {
    jQuery('.portfolio_facebook_link_with_count').addClass("hide");
}
jQuery('input[name="portfolio_facebook_link"]').click(function () {
    if (jQuery(this).val() == '1') {
        jQuery('.portfolio_facebook_link_with_count').removeClass("hide");
    } else {
        jQuery('.portfolio_facebook_link_with_count').addClass("hide");
    }
});


if (jQuery('#portfolio_google_link_1').is(':checked')) {
    jQuery('.portfolio_google_link_with_count').removeClass("hide");
} else {
    jQuery('.portfolio_google_link_with_count').addClass("hide");
}
jQuery('input[name="portfolio_google_link"]').click(function () {
    if (jQuery(this).val() == '1') {
        jQuery('.portfolio_google_link_with_count').removeClass("hide");
    } else {
        jQuery('.portfolio_google_link_with_count').addClass("hide");
    }
});

if (jQuery('#portfolio_linkedin_link_1').is(':checked')) {
    jQuery('.portfolio_linkedin_link_with_count').removeClass("hide");
} else {
    jQuery('.portfolio_linkedin_link_with_count').addClass("hide");
}
jQuery('input[name="portfolio_linkedin_link"]').click(function () {
    if (jQuery(this).val() == '1') {
        jQuery('.portfolio_linkedin_link_with_count').removeClass("hide");
    } else {
        jQuery('.portfolio_linkedin_link_with_count').addClass("hide");
    }
});


if (jQuery('#portfolio_pinterest_link_1').is(':checked')) {
    jQuery('.portfolio_pinterest_link_with_count').removeClass("hide");
} else {
    jQuery('.portfolio_pinterest_link_with_count').addClass("hide");
}
jQuery('input[name="portfolio_pinterest_link"]').click(function () {
    if (jQuery(this).val() == '1') {
        jQuery('.portfolio_pinterest_link_with_count').removeClass("hide");
    } else {
        jQuery('.portfolio_pinterest_link_with_count').addClass("hide");
    }
});


if (jQuery('#portfolio_single_facebook_link_1').is(':checked')) {
    jQuery('.facebook_link_with_count').removeClass("hide");
} else {
    jQuery('.facebook_link_with_count').addClass("hide");
}
jQuery('input[name="portfolio_single_facebook_link"]').click(function () {
    if (jQuery(this).val() == '1') {
        jQuery('.facebook_link_with_count').removeClass("hide");
    } else {
        jQuery('.facebook_link_with_count').addClass("hide");
    }
});


if (jQuery('#portfolio_single_google_link_1').is(':checked')) {
    jQuery('.google_link_with_count').removeClass("hide");
} else {
    jQuery('.google_link_with_count').addClass("hide");
}
jQuery('input[name="portfolio_single_google_link"]').click(function () {
    if (jQuery(this).val() == '1') {
        jQuery('.google_link_with_count').removeClass("hide");
    } else {
        jQuery('.google_link_with_count').addClass("hide");
    }
});

if (jQuery('#portfolio_single_linkedin_link_1').is(':checked')) {
    jQuery('.linkedin_link_with_count').removeClass("hide");
} else {
    jQuery('.linkedin_link_with_count').addClass("hide");
}
jQuery('input[name="portfolio_single_linkedin_link"]').click(function () {
    if (jQuery(this).val() == '1') {
        jQuery('.linkedin_link_with_count').removeClass("hide");
    } else {
        jQuery('.linkedin_link_with_count').addClass("hide");
    }
});


if (jQuery('#portfolio_single_pinterest_link_1').is(':checked')) {
    jQuery('.pinterest_link_with_count').removeClass("hide");
} else {
    jQuery('.pinterest_link_with_count').addClass("hide");
}
jQuery('input[name="portfolio_single_pinterest_link"]').click(function () {
    if (jQuery(this).val() == '1') {
        jQuery('.pinterest_link_with_count').removeClass("hide");
    } else {
        jQuery('.pinterest_link_with_count').addClass("hide");
    }
});

jQuery('.portfolio_button_radius_tr').addClass("hide");
if (jQuery('#portfolio_button_type_1').attr('checked') == 'checked') {
    jQuery('.portfolio_button_radius_tr').removeClass("hide");
}
jQuery('#portfolio_button_type_0').click(function () {
    jQuery('.portfolio_button_radius_tr').addClass("hide");
});
jQuery('#portfolio_button_type_1').click(function () {
    jQuery('.portfolio_button_radius_tr').removeClass("hide");
});



(function ($) {
    var $, win;

    $ = window.jQuery;

    win = $(window);

    $.fn.stick_in_parent = function (opts) {
        var doc, elm, enable_bottoming, fn, i, inner_scrolling, len, manual_spacer, offset_top, outer_width, parent_selector, recalc_every, sticky_class;
        if (opts == null) {
            opts = {};
        }
        sticky_class = opts.sticky_class, inner_scrolling = opts.inner_scrolling, recalc_every = opts.recalc_every, parent_selector = opts.parent, offset_top = opts.offset_top, manual_spacer = opts.spacer, enable_bottoming = opts.bottoming;
        if (offset_top == null) {
            offset_top = 0;
        }
        if (parent_selector == null) {
            parent_selector = void 0;
        }
        if (inner_scrolling == null) {
            inner_scrolling = true;
        }
        if (sticky_class == null) {
            sticky_class = "is_stuck";
        }
        doc = $(document);
        if (enable_bottoming == null) {
            enable_bottoming = true;
        }
        outer_width = function (el) {
            var _el, computed, w;
            if (window.getComputedStyle) {
                _el = el[0];
                computed = window.getComputedStyle(el[0]);
                w = parseFloat(computed.getPropertyValue("width")) + parseFloat(computed.getPropertyValue("margin-left")) + parseFloat(computed.getPropertyValue("margin-right"));
                if (computed.getPropertyValue("box-sizing") !== "border-box") {
                    w += parseFloat(computed.getPropertyValue("border-left-width")) + parseFloat(computed.getPropertyValue("border-right-width")) + parseFloat(computed.getPropertyValue("padding-left")) + parseFloat(computed.getPropertyValue("padding-right"));
                }
                return w;
            } else {
                return el.outerWidth(true);
            }
        };
        fn = function (elm, padding_bottom, parent_top, parent_height, top, height, el_float, detached) {
            var bottomed, detach, fixed, last_pos, last_scroll_height, offset, parent, recalc, recalc_and_tick, recalc_counter, spacer, tick;
            if (elm.data("sticky_kit")) {
                return;
            }
            elm.data("sticky_kit", true);
            last_scroll_height = doc.height();
            parent = elm.parent();
            if (parent_selector != null) {
                parent = parent.closest(parent_selector);
            }
            if (!parent.length) {
                throw "failed to find stick parent";
            }
            fixed = false;
            bottomed = false;
            spacer = manual_spacer != null ? manual_spacer && elm.closest(manual_spacer) : $("<div />");
            if (spacer) {
                spacer.css('position', elm.css('position'));
            }
            recalc = function () {
                var border_top, padding_top, restore;
                if (detached) {
                    return;
                }
                last_scroll_height = doc.height();
                border_top = parseInt(parent.css("border-top-width"), 10);
                padding_top = parseInt(parent.css("padding-top"), 10);
                padding_bottom = parseInt(parent.css("padding-bottom"), 10);
                parent_top = parent.offset().top + border_top + padding_top;
                parent_height = parent.height();
                if (fixed) {
                    fixed = false;
                    bottomed = false;
                    if (manual_spacer == null) {
                        elm.insertAfter(spacer);
                        spacer.detach();
                    }
                    elm.css({
                        position: "",
                        top: "",
                        width: "",
                        bottom: ""
                    }).removeClass(sticky_class);
                    restore = true;
                }
                top = elm.offset().top - (parseInt(elm.css("margin-top"), 10) || 0) - offset_top;
                height = elm.outerHeight(true);
                el_float = elm.css("float");
                if (spacer) {
                    spacer.css({
                        width: outer_width(elm),
                        height: height,
                        display: elm.css("display"),
                        "vertical-align": elm.css("vertical-align"),
                        "float": el_float
                    });
                }
                if (restore) {
                    return tick();
                }
            };
            recalc();
            if (height === parent_height) {
                return;
            }
            last_pos = void 0;
            offset = offset_top;
            recalc_counter = recalc_every;
            tick = function () {
                var css, delta, recalced, scroll, will_bottom, win_height;
                if (detached) {
                    return;
                }
                recalced = false;
                if (recalc_counter != null) {
                    recalc_counter -= 1;
                    if (recalc_counter <= 0) {
                        recalc_counter = recalc_every;
                        recalc();
                        recalced = true;
                    }
                }
                if (!recalced && doc.height() !== last_scroll_height) {
                    recalc();
                    recalced = true;
                }
                scroll = win.scrollTop();
                if (last_pos != null) {
                    delta = scroll - last_pos;
                }
                last_pos = scroll;
                if (fixed) {
                    if (enable_bottoming) {
                        will_bottom = scroll + height + offset > parent_height + parent_top;
                        if (bottomed && !will_bottom) {
                            bottomed = false;
                            offset = offset;
                            elm.css({
                                position: "fixed",
                                bottom: "",
                                top: offset
                            }).trigger("sticky_kit:unbottom");
                        }
                    }
                    if (scroll < top) {
                        fixed = false;
                        offset = offset_top;
                        if (manual_spacer == null) {
                            if (el_float === "left" || el_float === "right") {
                                elm.insertAfter(spacer);
                            }
                            spacer.detach();
                        }
                        css = {
                            position: "",
                            width: "",
                            top: ""
                        };
                        elm.css(css).removeClass(sticky_class).trigger("sticky_kit:unstick");
                    }
                    if (inner_scrolling) {
                        win_height = win.height();
                        if (height + offset_top > win_height) {
                            if (!bottomed) {
                                offset -= delta;
                                offset = Math.max(win_height - height, offset);
                                offset = Math.min(offset_top, offset);
                                offset = offset;
                                if (fixed) {
                                    elm.css({
                                        top: offset + "px"
                                    });
                                }
                            }
                        }
                    }
                } else {
                    if (scroll > top) {
                        fixed = true;
                        offset = 32;
                        css = {
                            position: "fixed",
                            top: offset
                        };
                        css.width = elm.css("box-sizing") === "border-box" ? elm.outerWidth() + "px" : elm.width() + "px";
                        elm.css(css).addClass(sticky_class);
                        if (manual_spacer == null) {
                            elm.after(spacer);
                            if (el_float === "left" || el_float === "right") {
                                spacer.append(elm);
                            }
                        }
                        elm.trigger("sticky_kit:stick");
                    }
                }
                if (fixed && enable_bottoming) {
                    if (will_bottom == null) {
                        will_bottom = scroll + height + offset > parent_height + parent_top;
                    }
                    if (!bottomed && will_bottom) {
                        bottomed = true;
                        if (parent.css("position") === "static") {
                            parent.css({
                                position: "relative"
                            });
                        }
                        return elm.css({
                            position: "absolute",
                            bottom: padding_bottom,
                            top: "auto"
                        }).trigger("sticky_kit:bottom");
                    }
                }
            };
            recalc_and_tick = function () {
                recalc();
                return tick();
            };
            detach = function () {
                detached = true;
                win.off("touchmove", tick);
                win.off("scroll", tick);
                win.off("resize", recalc_and_tick);
                $(document.body).off("sticky_kit:recalc", recalc_and_tick);
                elm.off("sticky_kit:detach", detach);
                elm.removeData("sticky_kit");
                elm.css({
                    position: "",
                    bottom: "",
                    top: "",
                    width: ""
                });
                parent.position("position", "");
                if (fixed) {
                    if (manual_spacer == null) {
                        if (el_float === "left" || el_float === "right") {
                            elm.insertAfter(spacer);
                        }
                        spacer.remove();
                    }
                    return elm.removeClass(sticky_class);
                }
            };
            win.on("touchmove", tick);
            win.on("scroll", tick);
            win.on("resize", recalc_and_tick);
            $(document.body).on("sticky_kit:recalc", recalc_and_tick);
            elm.on("sticky_kit:detach", detach);
            return setTimeout(tick, 0);
        };
        for (i = 0, len = this.length; i < len; i++) {
            elm = this[i];
            fn($(elm));
        }
        return this;
    };

}).call(this);

jQuery(".pdl-menu-setting").stick_in_parent();

function pdl_show_hide_permission() {
    jQuery('.pdl_permission_cover').slideToggle();
}

function pdl_submit_optin(options) {
    result = {};
    result.action = 'pdl_submit_optin';
    result.email = jQuery('#pdl_admin_email').val();
    result.type = options;

    if (options == 'submit') {
        if (jQuery('input#pdl_agree_gdpr').is(':checked')) {
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: result,
                error: function () { },
                success: function () {
                    window.location.href = "admin.php?page=portfolio_lite_settings";
                },
                complete: function () {
                    window.location.href = "admin.php?page=portfolio_lite_settings";
                }
            });
        }
        else {
            jQuery('.pdl_agree_gdpr_lbl').css('color', '#ff0000');
        }
    }
    else if (options == 'deactivate') {
        if (jQuery('input#pdl_agree_gdpr_deactivate').is(':checked')) {
            var pdl_plugin_admin = jQuery('.documentation_pdl_plugin').closest('div').find('.deactivate').find('a');
            result.selected_option_de = jQuery('input[name=sol_deactivation_reasons_pdl]:checked', '#frmDeactivationpdl').val();
            result.selected_option_de_id = jQuery('input[name=sol_deactivation_reasons_pdl]:checked', '#frmDeactivationpdl').attr("id");
            result.selected_option_de_text = jQuery("label[for='" + result.selected_option_de_id + "']").text();
            result.selected_option_de_other = jQuery('.sol_deactivation_reason_other_pdl').val();
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: result,
                error: function () { },
                success: function () {
                    window.location.href = pdl_plugin_admin.attr('href');
                },
                complete: function () {
                    window.location.href = pdl_plugin_admin.attr('href');
                }
            });
        }
        else {
            jQuery('.pdl_agree_gdpr_lbl').css('color', '#ff0000');
        }
    }
    else {
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: result,
            error: function () { },
            success: function () {
                window.location.href = "admin.php?page=portfolio_lite_settings";
            },
            complete: function () {
                window.location.href = "admin.php?page=portfolio_lite_settings";
            }
        });
    }
}

function get_custom_post() {
    var portfolio_post = jQuery('#portfolio_layout_post').val();
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'get_portfolio_custom_post',
            posttype: portfolio_post,
        },
        success: function (response) {
            if(response == 1){
                jQuery('.portfolio_popup_project_tr').show();
            } else{
                jQuery('.portfolio_popup_project_tr').hide();
            }
        }
    });
    
}