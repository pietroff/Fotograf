var TG = function( $ ) {
  var _loading = null;

  return {
    choose_images: function( title_field, caption_field, callback ) {
      tgm_media_frame = wp.media.frames.tgm_media_frame = wp.media( {
        multiple: true,
        library: {
          type: 'image'
        },
        title: 'Add image(s)',
        button: {
          text: 'Add image(s)'
        },
        states: [
          new wp.media.controller.Library( {
            library: wp.media.query( {
              type: 'image'
            } ),
            multiple: true,
            priority: 20,
            filterable: 'all'
          } )
        ]
      } );

      tgm_media_frame.on( 'select', function() {
        var selection = tgm_media_frame.state().get( 'selection' );
        var images = [];

        var errors = 0;
        selection.map( function( attachment ) {
          attachment = attachment.toJSON();

          if ( ! attachment.sizes ) {
            errors ++;
            return;
          }

          var obj = {
            imageId: attachment.id
          };

          if ( title_field != 'none' )
            obj.title = attachment[ title_field ];
          if ( caption_field != 'none' )
            obj.description = attachment[ caption_field ];

          obj.imagePath = attachment.url;

          if ( attachment.sizes.thumbnail )
            obj.thumbnail = attachment.sizes.thumbnail.url;

          if ( attachment.sizes.full )
            obj.altImagePath = attachment.sizes.full.url;

          images.push( obj );
        } );

        if ( errors ) {
          alert( errors + ' images could not be added because the selected size is not available' );
        }

        callback( images );
      } );

      tgm_media_frame.open();
    },
    show_loading: function() {
      $( '#spinner' ).addClass( 'shown' );
    },
    hide_loading: function() {
      $( '#spinner' ).removeClass( 'shown' );
    },
    delete_image: function( id ) {
      TG.show_loading();
      $.post( ajaxurl, {
        action: 'modula_delete_image',
        Modula: $( '#Modula' ).val(),
        id: id
      }, function() {
        TG.load_images();
      } );
    },
    load_images: function() {
      if ( ! _loading )
        TG.show_loading();

      $.post( ajaxurl, {
        action: 'modula_list_images',
        Modula: $( '#Modula' ).val(),
        gid: $( '#gallery-id' ).val()
      }, function( html ) {
        $( '#image-list' ).empty().append( html ).sortable( {
          update: function() {
            TG.show_loading();
            var ids = [];
            $( '#image-list .item' ).each( function() {
              ids.push( $( this ).data( 'id' ) );
            } );
            var data = {
              action: 'modula_sort_images',
              Modula: $( '#Modula' ).val(),
              ids: ids.join( ',' )
            };
            $.post( ajaxurl, data, function() {
              TG.hide_loading();
            } );
          }
        } );

        $( '#image-list .remove' ).click( function( e ) {
          e.preventDefault();
          e.stopPropagation();

          var $item = $( this ).parents( '.item:first' );
          var id = $item.data( 'id' );

          var data = {
            action: 'modula_delete_image',
            Modula: $( '#Modula' ).val(),
            id: id
          };

          TG.show_loading();
          $.post( ajaxurl, data, function() {
            $item.remove();
            TG.hide_loading();
          } );
        } );

        $( '#image-list .checkbox' ).click( function() {
          $( this ).toggleClass( 'checked' );
          $( this ).parents( '.item:first' ).toggleClass( 'selected' );
        } );

        TG.hide_loading();
      } );
    },
    edit_image: function( form ) {
      var data = {};
      form.find( 'input[type=text], input:checked, input, textarea, input[type=hidden]' ).each( function() {
        data[ $( this ).attr( 'name' ) ] = $( this ).val();
      } );
      data.action = 'modula_save_image';
      data.type = 'edit';
      data.Modula = $( '#Modula' ).val();
      TG.show_loading();
      $.ajax( {
        url: ajaxurl,
        data: data,
        dataType: 'json',
        type: 'post',
        error: function( a, b, c ) {
          TG.hide_loading();
        },
        success: function( r ) {
          if ( r.success ) {
            TG.load_images();
          } else {
            TG.hide_loading();
          }
        }
      } );
    },
    add_image: function() {

      var data = {};
      $( '#add_image_form input[type=text], #add_image_form input:checked, #add_image_form textarea, #add_image_form input[type=hidden]' ).each( function() {
        data[ $( this ).attr( 'name' ) ] = $( this ).val();
      } );

      data.action = 'modula_save_image';
      data.type = $( this ).data( 'type' );
      if ( data.img_id == '' ) {
        var p = $( '<div title=\'Attention\'>Select an image to add</div>' ).dialog( {
          modal: true,
          buttons: {
            Close: function() {
              p.dialog( 'destroy' );
            }
          }
        } );
        return false;
      }

      TG.show_loading();
      $.ajax( {
        url: ajaxurl,
        data: data,
        dataType: 'json',
        type: 'post',
        error: function( a, b, c ) {
          TG.hide_loading();
        },
        success: function( r ) {
          if ( r.success ) {
            TG.load_images();
            $( '#add_image_form .img img' ).remove();
            $( '[name=img_id],[name=img_url],[name=url],[name=image_caption]' ).val( '' );
          }
        }
      } );
    },
    update_filters: function() {
      var ff = [];
      $( '.filters .f' ).each( function() {
        var val = $.trim( $( this ).val() );
        if ( val.length > 0 && $.inArray( val, ff ) < 0 ) {
          ff.push( val );
        }
      } );
      $( '.filters [name=tg_filter]' ).val( ff.join( '|' ) );
    },
    add_filter: function( value ) {
      var row = $( '<p style=\'display:none\'><a href=\'#\' class=\'btn-floating waves-effect red\'><i class=\'fa fa-times\'></i></a> <input class=\'f\' type=\'text\' /></p>' );
      if ( value )
        row.find( '.f' ).val( value );

      $( '.filters .text' ).append( row );
      row.slideDown();
      row.find( 'a' ).click( function( e ) {
        e.preventDefault();
        row.slideUp( function() {
          $( this ).remove();
        } );
        TG.update_filters();
      } );
    },
    init_gallery: function() {
      var ff = $( '.filters [name=tg_filter]' ).val().split( '|' );
      if ( ff.length == 0 || ff[ 0 ] == '' ) {
        TG.add_filter();
      } else {
        for ( var i = 0; i < ff.length; i ++ ) {
          if ( ff[ i ].length > 0 )
            TG.add_filter( ff[ i ] );
        }
      }

    },
    save_gallery: function() {
      var data = {};
      data.action = 'modula_save_gallery';

      TG.update_filters();

      $( '.form-fields' ).find( 'input[type=text], select, input[type=range], input:checked, input[type=hidden], textarea' ).each( function() {
        data[ $( this ).attr( 'name' ) ] = $( this ).val();
      } );

      if ( parseInt( data.gridCellSize ) < 2 )
        data.gridCellSize = 2;

      if ( data.galleryName == '' ) {
        var p = $( '<div title=\'Attention\'>Insert a name for the gallery</div>' ).dialog( {
          modal: true,
          buttons: {
            Close: function() {
              p.dialog( 'destroy' );
            }
          }
        } );
        return false;
      }

      TG.show_loading();

      $.ajax( {
        url: ajaxurl,
        data: data,
        dataType: 'json',
        type: 'post',
        error: function( a, b, c ) {
          TG.hide_loading();
        },
        success: function( r ) {
          if ( data.ftg_gallery_edit ) {
            Materialize.toast( 'Gallery Saved', 2000 );
            TG.hide_loading();
          }
          else
            location.href = '?page=modula-pro-edit';
        }
      } );
    },
    bind: function() {

      $( '#image-list' ).on( 'click', '.item .filters li', function() {
        var currentFilter = $( this ).html().trim();

        $( '[data-type=image]' ).each( function() {
          $( this ).parent().parent().removeClass( 'selected' );
          var img = $( this );
          var filters = img.attr( 'data-filter' ).split( '|' );
          if ( $.inArray( currentFilter, filters ) >= 0 ) {
            img.parent().parent().addClass( 'selected' );
          }
        } );

      } );

      $( '.select-effect' ).on( 'change', function() {
        var currentEffect = $( this ).val();
        $( '.field .text .preview .panel' ).hide();

        if ( currentEffect != 'none' )
          $( '.field .text .preview .panel-' + currentEffect ).show();

        $( '.field .text .preview .panel' ).hide();
        $( '.field .text .preview .panel-' + currentEffect ).show();
      } ).change();

      $( '.bullet-menu li a' ).click( function( e ) {
        e.preventDefault();
        var target = $( this ).attr( 'rel' ).toLowerCase();
        $( '#' + target + ' .collapsible-header' ).click();
        setTimeout( function() {
          $( 'html, body' ).animate( {
            scrollTop: $( '#' + target ).offset().top - 28
          }, 1000 );
        }, 500 );
      } );

      $( '.import-export a' ).click( function() {

        if ( $( this ).attr( 'id' ) == 'import' ) {
          $( '#import-modal' ).modal();
          $( '#import-modal' ).modal( 'open' );
        }
        else if ( $( this ).attr( 'id' ) == 'export' ) {
          var data = { action: 'modula_get_config', id: $( '#gallery-id' ).val(), Modula: $( '#Modula' ).val() };

          $.ajax( {
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function( r ) {
              $( '#export-modal .modal-content textarea' ).val( '' );
              $( '#export-modal .modal-content textarea' ).val( r );

              $( '#export-modal' ).modal();
              $( '#export-modal' ).modal( 'open' );
            }

          } );
        }
      } );

      $( '#import-modal .modal-footer #save' ).click( function() {
        var config = $( '#import-modal textarea' ).val();

        var data = { action: 'modula_update_config', config: config, id: $( '#gallery-id' ).val(), Modula: $( '#Modula' ).val() };
        $.ajax( {
          type: 'POST',
          url: ajaxurl,
          data: data,
          success: function( r ) {
            alert( 'Gallery configuration has been updated' );
          }
        } );
      } );

      $( '.filter-list .filter-select-control li' ).click( function() {
        $( '.filter-list .filter-select-control li' ).removeClass( 'menu-activ' );
        $( this ).addClass( 'menu-activ' );

        var currentFilter = $( this ).html().trim();

        $( '[data-type=image]' ).each( function() {
          $( this ).parent().parent().removeClass( 'selected' );
          var img = $( this );
          var filters = img.attr( 'data-filter' ).split( '|' );

          if ( $.inArray( currentFilter, filters ) >= 0 ) {
            img.parent().parent().addClass( 'selected' );
          }
        } );

      } );

      $( '.collapsible-header' ).click( function() {
        var target = $( this ).parent().attr( 'id' );
        setTimeout( function() {
          $( 'html, body' ).animate( {
            scrollTop: $( '#' + target ).offset().top - 28
          }, 1000 );
        }, 500 );
      } );

      $( '.field .text .integer-only' ).keypress( function( e ) {
        var charCode = (e.which) ? e.which : e.keyCode;

        if ( charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57) )
          return false;

        return true;
      } );

      $( '#create-gallery' ).click( function() {
        var name = $( '#name' ).val();
        var description = $( '#description' ).val();

        if ( name == '' || description == '' ) return;

        var data = { action: 'create_gallery', name: name, description: description };

        jQuery.post( ajaxurl, data, function( id ) {
          $( '#name' ).val( '' );
          $( '#description' ).val( '' );

          $_success = $( '#success' );
          $_success.find( 'code' ).text( '[Modula id=\'' + id + '\']' );
          $_success.find( '.gallery-name' ).text( name );
          $_success.find( '.customize' ).attr( 'href', '?page=modula-pro-edit&galleryId=' + id );

          $_success.modal();
          $_success.modal( 'open' );
        } );

      } );

      $( '#add-submit' ).click( function( e ) {
        e.preventDefault();
        TG.add_image();
      } );
      $( '#add-gallery, #edit-gallery' ).click( function( e ) {
        e.preventDefault();
        TG.save_gallery();
      } );
      $( '.filters a' ).click( function( e ) {
        e.preventDefault();
        TG.add_filter();
      } );

      $( '#image-list' ).on( 'click', '.item .thumb', function() {
        $( this ).parents( '.item' ).toggleClass( 'selected' );
        $( this ).parents( '.item' ).find( '.checkbox' ).toggleClass( 'checked' );
      } );
      $( '#image-list' ).on( 'click', '.edit', function( e ) {
        e.preventDefault();

        $( '#wpbody-content' ).addClass( 'dark-content' );
        var $item = $( this ).parents( '.item' );

        var panel = $( '#image-panel-model' ).clone().attr( 'id', 'image-panel' );
        panel.css( {
          marginTop: $( window ).scrollTop() - (246 / 2)
        } );

        var $thumb = $( 'img', $item ).clone();

        $( '[name=target]', panel ).val( $( '[name=target]', $item ).val() );
        $( '#item-link', panel ).val( $( '[name=link]', $item ).val() );
        $( '.figure', panel ).append( $( 'img', $item ).clone() );
        $( '.figure', panel ).css( 'background-image', $( '.figure', $item ).css( 'background-image' ) );
        $( '.sizes', panel ).append( $( 'select', $item ).clone() );
        $( '#item-title', panel ).html( $( '#img-title', $item ).val() );
        $( '#item-description', panel ).val( $( 'pre', $item ).html() );
        $( '.copy', $item ).clone().appendTo( panel );

        var selFilters = $item.find( '[name=filters]' ).val().split( '|' );

        var filters = $( '[name=tg_filter]' ).val().split( '|' );
        for ( var i = 0; i < filters.length; i ++ ) {
          if ( $.trim( filters[ i ] ).length > 0 ) {
            //panel
            var ft = $( '<input type=\'checkbox\'>' );
            ft.val( $.trim( filters[ i ] ) );
            ft.attr( 'id', 'p' + filters[ i ] );
            $( '.filters', panel ).append( ft );
            ft.after( '<label class=\'\'  for=\'p' + filters[ i ] + '\'>' + $.trim( filters[ i ] ) + '</label>' );

            if ( $.inArray( filters[ i ], selFilters ) > - 1 ) {
              ft.attr( 'checked', 'checked' );
            }
          }
        }

        $( '.filters .checkbox', panel ).click( function() {
          $( this ).toggleClass( 'checked' );
        } );

        $( 'body' ).append( '<div class=\'overlay\' style=\'display:none\' />' );
        $( '.overlay' ).fadeIn();
        panel.appendTo( 'body' ).fadeIn();

        var link = $item.find( '[name=link]' ).val();

        $( '[name=halign]', panel ).val( $( '[name=halign]', $item ).val() );
        $( '[name=valign]', panel ).val( $( '[name=valign]', $item ).val() );

        $( '.buttons a', panel ).click( function( e ) {
          e.preventDefault();

          switch ( $( this ).data( 'action' ) ) {
            case 'save':
              $( '#wpbody-content' ).removeClass( 'dark-content' );
              var data = {
                action: 'modula_save_image',
                Modula: $( '#Modula' ).val()
              };
              $( 'input[type=text], input[type=hidden], input[type=radio]:checked, input[type=checkbox]:checked, textarea, select', panel ).each( function() {
                if ( $( this ).attr( 'name' ) )
                  data[ $( this ).attr( 'name' ) ] = $( this ).val();
              } );

              var savFilters = [];
              $( '.filters input:checked', panel ).each( function( i, o ) {
                savFilters.push( $( o ).attr( 'value' ) );
              } );
              data.filters = savFilters.join( '|' );

              $( '#image-panel .close' ).trigger( 'click' );
              TG.show_loading();
              $.ajax( {
                url: ajaxurl,
                data: data,
                dataType: 'json',
                type: 'post',
                error: function( a, b, c ) {
                  console.log( a, b, c );
                  TG.hide_loading();
                },
                success: function( r ) {
                  TG.hide_loading();
                  TG.load_images();
                }
              } );
              break;
            case 'cancel':
              $( '#wpbody-content' ).removeClass( 'dark-content' );
              $( '#image-panel .close' ).trigger( 'click' );
              break;
          }
        } );

        $( '#image-panel .close, .overlay' ).click( function( e ) {
          e.preventDefault();
          panel.fadeOut( function() {
            $( this ).remove();
          } );
          $( '.overlay' ).fadeOut( function() {
            $( this ).remove();
          } );
        } );
      } );

      $( '.jump' ).on( 'change', function() {
        var field = $( this ).val();
        $( 'html, body' ).animate( {
          scrollTop: $( '.row-' + field ).offset().top - 20
        }, 1000 );
        $( this ).get( 0 ).selectedIndex = 0;
      } );

      $( 'body' ).on( 'click', '[name=click_action]', function() {
        if ( $( this ).val() == 'url' ) {
          $( this ).siblings( '[name=url]' ).get( 0 ).disabled = false;
        } else {
          $( this ).siblings( '[name=url]' ).val( '' ).get( 0 ).disabled = true;
        }
      } );

      $( '.bulk a' ).click( function( e ) {
        e.preventDefault();

        var $bulk = $( '.bulk' );

        switch ( $( this ).data( 'action' ) ) {
          case 'select':
            $( '.filter-list .filter-select-control li' ).removeClass( 'menu-activ' );
            $( '#images .item' ).addClass( 'selected' );
            $( '#images .item .checkbox' ).addClass( 'checked' );
            break;
          case 'deselect':
            $( '.filter-list .filter-select-control li' ).removeClass( 'menu-activ' );
            $( '#images .item' ).removeClass( 'selected' );
            $( '#images .item .checkbox' ).removeClass( 'checked' );
            break;
          case 'toggle':
            $( '.filter-list .filter-select-control li' ).removeClass( 'menu-activ' );
            $( '#images .item' ).toggleClass( 'selected' );
            $( '#images .item .checkbox' ).toggleClass( 'checked' );
            break;
          case 'resize':
            var selected = [];
            $( '#images .item.selected' ).each( function( i, o ) {
              selected.push( $( o ).data( 'id' ) + '-' + $( o ).data( 'image-id' ) );
            } );
            if ( selected.length == 0 ) {
              alert( 'No images selected' );
            } else {
              $( '.panel', $bulk ).hide();
              $( '.panel strong', $bulk ).text( 'Select size' );
              $( '.panel .text', $bulk ).text( '' );
              var $sizes = $( '.current-image-size' ).clone( false );
              $sizes.removeClass( 'current-image-size' );
              $( '.panel .text', $bulk ).append( $sizes );

              $( '.cancel', $bulk ).unbind( 'click' ).click( function( e ) {
                e.preventDefault();
                $( '.panel', $bulk ).slideUp();
              } );

              $( '.proceed', $bulk ).unbind( 'click' ).click( function( e ) {
                e.preventDefault();
                $( '.panel', $bulk ).slideUp();

                var data = {
                  action: 'modula_resize_images',
                  Modula: $( '#Modula' ).val(),
                  size: $sizes.val(),
                  id: selected.join( ',' )
                };

                TG.show_loading();
                $.post( ajaxurl, data, function() {
                  TG.load_images();
                  TG.hide_loading();
                } );
              } );

              $( '.panel', $bulk ).slideDown();
            }
            break;
          case 'filter':
            var selected = [];
            $( '#images .item.selected' ).each( function( i, o ) {
              selected.push( $( o ).data( 'id' ) );
            } );
            if ( selected.length == 0 ) {
              alert( 'No images selected' );
            } else {
              $( '.panel', $bulk ).hide();
              $( '.panel strong', $bulk ).text( 'Select filters' );
              $( '.panel .text', $bulk ).text( '' );

              //no panel
              var filters = $( '[name=tg_filter]' ).val().split( '|' );
              for ( var i = 0; i < filters.length; i ++ ) {
                if ( $.trim( filters[ i ] ).length > 0 ) {
                  var ft = $( '<input id=\'' + filters[ i ] + '\' type=\'checkbox\' value=\'' + $.trim( filters[ i ] ) + '\' >' );
                  $( '.panel .text', $bulk ).append( ft );
                  ft.after( '<label class=\'label-text\' for=\'' + filters[ i ] + '\'>' + $.trim( filters[ i ] ) + '</label>' );
                }

              }

              $( '.panel .checkbox', $bulk ).click( function() {
                $( this ).toggleClass( 'checked' );
              } );

              $( '.cancel', $bulk ).unbind( 'click' ).click( function( e ) {
                e.preventDefault();
                $( '.panel', $bulk ).slideUp();
              } );

              $( '.proceed', $bulk ).unbind( 'click' ).click( function( e ) {
                e.preventDefault();

                var filters = [];
                $( '.panel input:checked', $bulk ).each( function( i, o ) {
                  filters.push( $( o ).attr( 'value' ) );
                } );
                $( '.panel', $bulk ).slideUp();

                var data = {
                  action: 'modula_assign_filters',
                  Modula: $( '#Modula' ).val(),
                  filters: filters.join( '|' ),
                  id: selected.join( ',' )
                };

                TG.show_loading();
                $.post( ajaxurl, data, function() {
                  TG.load_images();
                  TG.hide_loading();
                } );
              } );

              $( '.panel', $bulk ).slideDown();
            }
            break;
          case 'remove':
            var selected = [];
            $( '#images .item.selected' ).each( function( i, o ) {
              selected.push( $( o ).data( 'id' ) );
            } );
            if ( selected.length == 0 ) {
              alert( 'No images selected' );
            } else {
              $( '.panel', $bulk ).hide();
              $( '.panel strong', $bulk ).text( 'Confirm' );
              $( '.panel .text', $bulk ).text( 'You selected ' + selected.length + ' images to remove, proceed ?' );

              $( '.cancel', $bulk ).unbind( 'click' ).click( function( e ) {
                e.preventDefault();
                $( '.panel', $bulk ).slideUp();
              } );

              $( '.proceed', $bulk ).unbind( 'click' ).click( function( e ) {
                e.preventDefault();
                $( '.panel', $bulk ).slideUp();

                var data = {
                  action: 'modula_delete_image',
                  Modula: $( '#Modula' ).val(),
                  id: selected.join( ',' )
                };

                TG.show_loading();
                $.post( ajaxurl, data, function() {
                  $( '#images .item.selected' ).remove();
                  TG.hide_loading();
                } );
              } );

              $( '.panel', $bulk ).slideDown();
            }
            break;
        }
      } );
      $( '.import-source' ).on( 'change', function() {
        var source = $( this ).val();
        $( '#external-galleries ul' ).empty();

        if ( source ) {
          var data = {
            action: 'modula_get_ext_galleries',
            source: source,
            Modula: $( '#Modula' ).val()
          };

          function fill( list ) {
            var $ul = $( '#external-galleries ul' );
            $.each( list, function( i, g ) {
              console.log( g );
              $ul.append( '<li><input class=\'js-item\' type=\'checkbox\' value=\'' + g.id + '\'/> ' + g.title + '</li>' );
            } );
          }

          TG.show_loading();
          $.ajax( {
            url: ajaxurl,
            data: data,
            dataType: 'json',
            type: 'post',
            error: function( a, b, c ) {
              TG.hide_loading();
              alert( 'error loading galleries' );
            },
            success: function( r ) {
              if ( r.success ) {
                TG.hide_loading();

                fill( r.galleries );
              }
            }
          } );
        }
      } );
      $( '.open-media-panel' ).on( 'click', function() {

        var currentImageSize = $( '.current-image-size' ).val();

        tgm_media_frame = wp.media.frames.tgm_media_frame = wp.media( {
          multiple: true,
          library: {
            type: 'image'
          }
        } );

        modula_wp_caption_field = $( '#wp_caption' ).val();
        modula_wp_title_field = $( '#wp_title' ).val();

        tgm_media_frame.on( 'select', function() {
          var selection = tgm_media_frame.state().get( 'selection' );
          var images = [];
          selection.map( function( attachment ) {
            attachment = attachment.toJSON();

            var obj = {
              imageId: attachment.id
            };

            if ( modula_wp_caption_field == 'title' )
              obj.description = attachment.title;
            if ( modula_wp_caption_field == 'description' )
              obj.description = attachment.description;
            if ( modula_wp_caption_field == 'caption' )
              obj.description = attachment.caption;

            if ( modula_wp_title_field == 'title' )
              obj.title = attachment.title;
            if ( modula_wp_title_field == 'description' )
              obj.title = attachment.description;
            if ( modula_wp_title_field == 'none' )
              obj.title = '';

            if ( attachment.sizes[ TG.defaultImageSize ] )
              obj.imagePath = attachment.sizes[ TG.defaultImageSize ].url;
            else
              obj.imagePath = attachment.url;

            if ( attachment.sizes.full )
              obj.altImagePath = attachment.sizes.full.url;

            images.push( obj );

            if ( typeof attachment.sizes[ currentImageSize ] !== 'undefined' ) {
              obj.imagePath = attachment.sizes[ currentImageSize ].url;
            }
            else {
              obj.imagePath = attachment.sizes.full.url;

            }

          } );

          var data = {
            action: 'modula_add_image',
            enc_images: JSON.stringify( images ),
            galleryId: $( '#gallery-id' ).val(),
            Modula: $( '#Modula' ).val()
          };

          TG.show_loading();
          $.ajax( {
            url: ajaxurl,
            data: data,
            dataType: 'json',
            type: 'post',
            error: function( a, b, c ) {
              TG.hide_loading();
              alert( 'error adding images' );
            },
            success: function( r ) {
              if ( r.success ) {
                TG.hide_loading();
                TG.load_images();
              }
            }
          } );
        } );

        tgm_media_frame.open();
      } );
    }
  };
}( jQuery );

