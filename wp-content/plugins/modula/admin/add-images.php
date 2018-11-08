<?php
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
	die( _e( 'You are not allowed to call this page directly.', 'modula-gallery' ) );
}

//Load/Reload images
if ( isset( $_POST['galleryId'] ) ) {
	if ( check_admin_referer( 'Modula', 'Modula' ) ) {
		$galleryId = intval( $_POST['galleryId'] );
		$gallery   = $this->ModulaDB->getGalleryById( $galleryId );
	}
}
?>
<div class='wrap'>
	<?php include( "adv.php" ) ?>
	<h2>Modula - <?php echo esc_html__( 'Add Images', 'modula-gallery' ); ?></h2>
	<?php if ( ! isset( $_POST['galleryId'] ) ) {

		$galleryResults = $this->ModulaDB->getGalleries();

		?>
		<p><?php _e( 'Select a gallery to add or edit its images', 'modula-gallery' ); ?></p>
	<table class="widefat post fixed" id="galleryResults" cellspacing="0">
		<thead>
		<tr>
			<th><?php _e( 'Gallery Name', 'modula-gallery' ); ?></th>
			<th><?php _e( 'Description', 'modula-gallery' ); ?></th>
			<th></th>
			<th></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th><?php _e( 'Gallery Name', 'modula-gallery' ); ?></th>
			<th><?php _e( 'Description', 'modula-gallery' ); ?></th>
			<th></th>
			<th></th>
		</tr>
		</tfoot>
		<tbody>
		<?php
		foreach ( $galleryResults as $gallery ) {
			?>
			<tr>
				<td><?php echo esc_html( $gallery->name ); ?></td>
				<td><?php echo esc_html( $gallery->description ); ?></td>
				<td></td>
				<td>
					<form name="select_gallery_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>" method="post">
						<?php wp_nonce_field( 'Modula', 'Modula' ); ?>
						<input type="hidden" name="galleryId" value="<?php echo esc_attr( $gallery->Id ); ?>"/>
						<input type="hidden" name="galleryName" value="<?php echo esc_attr( $gallery->name ); ?>"/>
						<input type="submit" name="Submit" class="button-primary" value="<?php echo esc_attr( 'Select Gallery', 'modula-gallery' ); ?>"/>
					</form>
				</td>
			</tr>
		<?php } ?>
		<tr></tr>
		</tbody>
	</table><?php } else { ?>
		<div class="tip updated">
			<p>
				<strong><?php echo esc_html( 'About choosing a proper image size:', 'modula-gallery' ) ?></strong> <?php _e( "Modula doesn't scale down the images
            when there's enough space, it gives you the freedom to choose your favourite size for each image.", 'modula-gallery' ) ?>
				<br/> <br/>
				<?php echo esc_html( 'So you should use images that are smaller than the container, choose the', 'modula-gallery' ) ?>
				<strong><?php echo esc_html( 'thumbnail', 'modula-gallery' ) ?></strong> <?php echo esc_html( 'or', 'modula-gallery' ) ?>
				<strong><?php echo esc_html( 'medium', 'modula-gallery' ) ?></strong> <?php echo esc_html( 'size, for example.', 'modula-gallery' ) ?>
			</p>
		</div>
		<form name="add_image_form" action="?" method="post" id="add_image_form">
			<?php wp_nonce_field( 'Modula', 'Modula' ); ?>
			<input type="hidden" name="galleryId" value="<?php echo esc_attr( $gallery->Id ); ?>"/>
			<input type="hidden" name="ftgHookMediaPanel" value="1"/>
			<table class="widefat post fixed" cellspacing="0">
				<thead>
				<tr>
					<th width="240"><?php echo esc_html__( 'Image', 'modula-gallery' ); ?></th>
					<th><?php echo esc_html__( 'Action on click', 'modula-gallery' ); ?></th>
					<th><?php echo esc_html__( 'Caption', 'modula-gallery' ); ?></th>
					<th width="90"><?php echo esc_html__( 'Sort Order', 'modula-gallery' ); ?></th>
					<th width="115"></th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<th><?php echo esc_html__( 'Image', 'modula-gallery' ); ?></th>
					<th><?php echo esc_html__( 'Action on click', 'modula-gallery' ); ?></th>
					<th><?php echo esc_html__( 'Caption', 'modula-gallery' ); ?></th>
					<th><?php echo esc_html__( 'Sort Order', 'modula-gallery' ); ?></th>
					<th></th>
				</tr>
				</tfoot>
				<tbody>
				<tr>
					<td>
						<span class="img"></span> <br/>
						<?php
						$modal_update_href = esc_url( add_query_arg( array(
							                                             'page'     => 'shiba_gallery',
							                                             '_wpnonce' => wp_create_nonce( 'shiba_gallery_options' ),
						                                             ), admin_url( 'upload.php' ) ) );
						?>
						<a id="choose-from-library-link" href="#" data-update-link="<?php echo esc_attr( $modal_update_href ); ?>" data-choose="<?php esc_attr_e( 'Choose images' ); ?>" data-update="<?php esc_attr_e( 'Choose images' ); ?>"><?php echo esc_html__( 'Choose images' ); ?>
						</a> <input class="pick button-primary" type="button" value="Select Image"/>
						<input type="hidden" name="img_url"/> <input type="hidden" name="img_id"/>
					</td>
					<td>
						<input type="radio" name="click_action" value="" checked="checked"/> <?php echo esc_html__( 'None', 'modula-gallery' ) ?>
						<br/>
						<input type="radio" name="click_action" value="zoom"/> <?php echo esc_html__( 'Zoom', 'modula-gallery' ) ?>
						<br/>

						<div class="text dark">
							<input type="radio" name="click_action" value="url"/> <?php echo esc_html__( 'Go to URL:', 'modula-gallery' ) ?>
							<input type="text" size="20" name="url" disabled="disabled"/>
						</div>
					</td>
					<td>
						<div class="text dark">
							<textarea type="text" name="image_caption" cols="35" rows="3" value=""></textarea>
						</div>
					</td>
					<td>
						<div class="text dark">
							<input type="text" class="w50" name="image_sortOrder" size="10" value="1"/>
						</div>
					</td>
					<td class="major-publishing-actions">
						<input data-type="add" type="submit" id="add-submit" name="Submit" class="button-primary" value="Add Image"/>
					</td>
				</tr>
				</tbody>
			</table>
		</form>

		<?php $GalleryId = $_POST['galleryId']; ?><h3><?php echo esc_html__( 'Gallery Images', 'modula-gallery' ); ?>
			: <?php echo esc_html__( $gallery->name ); ?></h3>
		<form name="switch_gallery" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
			<?php wp_nonce_field( 'Modula', 'Modula' ); ?>
			<input type="hidden" name="switch" value="true"/>
			<input type="hidden" name="gallery-id" value="<?php echo esc_attr( $GalleryId ); ?>" id="gallery-id"/>
			<p>
				<input type="submit" name="Submit" class="button-primary" value="<?php echo esc_html__( 'Switch Gallery', 'modula-gallery' ); ?>"/>
			</p>
		</form><p><?php _e( 'Edit existing images in this gallery', 'modula-gallery' ); ?></p>

		<!-- image list -->
		<table class="widefat post fixed" id="imageResults" cellspacing="0">
			<thead>
			<tr>
				<th width="105"><?php echo esc_html__( 'Preview', 'modula-gallery' ); ?></th>
				<th width="115"></th>
				<th></th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th><?php echo esc_html__( 'Preview', 'modula-gallery' ); ?></th>
				<th></th>
				<th></th>
			</tr>
			</tfoot>
			<tbody>

			</tbody>
		</table>

		<script>
          jQuery( function() {
            FTG.load_images();

            var $ = jQuery;
            var cur_img_el = null;
            var tgm_media_frame;

            $( '.pick' ).on( 'click', function() {

              tgm_media_frame = wp.media.frames.tgm_media_frame = wp.media( {
                multiple: true,
                library: {
                  type: 'image'
                }
              } );

              tgm_media_frame.on( 'select', function() {
                var selection = tgm_media_frame.state().get( 'selection' );
                var images = [];
                selection.map( function( attachment ) {
                  attachment = attachment.toJSON();
                  images.push( attachment );
                  // Do something with attachment.id and/or attachment.url here
                } );

                var data = {
                  action: 'add_image',
                  images: images.join( ',' ),
                  Modula: $( '#Modula' ).val()
                };

                return;
                FTG.show_loading();
                $.ajax( {
                  url: ajaxurl,
                  data: data,
                  dataType: 'json',
                  type: 'post',
                  error: function( a, b, c ) {
                    FTG.hide_loading();
                    alert( 'error adding images' );
                  },
                  success: function( r ) {
                    if ( r.success ) {
                      FTG.load_images();
                    }
                  }
                } );
              } );

              tgm_media_frame.open();
            } );

            /*$(".pick").on("click", function() {
				cur_img_el = $(this).siblings(".img");
				//tb_show('', 'media-upload.php?tab=library&amp;type=image&amp;TB_iframe=true');

				tb_show( 'Add images', 'media-upload.php?post_id=1&amp;TB_iframe=true' );
			});

			window.send_to_editor = function(html, e) {

				html = "<div>" + html + "</div>";
				var imgurl = jQuery('img', html).attr('src');
				var classes = "";
				if((jQuery('img', html).attr('class')))
					classes = (jQuery('img', html).attr('class'));

				if(jQuery('a', html).attr("rel"))
					classes += (" " + jQuery('a', html).attr("rel"));

				var id = "0";
				var parts = classes.split(" ");

				for(var i=0; i<parts.length; i++) {
					if(parts[i].substr(0, 'wp-image'.length) == 'wp-image' ||
						parts[i].substr(0, 'wp-att'.length) == 'wp-att') {
						id = parts[i].split("-").pop();
						break;
					}
				}

				if(id == "0")
				{
					var p = $("<div title='Attention'>Ouch! I couldn't retrieve the image id, please contact my developer. If you don't want to use the zoom you can go on.</div>").dialog({
						modal: true,
						buttons: {
							Close: function () {
								p.dialog("destroy");
							}
						}
					});
				}

				cur_img_el.empty().append("<img src='" + imgurl + "' />");
				cur_img_el.find("img").css("max-width", "100%");
				cur_img_el.siblings("[name=img_url]").val(imgurl);
				cur_img_el.siblings("[name=img_id]").val(id);

				tb_remove();
			}*/
          } );
		</script>
	<?php } ?>

</div>