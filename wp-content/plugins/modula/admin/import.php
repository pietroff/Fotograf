<?php
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
	die( _e( 'You are not allowed to call this page directly.', 'modula-gallery' ) );
}

if ( empty( $tg_subtitle ) ) {
	$tg_subtitle = "Import galleries";
}

?>
<?php include( "header.php" ); ?>

<div id="modula-wizard" class="import">
	<h2>  <?php _e( 'Import galleries', 'modula-gallery' ); ?> </h2>
	<form action="#" method="post" onsubmit="return false;">
		<?php wp_nonce_field( 'Modula', 'Modula' ); ?>

		<fieldset data-step="1">
			<div class="row">
				<div class="input-field">
					<p><?php echo esc_html__( 'Select an external source from which you want to import existing galleries', 'modula-gallery' ); ?></p>
					<select class="import-source">
						<option value=""><?php echo esc_html__( 'Choose a source', 'modula-gallery' ); ?></option>
						<?php if ( class_exists( "Envira_Gallery_Lite" ) || class_exists( "Envira_Gallery" ) ) : ?>
							<option><?php echo esc_html__( 'Envira', 'modula-gallery' ); ?></option>
						<?php endif ?>
						<?php if ( class_exists( "nggGallery" ) ) : ?>
							<option><?php echo esc_html__( 'NextGen', 'modula-gallery' ); ?></option>
						<?php endif ?>
					</select>
				</div>
			</div>
		</fieldset>
		<fieldset data-step="2" data-branch="galleries">
			<div class="field">
				<h5><?php echo esc_html__( 'List of galleries', 'modula-gallery' ) ?></h5>
				<div id="external-galleries">
					<ul></ul>
					<button class="waves-effect button-bg green lighten-3 waves-light btn js-select-all">Select all
					</button>
				</div>
			</div>
		</fieldset>
		<fieldset data-step="3" data-save="true">
			<h5><?php echo esc_html__( 'You are going to import ', 'modula-gallery' ) ?>
				<strong class="galleries-count"></strong> <?php echo esc_html__( 'galleries.', 'modula-gallery' ) ?>
			</h5>
		</fieldset>

		<footer class="page-footer">
			<div class="progress loading hide">
				<div class="indeterminate"></div>
			</div>

			<a class="waves-effect waves-yellow btn-flat prev"><?php echo esc_html__( 'Previous', 'modula-gallery' ) ?></a>
			<a class="waves-effect waves-green btn-flat next"><?php echo esc_html__( 'Next', 'modula-gallery' ) ?></a>
		</footer>

	</form>
	<div id="success" class="modal">
		<div class="modal-content">
			<h4><?php echo esc_html__( 'Success!', 'modula-gallery' ) ?></h4>
			<p><?php echo esc_html__( 'All selected galleries have been imported!', 'modula-gallery' ) ?></p>
			<p> <?php printf( esc_html__( 'Go to the %s and copy the shortcode to paste inside your
				pages and posts', 'medzone' ), '<a href="?page=ModulaLite-admin">' . esc_html__( 'dashboard page', 'modula-gallery' ) . '</a>' ); ?></p>
		</div>
		<div class="modal-'footer">
			<a href="?page=modula-pro-admin" id="modal-close" class="waves-effect waves-green btn-flat modal-action"><?php echo esc_html__( 'Close', 'modula-gallery' ) ?></a>
		</div>
	</div>

	<div id="error" class="modal">
		<div class="modal-content">
			<h4><?php echo esc_html__( 'Error!', 'modula-gallery' ) ?></h4>
			<p><?php echo esc_html__( 'For some reason it was not possible to import one or more galleries', 'modula-gallery' ) ?></p>
		</div>
		<div class="modal-footer">
			<a href="?page=modula-pro-admin" class="waves-effect waves-green btn-flat modal-action"><?php echo esc_html__( 'Close', 'modula-gallery' ) ?></a>
		</div>
	</div>
</div>