var NewGalleryWizard = function( $ ) {

  var _curPage = 1;
  var $_wizard = null;
  var _lock = false;

  return {
    init: function() {
      $_wizard = $( '#modula-wizard.add-gallery' );

      /*! Wizard next */
      $_wizard.find( '.next' ).click( function() {
        if ( $( this ).hasClass( 'disabled' ) )
          return;

        var branch = 'images';
        $( '.invalid' ).removeClass( 'invalid' );

        if ( _curPage == 1 ) {
          var name = $.trim( $( '[name=tg_name]' ).val() );
          if ( name.length == 0 ) {
            $( '[name=tg_name]' ).addClass( 'invalid' );
            return false;
          }
        }

        /*! Wizard save */
        if ( $_wizard.find( 'fieldset[data-step=' + _curPage + ']' ).data( 'save' ) ) {
          NewGalleryWizard.save();
          return;
        } else {

          $_wizard.find( 'fieldset' ).hide();
          _curPage ++;
          var $fs = $_wizard.find( 'fieldset[data-step=' + _curPage + ']' );
          /*if (_curPage == 3) {

              $fs = $fs.filter("[data-branch=" + branch + "]");
          }*/
          $fs.show();

          if ( $fs.data( 'save' ) ) {
            $( '.prev' ).css( 'visibility', 'visible' );
            $( this ).text( 'Save' );
            if ( branch == 'images' ) {
              $( '.select-images' ).show();
              $( '[name=post_categories]' ).val( '' );
              $( '[name=woo_categories]' ).val( '' );
              $( '[name=post_tags]' ).val( '' );
            } else if ( branch == 'posts' ) {
              $( '.select-images' ).hide();
              $( '[name=enc_images]' ).val( '' );

              var categories = [];
              $( '[name=_post_categories]:checked' ).each( function() {
                categories.push( this.value );
              } );
              $( '[name=post_categories]' ).val( categories.join( ',' ) );

              var tags = [];
              $( '[name=_post_tags]:checked' ).each( function() {
                tags.push( this.value );
              } );
              $( '[name=post_tags]' ).val( tags.join( ',' ) );
            } else {
              $( '.select-images' ).hide();
              $( '[name=enc_images]' ).val( '' );

              var categories = [];
              $( '[name=_woo_categories]:checked' ).each( function() {
                categories.push( this.value );
              } );
              $( '[name=woo_categories]' ).val( categories.join( ',' ) );
            }
          } else {
            $( this ).text( 'Next' );
          }
        }

        $_wizard.find( '.prev' ).css( {
          visibility: 'visible'
        } );
      } );

      /*! Wizard prev */
      $_wizard.find( '.prev' ).click( function() {
        if ( $( this ).hasClass( 'disabled' ) )
          return;
        _curPage --;

        var branch = $( '[name=tg_source]:checked' ).val();

        if ( _curPage == 1 ) {
          $( this ).css( {
            visibility: 'hidden'
          } );
        }

        $_wizard.find( 'fieldset' ).hide();
        var $fs = $_wizard.find( 'fieldset[data-step=' + _curPage + ']' );
        if ( _curPage == 3 ) {
          $fs = $fs.filter( '[data-branch=' + branch + ']' );
        }
        $fs.show();
        $_wizard.find( '.next' ).css( {
          visibility: 'visible'
        } ).text( 'Next' );
      } );

      /*! Wizard add images */
      $_wizard.find( '.add-images' ).click( function( e ) {
        e.preventDefault();
        var size = $_wizard.find( '[name=def_imgsize]' ).val();

        var caption_field = $( '[name=ftg_wp_field_caption]' ).val();
        var title_field = $( '[name=ftg_wp_field_title]' ).val();
        TG.choose_images( title_field, caption_field, function( images ) {

          $( '[name=enc_images]' ).val( JSON.stringify( images ) );

          $.each( images, function() {

            var $_tile = $( '<div class=\'tile list-group-item\' />' );
            $_tile.append( '<a class=\'btn-floating waves-effect waves-light red del\'><i class=\'mdi-content-clear\'></i></a>' );
            $_tile.append( '<img src="' + this.thumbnail + '" />' );

            $_wizard.find( '.images' ).append( $_tile );

            $_tile.find( '.del' ).click( function() {
              $( this ).parents( '.tile' ).fadeOut( 200, function() {
                $( this ).remove();
              } );
            } );
          } );

        } );
        $_wizard.find( '.images' ).sortable( {
          update: function() {
            var images = [];
            $_wizard.find( '.images .tile' ).each( function() {
              images.push( $( this ).data( 'img' ) );
            } );
            $( '[name=enc_images]' ).val( JSON.stringify( images ) );
          }
        } );
      } );
    },
    save: function() {

      var name = $( '#name' ).val();
      var description = $( '#description' ).val();
      var images = $( '[name=enc_images]' ).val();
      var width = $( '#width' ).val();
      var height = $( '#height' ).val();
      var effect = $( '[name=tg_hoverEffect]' ).val();
      var img_size = $( '#img_size' ).val();

      var data = {
        action: 'modula_create_gallery',
        name: name,
        description: description,
        images: images,
        width: width,
        height: height,
        effect: effect,
        img_size: img_size,
        Modula: $( '#Modula' ).val()
      };

      $_wizard.find( 'footer a' ).addClass( 'disabled' );
      $_wizard.find( '.loading' ).show();

      $.ajax( {
        url: ajaxurl,
        data: data,
        dataType: 'json',
        type: 'post',
        error: function( a, b, c ) {
          $( '#error' ).modal();
          $( '#error' ).modal( 'open' );
        },
        success: function( id ) {
          id = $.trim( id );
          $( '#name' ).val( '' );
          $( '#description' ).val( '' );

          $_success = $( '#success' );
          $_success.find( 'code' ).text( '[Modula id=\'' + id + '\']' );
          $_success.find( '.gallery-name' ).text( name );
          $_success.find( '.customize' ).attr( 'href', '?page=modula-pro-edit&galleryId=' + id );

          try {
            $_success.modal();
            $_success.modal( 'open' );
          }
          catch ( error ) {
            console.warn( 'Modal not loaded. Please contact support.' );
          }
        }
      } );
    }
  };
}( jQuery );
var ImportWizard = function( $ ) {

  var _curPage = 1;
  var $_wizard = null;
  var _lock = false;

  return {
    init: function() {
      $_wizard = $( '#modula-wizard.import' );
      $( '#external-galleries .js-select-all' ).on( 'click', function() {
        $( '#external-galleries .js-item' ).each( function() {
          this.checked = true;
        } );
      } );
      // $_wizard.find('select').material_select();

      /*! Wizard next */
      $_wizard.find( '.next' ).click( function() {
        if ( $( this ).hasClass( 'disabled' ) )
          return;

        // var branch = $("[name=ftg_source]:checked").val();
        $( '.invalid' ).removeClass( 'invalid' );

        if ( _curPage == 1 ) {
          var source = $( '.import-source' ).val();
          if ( 'undefined' === typeof source ) {
            $( '.import-source' ).addClass( 'invalid' );
            return false;
          }
        }

        if ( _curPage == 2 ) {
          var count = $( '#external-galleries .js-item:checked' ).length;
          if ( count == 0 )
            return false;

          $_wizard.find( '.galleries-count' ).text( count );
        }

        /*! Wizard save */
        if ( $_wizard.find( 'fieldset[data-step=' + _curPage + ']' ).data( 'save' ) ) {
          ImportWizard.import();
          return;
        } else {
          branch = 'images';
          $_wizard.find( 'fieldset' ).hide();
          _curPage ++;

          var $fs = $_wizard.find( 'fieldset[data-step=' + _curPage + ']' );
          $fs.show();

          if ( $fs.data( 'save' ) ) {
            $( '.prev' ).css( 'visibility', 'visible' );
            $( this ).text( 'Proceed' );

          } else {
            $( this ).text( 'Next' );
          }
        }

        $_wizard.find( '.prev' ).css( {
          visibility: 'visible'
        } );
      } );

      /*! Wizard prev */
      $_wizard.find( '.prev' ).click( function() {
        if ( $( this ).hasClass( 'disabled' ) )
          return;
        _curPage --;

        var branch = $( '[name=ftg_source]:checked' ).val();

        if ( _curPage == 1 ) {
          $( this ).css( {
            visibility: 'hidden'
          } );
        }

        $_wizard.find( 'fieldset' ).hide();
        var $fs = $_wizard.find( 'fieldset[data-step=' + _curPage + ']' );
        if ( _curPage == 3 ) {
          $fs = $fs.filter( '[data-branch=' + branch + ']' );
        }
        $fs.show();
        $_wizard.find( '.next' ).css( {
          visibility: 'visible'
        } ).text( 'Next' );
      } );
    },
    import: function() {
      var source = $( '.import-source' ).val();
      var ids = [];

      $( '#external-galleries .js-item:checked' ).each( function( i, e ) {
        ids.push( $( e ).val() );
      } );

      var data = {
        action: 'modula_do_import_galleries',
        source: source,
        ids: ids.join( ',' ),
        Modula: $( '#Modula' ).val()
      };

      $_wizard.find( 'footer a' ).addClass( 'disabled' );
      $_wizard.find( '.loading' ).show();

      $.ajax( {
        url: ajaxurl,
        data: data,
        dataType: 'json',
        type: 'post',
        error: function( a, b, c ) {
          $( '#error' ).modal();
          $( '#error' ).modal( 'open' );
        },
        success: function( r ) {
          if ( r.success ) {
            $( '#success' ).modal();
            $( '#success' ).modal( 'open' );
          } else {
            $( '#error' ).modal();
            $( '#error' ).modal( 'open' );
          }
        }
      } );
    }
  };
}( jQuery );
jQuery( function() {

  jQuery( '.pickColor' ).wpColorPicker( {
    change: function( event, ui ) {},
    clear: function() {},
    hide: true,
    palettes: true
  } );

  TG.bind();
  NewGalleryWizard.init();
  ImportWizard.init();
} );