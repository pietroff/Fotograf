<?php

$active_filters = explode( '|', $gallery->filters );

function getName( $image ) {
	$p = explode( "/", $image->imagePath );

	return $p[ count( $p ) - 1 ];
}


foreach ( $imageResults as $image ) {
	$sizes = ModulaTools::get_image_size_links( $image->imageId );
	$thumb = array_key_exists( "150x150", $sizes ) ? $sizes["150x150"] : $image->imagePath;
	?>
	<div class='item card' data-image-id="<?php echo esc_attr( $image->imageId ); ?>" data-id="<?php echo esc_attr( $image->Id ); ?>">
		<input type="hidden" name="filter-list" value="<?php echo esc_attr( $image->imageId ); ?>">
		<div class="figure card-image" style="background-image: url('<?php echo esc_attr( $thumb ); ?>');">
			<img class="thumb" src="<?php echo esc_url( plugins_url( '../images/square.gif', __FILE__ ) ); ?>" data-filter="<?php echo esc_attr( $image->filters ); ?>"/>
			<?php
			if ( ! empty( $image->filters ) ) {
				print "<ul class='filters'>";

				foreach ( explode( '|', $image->filters ) as $f ) {
					if ( in_array( $f, $active_filters ) ) {
						print "<li> $f </li>";
					}
				}

				print "</ul>";

				print "<input type='hidden' class ='current_image_filter' value=$image->filters>";

			}


			?>

		</div>
		<div class="card-content">
			<p class="truncate">
				<?php echo ( isset( $image->title ) && ! empty( $image->title ) ) ? esc_html( $image->title ) : esc_html( $image->description ); ?>
			</p>
			<div class="data">
				<input class="copy" type="hidden" name="id" value="<?php echo esc_attr( $image->Id ); ?>"/>
				<input class="copy" type="hidden" name="img_id" value="<?php echo esc_attr( $image->imageId ); ?>"/>
				<input class="copy" type="hidden" name="sortOrder" value="<?php echo esc_attr( $image->sortOrder ); ?>"/>
				<input class="copy" type="hidden" name="filters" value="<?php echo esc_attr( $image->filters ); ?>"/>
				<select name="img_url" class="select">
					<?php foreach ( $sizes as $k => $v ) : ?>
						<option <?php echo $v == $image->imagePath ? "selected" : "" ?> value="<?php echo esc_attr( $v ); ?>"><?php echo esc_html( $k ); ?></option>
					<?php endforeach ?>
				</select> <input type="hidden" name="link" value="<?php echo esc_attr( $image->link ); ?>"/>
				<input type="hidden" name="target" value="<?php echo esc_attr( $image->target ); ?>"/>
				<input type="hidden" name="valign" value="<?php echo esc_attr( $image->valign ); ?>"/>
				<input type="hidden" name="halign" value="<?php echo esc_attr( $image->halign ); ?>"/>
				<input type="hidden" name="sortOrder" value="<?php echo esc_attr( $image->sortOrder ); ?>"/>
				<pre><?php echo esc_html( $image->description ); ?></pre>
				<input id="img-title" value="<?php echo esc_attr( $image->title ); ?>">
			</div>
		</div>

		<div class="card-action">
			<a href="#" class="edit"> <span><?php echo esc_html__( 'Edit', 'modula-gallery' ); ?></span> </a>
			<a href="#" class="remove"> <span><?php echo esc_html__( 'Remove', 'modula-gallery' ); ?></span> </a>
		</div>

	</div>
<?php } ?>