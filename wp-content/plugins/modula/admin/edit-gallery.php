<?php
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
	die( _e( 'You are not allowed to call this page directly.', 'modula-gallery' ) );
}

function tg_p( $gallery, $field, $default = null ) {
	if ( $gallery == null || $gallery->$field === null ) {
		if ( $default === null ) {
			print "";
		} else {
			print stripslashes( $default );
		}
	} else {
		print stripslashes( $gallery->$field );
	}
}

$galleryResults = $this->ModulaDB->getGalleries();
$gallery        = $this->loadedData;
$tg_subtitle    = "Edit Gallery: " . $gallery->name;

if ( ! isset( $gallery->filters ) ) {
	$gallery->filters = "";
}

$filters = explode( '|', $gallery->filters );

global $modula_parent_page;

include( "header.php" );

?>

<script>
  var modula_wp_caption_field = '<?php tg_p( $gallery, "wp_field_caption" )  ?>';
</script>
<div class="container">
	<div class="row collapsible">
		<div class="card-panel light-green lighten-4">
			<span> <?php esc_html__( 'Shortcode:', 'modula-gallery' ); ?> </span>
			<input type="text" readonly value="[Modula id='<?php echo esc_attr( $gallery->id ); ?>']"> </input>
		</div>
	</div>
</div>
<?php

$idx = 0;
// $colors = array('indigo', 'blue', 'cyan', 'teal' ,'green', 'lime', 'amber', 'deep-orange');


?>

