<?php
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'WPMLP_STORE_URL', 'http://masonrylayout.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
define( 'WPMLP_ITEM_NAME', 'WP Masonry Layout Pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

function wmlp_plugin_updater() {

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'wmlp_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( WPMLP_STORE_URL, 'wp-masonry-layout-pro/wp-masonry-layout-pro.php', array(
			'version' 	=> '1.8', 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => WPMLP_ITEM_NAME, 	// name of this plugin
			'author' 	=> 'Dinesh Karki'  		// author of this plugin
		)
	);

}
add_action( 'admin_init', 'wmlp_plugin_updater', 0 );

function wmlp_license() {
	include('includes/admin/common/wmlp_header.php');
	include('includes/admin/license/wmlp_license.php');
	include('includes/admin/common/wmlp_footer.php');
}

function wmlp_sanitize_license( $new ) {
	$old = get_option( 'wmlp_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'wmlp_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

/************************************
* this illustrates how to activate 
* a license key
*************************************/

function wmlp_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['wmlp_license_activate'] ) ) {
		
		// run a quick security check 
	 	//if( ! check_admin_referer( 'wmlp_nonce', 'wmlp_nonce' ) ) 	
			//return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( $_POST['wmlp_license_key'] );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( WPMLP_ITEM_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, WPMLP_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "valid" or "invalid"

		update_option( 'wmlp_license_key', $license);
		update_option( 'wmlp_license_status', $license_data->license );

	}
}
add_action('admin_init', 'wmlp_activate_license');


/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function wmlp_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['wmlp_license_deactivate'] ) ) {

		// run a quick security check 
	 	//if( ! check_admin_referer( 'wmlp_nonce', 'wmlp_nonce' ) ) 	
		//	return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'wmlp_license_key' ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'deactivate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( WPMLP_ITEM_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, WPMLP_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ){
			update_option( 'wmlp_license_status','Please enter license key' );
			delete_option( 'wmlp_license_key', '' );
		}

	}
}
add_action('admin_init', 'wmlp_deactivate_license');


/************************************
* this illustrates how to check if 
* a license key is still valid
* the updater does this for you,
* so this is only needed if you
* want to do something custom
*************************************/

function wmlp_check_license_online() {

	global $wp_version;

	$license = trim( get_option( 'wmlp_license_key' ) );
		
	$api_params = array( 
		'edd_action' => 'check_license', 
		'license' => $license, 
		'item_name' => urlencode( WPMLP_ITEM_NAME ),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, WPMLP_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );


	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		update_option( 'wmlp_license_status','valid' );
		update_option( 'wmpl_license_checked_on', date('ymd') );
		return 'valid';
	} elseif( $license_data->license == 'expired' ) {
		update_option( 'wmlp_license_status','expired' );
		update_option( 'wmpl_license_checked_on', date('ymd') );
		return 'expired';
	} else {
		update_option( 'wmlp_license_status','invalid' );
		update_option( 'wmpl_license_checked_on', date('ymd') );
		return 'invalid';
	}
}

function wmpl_check_license(){
	$lastChecked = get_option('wmpl_license_checked_on');
	if (date('ymd') == $lastChecked){
		$status = get_option('wmlp_license_status');
		if (($status == 'valid') || ($status == 'expired')){
			return true;	
		} else {
			return false;	
		}
	} else {
		$status = wmlp_check_license_online();
		if (($status == 'valid') || ($status == 'expired')){
			return true;	
		} else {
			return false;	
		}
	}
}

function wmpl_authenticate(){
	$allowedPages = array('wmlp_license','wmlp_documentation');
	if (!in_array($_GET['page'],$allowedPages)){
		if (wmpl_check_license()){
			return true;	
		} else {
			echo '<script>window.location = "admin.php?page=wmlp_license&msg=licenseNeeded"</script>';
			exit();
		}
	}
}