<?php
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
	die( _e( 'You are not allowed to call this page directly.', 'modula-gallery' ) );
}

if ( empty( $tg_subtitle ) ) {
	$tg_subtitle = "License";
}

?>

<?php include( "header.php" ); ?>

<div class="row">
	<form method="post" action="options.php" class="col ">
		<?php settings_fields( 'modula_license' ); ?>

		<table class="form-table license">
			<tbody>
			<tr valign="top">
				<th scope="row" valign="top">
					<?php _e( 'License status' ); ?>
				</th>
				<td class="field">
					<?php if ( $status != "valid" ) : ?>
						<span class="status red white-text darken-1"><?php echo esc_html__( 'INACTIVE', 'modula-gallery' ); ?></span> - <?php echo esc_html__( 'you are not receiving updates', 'modula-gallery' ); ?><?php else : ?>
						<span class="status green white-text darken-1"><?php echo esc_html__( 'ACTIVE', 'modula-gallery' ); ?></span> - <?php echo esc_html__( 'you are receiving updates', 'modula-gallery' ); ?>
					<?php endif ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="top">
					<?php _e( 'License Key' ); ?>
				</th>
				<td class="field">
					<input id="modula_license_key" name="modula_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>"/>
					<label class="description" for="modula_license_key"><?php esc_html__( 'Enter your license key' ); ?></label>
				</td>
			</tr>
			<?php if ( false !== $license ) { ?>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Activate License' ); ?>
					</th>
					<td>
						<?php if ( $status !== false && $status == 'valid' ) { ?><?php wp_nonce_field( 'modula_nonce', 'modula_nonce' ); ?>
							<input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php esc_html_e( 'Deactivate License' ); ?>"/>
						<?php } else { ?><?php wp_nonce_field( 'modula_nonce', 'modula_nonce' ); ?>
							<input type="submit" class="button-secondary" name="edd_license_activate" value="<?php esc_html_e( 'Activate License' ); ?>"/>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php submit_button(); ?>

	</form>
</div>