<div class="container">
	<div class="row">
		<ul class="collapsible" data-collapsible="accordion">
			<?php foreach ( $modula_fields as $section => $s ): ?>
				<li id="<?php echo strtolower( str_replace( ' ', '-', $section ) ); ?>">
					<div class="collapsible-header white-text  darken-2">
						<i class="<?php echo esc_attr( $s["icon"] ); ?>"></i>
						<span><?php echo esc_html( $section ) ?> </span> <i class="fa fa-chevron-right"></i>
					</div>

					<div class="collapsible-body    lighten-5 tab form-fields">

						<input type="hidden" id="wp_caption" value="<?php echo esc_attr( $gallery->wp_field_caption ); ?>">
						<input type="hidden" id="wp_title" value="<?php echo esc_attr( $gallery->wp_field_title ); ?>">

						<form name="gallery_form" id="gallery_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>" method="post">
							<?php wp_nonce_field( 'Modula', 'Modula' ); ?>
							<input type="hidden" name="ftg_gallery_edit" id="gallery-id" value="<?php echo esc_attr( $gallery->id ); ?>"/>
							<table class="widefat post fixed" cellspacing="0">
								</tbody>
							</table>
						</form>

						<table>
							<tbody>
							<?php foreach ( $s["fields"] as $f => $data ) : ?><?php if ( is_array( $data["excludeFrom"] ) && ! in_array( $modula_parent_page, $data["excludeFrom"] ) ) : ?>
								<tr class="row-<?php echo esc_attr( $f ); ?> <?php echo esc_attr( $data["type"] ); ?>">
									<th scope="row">
										<label class="label-text"><?php echo esc_html( $data["name"] ); ?>
											<?php if ( isset( $data["mu"] ) ) : ?>
												(<?php echo esc_html( $data["mu"] ) ?>)
											<?php endif ?>
										</label>
									</th>
									<td>
										<div class="field">
											<?php if ( $data["type"] == "text" ) : ?>
												<div class="text">
													<input type="text" size="30" name="tg_<?php echo esc_attr( $f ); ?>" value="<?php echo esc_attr( $gallery->$f ); ?>"/>
												</div>
											<?php elseif ( $data["type"] == "select" ) : ?>

												<div class="text">
													<select class="browser-default dropdown-menu" name="tg_<?php echo esc_attr( $f ); ?>">
														<?php foreach ( array_keys( $data["values"] ) as $optgroup ) : ?>
															<optgroup label="<?php echo esc_attr( $optgroup ); ?>">
																<?php foreach ( $data["values"][ $optgroup ] as $option ) : ?><?php $v = explode( "|", $option ); ?>
																	<option value="<?php echo esc_attr( $v[0] ); ?>" <?php echo $v[0] == $gallery->$f ? "selected" : "" ?> ><?php echo esc_html( $v[1] ); ?></option>
																<?php endforeach ?>
															</optgroup>
														<?php endforeach ?>
													</select>
												</div>
											<?php elseif ( $data["type"] == "toggle" ) : ?>
												<div class="text">
													<input type="checkbox" id="ftg_<?php echo esc_attr( $f ); ?>" name="tg_<?php echo esc_attr( $f ); ?>" value="<?php echo esc_attr( $gallery->$f ); ?>" <?php echo $gallery->$f == 'T' ? 'checked' : '' ?> />
													<label for="ftg_<?php echo esc_attr( $f ); ?>"><?php echo esc_html( $data["description"] ); ?></label>
												</div>

											<?php elseif ( $data["type"] == "slider" ) : ?>
												<div class="text">
													<label class="effect-description"><?php echo esc_html( $data['description'] ); ?></label>
													<p class="range-field">
														<input name="tg_<?php echo esc_attr( $f ); ?>" value="<?php echo absint( $gallery->$f ); ?>" type="range" min="<?php echo esc_attr( $data["min"] ); ?>" max="<?php echo esc_attr( $data["max"] ); ?>"/>
													</p>
												</div>

											<?php elseif ( $data["type"] == "number" ) : ?>
												<div class="text">
													<input type="text" name="tg_<?php echo esc_attr( $f ); ?>" class="integer-only" value="<?php echo esc_attr( $gallery->$f ); ?>">
												</div>

											<?php elseif ( $data["type"] == "color" ) : ?>
												<div class="text">
													<label class="effect-description effect-color" style="display:none;"> <?php echo esc_html( $data['description'] ); ?></label>
													<input type="text" size="6" data-default-color="<?php echo esc_attr( $data["default"] ); ?>" name="tg_<?php echo esc_attr( $f ); ?>" value="<?php echo esc_attr( $gallery->$f ); ?>" class='pickColor'/>
												</div>

											<?php elseif ( $data["type"] == "filter" ) : ?>
												<div class="filters gallery-filters dynamic-table">
													<div class="text"></div>
													<a href="#" class="add waves-effect waves-light btn">
														<i class="mdi-content-add left"></i> <?php esc_html__( 'Add filter', 'modula-gallery' ); ?>
													</a>
													<input type="hidden" id="tg_filter" name="tg_filter" value="<?php echo esc_html( $gallery->$f ); ?>"/>
												</div>

											<?php elseif ( $data["type"] == "textarea" ) : ?>
												<div class="text">
													<textarea name="tg_<?php echo esc_attr( $f ); ?>"><?php echo esc_html( $gallery->$f ); ?></textarea>
												</div>
											<?php elseif ( $data["type"] == "hover-effect" ): ?>

												<div class="text">
													<label class="effect-description"> <?php echo esc_html( $data['description'] ); ?> </label>
													<select name="tg_hoverEffect" class="select-effect">
														<?php foreach ( $this->hoverEffects as $effect ) : ?>
															<option <?php echo( $gallery->hoverEffect == strtolower( $effect->code ) ? "selected" : null ) ?> value="<?php echo esc_attr( $effect->code ); ?>"><?php echo esc_attr( $effect->name ); ?></option>
														<?php endforeach ?>
													</select>

													<!-- all effects preview -->
													<div class="preview modula">
														<?php foreach ( $this->hoverEffects as $effect ) : if ( $effect->code == 'none' ) {
															continue;
														} ?>

															<div class="panel panel-<?php echo esc_attr( $effect->code ); ?> items clearfix">
																<!-- show preview -->

																<div class="item effect-<?php echo esc_attr( $effect->code ); ?>">
																	<img src="<?php echo esc_url( plugins_url() ); ?>/modula/admin/images/effect.jpg" class="pic">
																	<div class="figc">
																		<div class="figc-inner">

																			<?php if ( $effect->allowTitle ): ?>
																				<h2>Lorem ipsum</h2>
																			<?php endif ?>

																			<?php if ( $effect->allowSubtitle ): ?>
																				<p class="description">Quisque diam
																					erat, mollis vitae enim eget</p>
																			<?php endif ?>

																			<?php if ( $effect->maxSocial > 0 ): ?>
																				<div class="jtg-social">
																					<a class="tw-icon fa fa-twitter" href="#"></a>
																					<a class="fb-icon fa fa-facebook" href="#"></a>
																					<a class="gp-icon fa fa-google-plus" href="#"></a>
																					<a class="pi-icon fa fa-pinterest" href="#"></a>
																				</div>
																			<?php endif ?>
																		</div>
																	</div>
																</div>

																<div class="effect-compatibility">

																	<label class="effect-description"> <?php echo esc_html( 'This effect is
																		compatible with:', 'modula-gallery' ); ?>
																		<?php if ( $effect->allowTitle ): ?>
																			<span><i class="fa fa-check"></i> <?php echo esc_html( 'Title', 'modula-gallery' ); ?></span>
																		<?php endif; ?>

																		<?php if ( $effect->allowSubtitle ): ?>
																			<span><i class="fa fa-check"></i> <?php echo esc_html( 'Subtitle', 'modula-gallery' ); ?> </span>
																		<?php endif; ?>

																		<?php if ( $effect->maxSocial > 0 ): ?>
																			<span><i class="fa fa-check"></i> <?php echo esc_html( 'Social Icons', 'modula-gallery' ); ?> </span>
																		<?php endif; ?>

																	</label>

																	<?php if ( ! ( $effect->allowTitle && $effect->allowSubtitle && $effect->maxSocial != 0 ) ): ?>
																		<label class="effect-description"><?php echo esc_html( 'This effect is not
																		compatible with:', 'modula-gallery' ); ?>

																			<?php if ( ! $effect->allowTitle ): ?>
																				<span> <i class="fa fa-times"></i> <?php echo esc_html( 'Title', 'modula-gallery' ); ?> </span>
																			<?php endif; ?>

																			<?php if ( ! $effect->allowSubtitle ): ?>
																				<span> <i class="fa fa-times"></i> <?php echo esc_html( 'Subtitle', 'modula-gallery' ); ?> </span>
																			<?php endif; ?>

																			<?php if ( $effect->maxSocial == 0 ): ?>
																				<span> <i class="fa fa-times"></i> <?php echo esc_html( 'Social Icons', 'modula-gallery' ); ?> </span>
																			<?php endif; ?>

																		</label>
																	<?php endif; ?>
																</div>
															</div>
														<?php endforeach ?>
													</div>
												</div>
											<?php endif ?>
											<div class="help">
												<?php _e( $data["description"] ); ?>
											</div>

										</div>
									</td>
								</tr>
							<?php endif ?><?php endforeach ?>

							</tbody>
						</table>
					</div>
				</li>
				<?php $idx ++ ?>

			<?php endforeach; ?>

			<li id="images">
				<div class="collapsible-header white-text white darken-2">
					<i class="mdi mdi-image-filter"></i>
					<span><?php echo esc_html__( 'Images', 'Modula-gallery' ) ?> </span>
					<i class="fa fa-chevron-right"></i>
				</div>

				<div class="collapsible-body white lighten-5">

					<div class="image-size-section">
						<div>
							<div class="tips">

                            <span class="shortpixel">
                            <img src="<?php echo esc_url( plugins_url( '', __file__ ) ); ?>/images/icon-shortpixel.png" alt="ShortPixel">
                          <a target="_blank" href="https://shortpixel.com/h/af/HUOYEBB31472"><?php esc_html_e( 'We suggest using the ShortPixel image optimization plugin to optimize your images and get the best possible SEO results & load speed..', 'modula-gallery' ) ?></a>
                          </span>

							</div>
						</div>
					</div>

					<div>
						<div class="actions row">
							<label class="label-text row"><?php esc_html_e( 'Add Images', 'modula-gallery' ) ?></label>
							<a href="#" class="open-media-panel waves-effect waves-light btn action button-bg"><i class="mdi-image-photo"></i> <?php esc_html_e( 'Add images', 'Modula-gallery' ) ?>
							</a>
						</div>


						<div class="bulk row">
							<label class="label-text row"><?php esc_html_e( 'Bulk Actions', 'modula-gallery' ) ?></label>
							<div class="options">
								<a class="btn button-bg waves-effect waves-light" href="#" data-action="select"><?php esc_html_e( 'Select all', 'modula-gallery' ) ?></a>
								<a class="btn button-bg waves-effect waves-light" href="#" data-action="deselect"><?php esc_html_e( 'Deselect all', 'modula-gallery' ) ?></a>
								<a class="btn button-bg waves-effect waves-light" href="#" data-action="toggle"><?php esc_html_e( 'Toggle selection', 'modula-gallery' ) ?></a>
								<a class="btn button-bg waves-effect waves-light" href="#" data-action="filter"><?php esc_html_e( 'Assign filters', 'modula-gallery' ) ?></a>
								<a class="btn button-bg waves-effect waves-light" href="#" data-action="remove"><?php esc_html_e( 'Remove', 'modula-gallery' ) ?></a>
							</div>
							<div class="panel">
								<strong></strong>
								<p class="text"></p>
								<p class="buttons">
									<a class="btn orange mrm cancel" href="#"><?php esc_html_e( 'CANCEL', 'modula-gallery' ); ?></a>
									<a class="btn green mrm proceed firm" href="#"><?php esc_html_e( 'PROCEED', 'modula-gallery' ); ?></a>
								</p>
							</div>
						</div>

						<?php if ( is_array( $filters ) && count( $filters ) > 1 ): ?>
							<div class="row filter-list">
								<b class="list-view"> <?php esc_html_e( 'Filters:', 'modula-gallery' ); ?> </b>
								<ul class="filter-select-control">
									<?php foreach ( $filters as $filter ): ?>
										<li class="filter-item"> <?php print $filter ?> </li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

						<div class="row">
							<span class="tip"><?php esc_html_e( 'Drag images to change order.', 'modula-gallery' ); ?></span>
						</div>


						<div id="image-list"></div>

						<!-- image panel -->
						<div id="image-panel-model" style="display:none">
							<a href="#" class="close" title="Close">X</a>
							<h4> <?php esc_html_e( 'Edit image', 'modula-gallery' ); ?> </h4>
							<div class="clearfix">
								<div class="left">
									<div class="figure"></div>
								</div>
								<div class="editimage-right">
									<div class="field">
										<label><?php echo esc_html( 'Title', 'modula-gallery' ); ?></label>
										<div class="text">
											<textarea id="item-title" name="title"></textarea>
										</div>
										<label><?php echo esc_html( 'Caption', 'modula-gallery' ); ?></label>
										<div class="text">
											<textarea id="item-description" name="description"></textarea>
										</div>
									</div>

									<div class="field">
										<label for="alignment"><?php echo esc_html( 'Alignment', 'modula-gallery' ); ?></label>
										<select name="halign">
											<option>left</option>
											<option selected>center</option>
											<option>right</option>
										</select> <select name="valign">
											<option>top</option>
											<option selected>middle</option>
											<option>bottom</option>
										</select>
									</div>
									<div class="field">
										<label><?php echo esc_html( 'Link', 'modula-gallery' ); ?></label>
										<div class="text">
											<!-- <input type="text" name="link" value="" class="text-input row">  -->
											<textarea id="item-link" name="link"></textarea> <select name="target">
												<option value=""><?php echo esc_html( 'Default target', 'modula-gallery' ); ?></option>
												<option value="_self"><?php echo esc_html( 'Open in same page', 'modula-gallery' ); ?></option>
												<option value="_blank"><?php echo esc_html( 'Open in new page', 'modula-gallery' ); ?></option>
											</select>
										</div>
									</div>
									<div class="field filters clearfix"></div>

								</div>
							</div>
							<div class="field buttons">
								<a href="#" data-action="cancel" class="action modal-action modal-close waves-effect waves-yellow btn-flat"><i class="mdi-content-reply"></i> <?php esc_html_e( 'Cancel', 'modula-gallery' ) ?>
								</a>
								<a href="#" data-action="save" class="action modal-action modal-close waves-effect waves-green btn-flat"><i class="fa fa-save"></i> <?php esc_html_e( 'Save', 'modula-gallery' ) ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</li>

		</ul>
	</div>
</div>

<a id="edit-gallery" data-tooltip="Update gallery" data-position="top" data-delay="10" class="tooltipped btn-floating btn-large waves-effect waves-light green update-gallery">
	<i class="fa fa-save"> </i> </a>


<div class="preloader-wrapper big active" id="spinner">
	<div class="spinner-layer spinner-blue-only">
		<div class="circle-clipper left">
			<div class="circle"></div>
		</div>
		<div class="gap-patch">
			<div class="circle"></div>
		</div>
		<div class="circle-clipper right">
			<div class="circle"></div>
		</div>
	</div>
</div>

<script>
  (function( $ ) {
    $( document ).ready( function() {
      TG.load_images();
      TG.init_gallery();

      function setHoverOpacity() {
        $( '.panel .item' ).hover(
            function() {
              var opacity = parseInt( $( '[name=tg_hoverOpacity]' ).val() );
              $( this ).find( 'img' ).fadeTo( 150, 1 - (opacity / 100) );
            },
            function() {
              $( this ).find( 'img' ).fadeTo( 150, 1 );
            }
        );
      }

      function setHoverColor() {
        var color = $( '[name=tg_hoverColor]' ).val();
        $( '.panel .item' ).css( 'background-color', color );
      }

      setInterval( setHoverColor, 500 );
      setHoverOpacity();
    } );
  })( jQuery );
</script>

<div id="import-modal" class="modal">
	<div class="modal-content">
		<h3><?php echo esc_html__( 'Import Configuration', 'modula-gallery' ); ?></h3>
		<p><?php echo esc_html__( 'Paste here the configuration code', 'modula-gallery' ); ?></p>
		<textarea> </textarea>

	</div>
	<div class="modal-footer">
		<a id="save" href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat"><?php echo esc_html__( 'Import', 'modula-gallery' ) ?></a>

		<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat"><?php echo esc_html__( 'Close', 'modula-gallery' ) ?></a>
	</div>
</div>

<div id="export-modal" class="modal">
	<div class="modal-content">
		<h3><?php echo esc_html__( 'Export Configuration', 'modula-gallery' ); ?></h3>
		<p><?php echo esc_html__( 'Copy the configuration code', 'modula-gallery' ); ?></p>

		<textarea readonly> </textarea>

	</div>
	<div class="modal-footer">
		<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat"><?php echo esc_html__( 'OK', 'modula-gallery' ) ?></a>
	</div>
</div>
