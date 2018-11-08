<?php
/**
 * Plugin Name: Modula PRO
 * Plugin URI: https://wp-modula.com/
 * Description: Modula is one of the best & most creative WordPress gallery plugins. Use it to create a great grid or masonry image gallery.
 * Author: Macho Themes
 * Version: 1.3.1
 * URI: https://www.machothemes.com/
 */

define( 'MODULA_VERSION', '1.3.1' );

/* EDD store definitions */
define( 'MODULA_STORE_URL', 'https://wp-modula.com' );
define( 'MODULA_EDD_ITEM_NAME', 'Modula Grid Gallery' );

if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

function modula_create_db_tables() {
	include_once( WP_PLUGIN_DIR . '/modula/lib/install-db.php' );
	modula_install_db();
}

if ( ! class_exists( "Modula" ) ) {
	class Modula {

		private $loadedData;

		private $defaultValues = array(
			'allFilterLabel'   => 'All',
			'borderColor'      => '#ffffff',
			'borderRadius'     => 0,
			'borderSize'       => 0,
			'captionColor'     => '#ffffff',
			'captionFontSize'  => 14,
			'enableFacebook'   => 'T',
			'enableGplus'      => 'T',
			'enablePinterest'  => 'T',
			'enableTwitter'    => 'T',
			'filterClick'      => 'F',
			'filters'          => '',
			'hasResizedImages' => false,
			'height'           => 800,
			'hoverColor'       => '#000000',
			'hoverEffect'      => 'none',
			'hoverOpacity'     => '50',
			'img_size'         => 500,
			'lightbox'         => 'lightbox2',
			'loadedHSlide'     => 0,
			'loadedRotate'     => 0,
			'loadedScale'      => 100,
			'loadedVSlide'     => 0,
			'margin'           => 10,
			'maxImagesCount'   => 0,
			'randomFactor'     => 50,
			'script'           => '',
			'shadowColor'      => '#ffffff',
			'shadowSize'       => 0,
			'shuffle'          => 'F',
			'socialIconColor'  => '#ffffff',
			'style'            => '',
			'titleFontSize'    => 16,
			'width'            => 100,
			'wp_field_caption' => 'caption',
			'wp_field_title'   => 'title',
		);

		public function __construct() {
			$this->plugin_name = plugin_basename( __FILE__ );
			$this->plugin_url  = plugins_url( '', __FILE__ );
			$this->define_constants();
			$this->define_db_tables();
			$this->define_hover_effects();
			$this->add_gallery_options();
			$this->ModulaDB = $this->create_db_conn();

			add_filter( 'widget_text', 'do_shortcode' );
			add_filter( 'mce_buttons', array( $this, 'editor_button' ) );
			add_filter( 'mce_external_plugins', array( $this, 'register_editor_plugin' ) );

			add_action( 'init', array( $this, 'create_textdomain' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'add_gallery_scripts' ) );

			add_action( 'admin_menu', array( $this, 'add_gallery_admin_menu' ) );

			add_action( 'admin_init', array( $this, 'modula_plugin_updater' ), 0 );
			add_action( 'admin_init', array( $this, 'modula_deactivate_license' ) );
			add_action( 'admin_init', array( $this, 'modula_activate_license' ) );
			add_action( 'admin_init', array( $this, 'modula_register_option' ) );

			add_action( 'wp_ajax_modula_save_gallery', array( $this, 'save_gallery' ) );
			add_action( 'wp_ajax_modula_save_image', array( $this, 'save_image' ) );
			add_action( 'wp_ajax_modula_add_image', array( $this, 'add_image' ) );
			add_action( 'wp_ajax_modula_list_images', array( $this, 'list_images' ) );
			add_action( 'wp_ajax_modula_sort_images', array( $this, 'sort_images' ) );
			add_action( 'wp_ajax_modula_delete_image', array( $this, 'delete_image' ) );
			add_action( 'wp_ajax_modula_assign_filters', array( $this, 'assign_filters' ) );
			add_action( 'wp_ajax_modula_resize_images', array( $this, 'resize_images' ) );
			add_action( 'wp_ajax_modula_delete_gallery', array( $this, 'delete_gallery' ) );
			add_action( 'wp_ajax_modula_clone_gallery', array( $this, 'clone_gallery' ) );
			add_action( 'wp_ajax_modula_create_gallery', array( $this, 'create_gallery' ) );
			add_action( 'wp_ajax_mtg_shortcode_editor', array( $this, 'mtg_shortcode_editor' ) );
			add_action( 'wp_ajax_modula_get_config', array( $this, 'get_config' ) );
			add_action( 'wp_ajax_modula_update_config', array( $this, 'update_config' ) );
			add_action( 'wp_ajax_modula_get_ext_galleries', array( $this, 'get_ext_galleries' ) );
			add_action( 'wp_ajax_modula_do_import_galleries', array( $this, 'do_import_galleries' ) );

			add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
			add_filter( 'plugin_row_meta', array( $this, 'register_links' ), 10, 2 );

			add_shortcode( 'Modula', array( $this, 'gallery_shortcode_handler' ) );
			add_filter( 'the_content', array( $this, 'shortcode_empty_paragraph_fix' ), 99 );
		}

		public function get_ext_galleries() {
			header( "Content-type: application/json" );
			if ( check_admin_referer( "Modula", "Modula" ) ) {
				$res = array( "success" => 0 );

				$source = $_POST['source'];
				if ( $source ) {
					$res['success']   = 1;
					$res['galleries'] = array();

					switch ( $source ) {
						case 'Envira':
							$galleries = get_posts( array(
								                        'post_type'      => 'envira',
								                        'posts_per_page' => 1000,
							                        ) );
							foreach ( $galleries as $g ) {
								$item                = array();
								$item['id']          = $g->ID;
								$item['title']       = $g->post_title;
								$res['galleries'] [] = $item;
							}
							break;
					}
				}

				echo json_encode( $res );
			}
			die();
		}

		public function admin_footer( $text ) {
			global $current_screen;
			if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'modula-pro' ) !== false ) {
				$url  = 'https://wordpress.org/support/plugin/modula-best-grid-gallery/reviews/?rate=5#new-post';
				$text = sprintf( __( 'Please rate <strong>Modula Gallery</strong> <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%s" target="_blank">WordPress.org</a> to help us spread the word. Thank you, on behalf of the Modula team!', 'modula-gallery' ), $url, $url );
			}

			return $text;
		}

		public function do_import_galleries() {
			header( "Content-type: application/json" );
			if ( check_admin_referer( "Modula", "Modula" ) ) {
				$res    = array( "success" => 0 );
				$source = $_POST['source'];
				$ids    = explode( ",", $_POST['ids'] );
				switch ( $source ) {
					case 'Envira':
						foreach ( $ids as $id ) {
							$gallery = get_post( $id );
							$meta    = get_post_meta( $id );
							$data    = unserialize( $meta['_eg_gallery_data'][0] );

							$g   = array(
								'name'             => $data['config']['title'],
								'description'      => 'Imported on ' . date( 'M, d Y' ),
								'margin'           => $data['config']['gutter'],
								'hasResizedImages' => true,
							);
							$gdb = array_merge( $this->defaultValues, $g );

							$saved = $this->ModulaDB->addGallery( $gdb );
							$newId = $this->ModulaDB->getNewGalleryId();

							if ( $newId && count( $data['gallery'] ) ) {
								$images = array();
								//TODO only active images
								foreach ( $data['gallery'] as $item ) {
									$toAdd              = new stdClass();
									$toAdd->imageId     = $this->ModulaDB->getIDbyGUID( $item['src'] );
									$toAdd->title       = $item['title'];
									$toAdd->description = $item['caption'];
									$toAdd->imagePath   = $item['src'];

									$images [] = $toAdd;
								}
								$imgResult = $this->ModulaDB->addImages( $newId, $images );
							}
						}
						$res['success'] = 1;
						break;
				}
				echo json_encode( $res );
			}
			die();
		}

		public function register_links( $links, $file ) {
			$base = plugin_basename( __FILE__ );
			if ( $file == $base ) {
				$links[] = '<a href="admin.php?page=modula-pro-admin" title="Modula Grid Gallery Dashboard">Dashboard</a>';
				$links[] = '<a href="https://twitter.com/MachoThemez" title="@MachoThemez on Twitter">Twitter</a>';
				$links[] = '<a href="https://www.facebook.com/MachoThemes" title="MachoThemes on Facebook">Facebook</a>';
			}

			return $links;
		}

		//Define textdomain
		public function create_textdomain() {
			$plugin_dir = basename( dirname( __FILE__ ) );
			load_plugin_textdomain( 'modula-gallery', false, $plugin_dir . '/lib/languages' );
		}

		public function modula_register_option() {
			register_setting( 'modula_license', 'modula_license_key', array( $this, 'sanitize_license' ) );
		}

		public function sanitize_license( $new ) {
			$old = get_option( 'modula_license_key' );
			if ( $old && $old != $new ) {
				delete_option( 'modula_license_status' ); // new license has been entered, so must reactivate
			}

			return $new;
		}

		public function modula_plugin_updater() {
			// retrieve our license key from the DB
			$license_key = trim( get_option( 'modula_license_key' ) );

			// setup the updater
			$edd_updater = new EDD_SL_Plugin_Updater( MODULA_STORE_URL, __FILE__, array(
				'version'   => MODULA_VERSION,
				'license'   => $license_key,
				'item_name' => MODULA_EDD_ITEM_NAME,
				'author'    => 'MachoThemes',
			) );
		}

		public function modula_activate_license() {
			if ( isset( $_POST['edd_license_activate'] ) ) {
				if ( ! check_admin_referer( 'modula_nonce', 'modula_nonce' ) ) {
					return;
				} // get out if we didn't click the Activate button

				$license = trim( get_option( 'modula_license_key' ) );

				// data to send in our API request
				$api_params = array(
					'edd_action' => 'activate_license',
					'license'    => $license,
					'item_name'  => urlencode( MODULA_EDD_ITEM_NAME ), // the name of our product in EDD
					'url'        => home_url(),
				);

				// Call the custom API.
				$response = wp_remote_post( MODULA_STORE_URL, array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				) );

				// make sure the response came back okay
				if ( is_wp_error( $response ) ) {
					return false;
				}

				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				// $license_data->license will be either "valid" or "invalid"

				update_option( 'modula_license_status', $license_data->license );

			}
		}

		public function modula_deactivate_license() {
			if ( isset( $_POST['edd_license_deactivate'] ) ) {
				if ( ! check_admin_referer( 'modula_nonce', 'modula_nonce' ) ) {
					return;
				}

				// retrieve the license from the database
				$license = trim( get_option( 'modula_license_key' ) );

				// data to send in our API request
				$api_params = array(
					'edd_action' => 'deactivate_license',
					'license'    => $license,
					'item_name'  => urlencode( MODULA_EDD_ITEM_NAME ), // the name of our product in EDD
					'url'        => home_url(),
				);

				// Call the custom API.
				$response = wp_remote_post( MODULA_STORE_URL, array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				) );

				// make sure the response came back okay
				if ( is_wp_error( $response ) ) {
					return false;
				}

				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				// $license_data->license will be either "deactivated" or "failed"
				if ( $license_data->license == 'deactivated' ) {
					delete_option( 'modula_license_status' );
				}

			}
		}


		function define_hover_effects() {
			$this->hoverEffects   = array();
			$this->hoverEffects[] = new ModulaHoverEffect( 'None', 'none', false, false, 0 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Fluid Up', 'fluid-up', true, true, 0 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Pufrobo', 'pufrobo', true, true, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Hide', 'hide', true, true, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Quiet', 'quiet', true, false, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Catinelle', 'catinelle', false, false, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Reflex', 'reflex', true, true, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Curtain', 'curtain', true, false, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Lens', 'lens', true, true, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Appear', 'appear', true, false, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Crafty', 'crafty', true, true, 0 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Seemo', 'seemo', true, false, 4 );
			$this->hoverEffects[] = new ModulaHoverEffect( 'Comodo', 'comodo', true, false, 4 );
		}


		//delete gallery
		function delete_gallery() {
			if ( check_admin_referer( "Modula", "Modula" ) ) {
				$id = intval( $_POST['gid'] );
				$this->ModulaDB->deleteGallery( $id );
			}

			die();
		}

		public function update_config() {
			if ( check_admin_referer( "Modula", "Modula" ) ) {
				$id     = $_POST['id'];
				$config = stripslashes( $_POST['config'] );

				$this->ModulaDB->update_config( $id, $config );
			}

			die();
		}

		public function get_config() {
			if ( check_admin_referer( "Modula", "Modula" ) ) {
				$id = $_POST['id'];

				$data = $this->ModulaDB->getConfig( $id );

				print json_encode( $data );

			}

			die();
		}

		//add gallery

		function create_gallery() {
			if ( check_admin_referer( "Modula", "Modula" ) ) {
				$data                     = $this->defaultValues;
				$data["name"]             = $_POST['name'];
				$data["description"]      = $_POST['description'];
				$data["width"]            = $_POST['width'];
				$data["height"]           = $_POST['height'];
				$data["hoverEffect"]      = $_POST['effect'];
				$data["img_size"]         = intval( $_POST['img_size'] );
				$data["hasResizedImages"] = true;

				$this->ModulaDB->addGallery( $data );
				$id = $this->ModulaDB->getLastGalleryId()->Id;

				if ( $id > 0 && array_key_exists( 'images', $_POST ) && strlen( $_POST['images'] ) ) {
					$enc_images = stripslashes( $_POST["images"] );
					$images     = json_decode( $enc_images );
					ModulaTools::check_and_resize( $images, $data['img_size'] );
					$result = $this->ModulaDB->addImages( $id, $images );
				}

				print $id;
			} else {
				print "Nonce error";
			}

			die();
		}

		//clone gallery
		function clone_gallery() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {
				$sourceId = intval( $_POST['gid'] );
				$g        = $this->ModulaDB->getGalleryById( $sourceId );
				$g->name  .= "(copy)";
				$this->ModulaDB->addGallery( $g );
				$id     = $this->ModulaDB->getNewGalleryId();
				$images = $this->ModulaDB->getImagesByGalleryId( $sourceId );

				foreach ( $images as &$image ) {
					$image->Id  = null;
					$image->gid = $id;
				}

				$this->ModulaDB->addImages( $id, $images );
			}

			die();
		}


		//Define constants
		public function define_constants() {
			if ( ! defined( 'Modula_PLUGIN_BASENAME' ) ) {
				define( 'Modula_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			}

			if ( ! defined( 'Modula_PLUGIN_NAME' ) ) {
				define( 'Modula_PLUGIN_NAME', trim( dirname( Modula_PLUGIN_BASENAME ), '/' ) );
			}

			if ( ! defined( 'Modula_PLUGIN_DIR' ) ) {
				define( 'Modula_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . Modula_PLUGIN_NAME );
			}
		}

		//delete Gallery


		//Define DB tables
		public function define_db_tables() {
			global $wpdb;

			$wpdb->ModulaGalleries = $wpdb->prefix . 'modula';
			$wpdb->ModulaImages    = $wpdb->prefix . 'modula_images';
		}


		public function create_db_conn() {
			require( 'lib/db-class.php' );
			$ModulaDB = ModulaDB::getInstance();

			return $ModulaDB;
		}

		public function editor_button( $buttons ) {
			array_push( $buttons, 'separator', 'mtg_shortcode_editor' );

			return $buttons;
		}

		public function register_editor_plugin( $plugin_array ) {
			$plugin_array['mtg_shortcode_editor'] = plugins_url( '/admin/scripts/editor-plugin.js', __file__ );

			return $plugin_array;
		}

		public function mtg_shortcode_editor() {
			$css_path  = plugins_url( 'assets/css/admin.css', __FILE__ );
			$admin_url = admin_url();

			$galleries = $this->ModulaDB->getGalleries(); //load all galleries

			include 'admin/include/tinymce-galleries.php';
			die();
		}

		//Add gallery options
		public function add_gallery_options() {
			$gallery_options = array(
				'margin'           => '10',
				'defaultSize'      => 'large',
				'width'            => '100%',
				'height'           => '800',
				'lightbox'         => 'lightbox',
				'captionColor'     => '#ffffff',
				'hoverColor'       => '#000000',
				'borderSize'       => 0,
				'borderRadius'     => 0,
				'shadowSize'       => 0,
				//'wp_field_title' => 'title',
				'wp_field_caption' => 'description',
				'wp_field_title'   => 'title',
			);

			add_option( 'modula_options', $gallery_options );
		}

		//Add gallery scripts
		public function add_gallery_scripts() {
			wp_enqueue_script( 'jquery' );

			wp_register_script( 'Modula', plugins_url() . '/modula/scripts/jquery.modula.js', array( 'jquery' ) );
			wp_enqueue_script( 'Modula' );

			wp_register_style( 'Modula_stylesheet', plugins_url() . '/modula/scripts/modula.css' );
			wp_enqueue_style( 'Modula_stylesheet' );

			wp_register_style( 'Effects_stylesheet', plugins_url() . '/modula/scripts/effects.css' );
			wp_enqueue_style( 'Effects_stylesheet' );

			wp_register_script( 'magnific_script', plugins_url() . '/modula/lightbox/magnific/jquery.magnific-popup.min.js', array( 'jquery' ) );
			wp_register_script( 'prettyphoto_script', plugins_url() . '/modula/lightbox/prettyphoto/script.js', array( 'jquery' ) );
			wp_register_script( 'fancybox_script', plugins_url() . '/modula/lightbox/fancybox/jquery.fancybox.min.js', array( 'jquery' ) );
			wp_register_script( 'swipebox_script', plugins_url() . '/modula/lightbox/swipebox/js/jquery.swipebox.min.js', array( 'jquery' ) );
			wp_register_script( 'lightbox2_script', plugins_url() . '/modula/lightbox/lightbox2/js/lightbox.min.js', array( 'jquery' ) );
			wp_register_script( 'lightgallery_script', plugins_url() . '/modula/lightbox/lightgallery/js/lightgallery.min.js', array( 'jquery' ) );

			wp_register_style( 'magnific_stylesheet', plugins_url() . '/modula/lightbox/magnific/magnific-popup.css' );
			wp_register_style( 'prettyphoto_stylesheet', plugins_url() . '/modula/lightbox/prettyphoto/style.css' );
			wp_register_style( 'fancybox_stylesheet', plugins_url() . '/modula/lightbox/fancybox/jquery.fancybox.min.css' );
			wp_register_style( 'swipebox_stylesheet', plugins_url() . '/modula/lightbox/swipebox/css/swipebox.min.css' );
			wp_register_style( 'lightbox2_stylesheet', plugins_url() . '/modula/lightbox/lightbox2/css/lightbox.min.css' );
			wp_register_style( 'lightgallery_stylesheet', plugins_url() . '/modula/lightbox/lightgallery/css/lightgallery.min.css' );


			wp_register_style( 'fontawesome_stylesheet', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css' );
			wp_enqueue_style( 'fontawesome_stylesheet' );
		}

		//Admin Section - register scripts and styles
		public function gallery_admin_init() {
			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}
			//wp_enqueue_script( 'custom-header' );

			wp_enqueue_script( 'jquery' );

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );

			/*wp_register_style('materialize', WP_PLUGIN_URL.'/modula/admin/css/materialize.css');
			wp_enqueue_style('materialize');

			wp_register_style('materialize', WP_PLUGIN_URL.'/modula/admin/css/materialize.min.css');
			wp_enqueue_style('materialize'); */

			wp_register_style( 'styles', plugins_url() . '/modula/admin/css/style.css' );
			wp_enqueue_style( 'styles' );

			wp_register_style( 'effects', plugins_url() . '/modula/scripts/effects.css' );
			wp_enqueue_style( 'effects' );

			wp_register_script( 'materialize', plugins_url() . '/modula/admin/scripts/materialize.js', array( 'jquery' ), false, true );
			wp_register_script( 'modula', plugins_url() . '/modula/admin/scripts/modula-admin.js', array(
				'materialize',
				'jquery',
				'media-upload',
				'thickbox',
			), false, false );

			wp_enqueue_script( 'modula' );

			wp_register_style( 'fontawesome_stylesheet', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css' );
			wp_enqueue_style( 'fontawesome_stylesheet' );

			wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto:400,700,500,300,900' );
			wp_enqueue_style( 'google-fonts' );
			wp_register_style( 'google-icons', '//cdn.materialdesignicons.com/1.1.34/css/materialdesignicons.min.css', array() );
			wp_enqueue_style( 'google-icons' );

			wp_enqueue_style( 'thickbox' );

			$tg_db_version = '1.0';
			$installed_ver = get_option( "Modula_db_version" );


			if ( $installed_ver != $tg_db_version ) {
				modula_create_db_tables();
				update_option( "Modula_db_version", $tg_db_version );
			}
		}


		//Create Admin Menu
		public function add_gallery_admin_menu() {
			$overview = add_menu_page( 'Modula', 'Modula', 'edit_posts', 'modula-pro-admin', array(
				$this,
				'add_overview',
			), WP_PLUGIN_URL . '/modula/admin/icon.png' );

			$license      = add_submenu_page( 'modula-pro-admin', __( 'Modula >> License', 'Modula' ), __( 'License', 'Modula' ), 'edit_posts', 'modula-pro-license', array(
				$this,
				'license',
			) );
			$add_gallery  = add_submenu_page( 'modula-pro-admin', __( 'Modula >> Add Gallery', 'Modula' ), __( 'Add Gallery', 'Modula' ), 'edit_posts', 'modula-pro-add', array(
				$this,
				'add_gallery',
			) );
			$edit_gallery = add_submenu_page( 'modula-pro-admin', __( 'Modula >> Edit Gallery', 'Modula' ), __( 'Edit Gallery', 'Modula' ), 'edit_posts', 'modula-pro-edit', array(
				$this,
				'edit_gallery',
			) );
			if ( class_exists( "Envira_Gallery_Lite" ) || class_exists( "Envira_Gallery" ) || class_exists( 'nggGallery' ) ) :
				$import = add_submenu_page( 'modula-pro-admin', __( 'Modula >> Import', 'Modula' ), __( 'Import galleries', 'Modula' ), 'edit_posts', 'modula-pro-gallery-import', array(
					$this,
					'import_galleries',
				) );
			endif;

			// add_action('admin_print_styles-'.$add_gallery, array($this, 'Mikado_admin_style_load')); 
			// add_action('admin_print_styles-'.$edit_gallery, array($this, 'Mikado_admin_style_load'));

			//add_action('load-'.$tutorial, array($this, 'gallery_admin_init'));
			add_action( 'load-' . $license, array( $this, 'gallery_admin_init' ) );
			add_action( 'load-' . $overview, array( $this, 'gallery_admin_init' ) );
			add_action( 'load-' . $add_gallery, array( $this, 'gallery_admin_init' ) );
			add_action( 'load-' . $edit_gallery, array( $this, 'gallery_admin_init' ) );
			if ( class_exists( "Envira_Gallery_Lite" ) || class_exists( "Envira_Gallery" ) || class_exists( 'nggGallery' ) ) :
				add_action( 'load-' . $import, array( $this, 'gallery_admin_init' ), 999 );
			endif;
		}

		//Create Admin Pages
		public function add_overview() {
			include( "admin/overview.php" );
		}

		public function tutorial() {
			include( "admin/tutorial.php" );
		}

		public function license() {
			$license = get_option( 'modula_license_key' );
			$status  = get_option( 'modula_license_status' );

			include( "admin/license.php" );
		}

		public function add_gallery() {
			include( "admin/add-gallery.php" );
		}

		public function import_galleries() {
			include( "admin/import.php" );
		}

		public function delete_image() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {
				foreach ( explode( ",", $_POST["id"] ) as $id ) {
					$this->ModulaDB->deleteImage( intval( $id ) );
				}
			}
			die();
		}

		public function assign_filters() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {
				foreach ( explode( ",", $_POST["id"] ) as $id ) {
					$result = $this->ModulaDB->editImage( $id, array( "filters" => $_POST["filters"] ) );
				}
			}
			die();
		}

		public function resize_images() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {
				foreach ( explode( ",", $_POST["id"] ) as $data ) {
					$data  = explode( "-", $data );
					$image = wp_get_attachment_image_src( $data[1], $_POST['size'] );

					if ( ! empty( $image ) ) {
						$this->ModulaDB->editImage( $data[0], array( "imagePath" => $image[0] ) );
					}
				}
			}
			die();
		}

		public function add_image() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {
				$gid        = intval( $_POST['galleryId'] );
				$enc_images = stripslashes( $_POST["enc_images"] );
				$images     = json_decode( $enc_images );

				$result = $this->ModulaDB->addImages( $gid, $images );

				header( "Content-type: application/json" );
				if ( $result === false ) {
					print "{\"success\":false}";
				} else {
					print "{\"success\":true}";
				}
			}
			die();
		}

		public function sort_images() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {
				$result = $this->ModulaDB->sortImages( explode( ',', $_POST['ids'] ) );

				header( "Content-type: application/json" );
				if ( $result === false ) {
					print "{\"success\":false}";
				} else {
					print "{\"success\":true}";
				}
			}
			die();
		}

		public function save_image() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {
				$result = false;
				// $type = $_POST['type'];			
				$imageUrl     = esc_url_raw( $_POST['img_url'] );
				$imageCaption = stripslashes($_POST['description']);
				$filters      = $_POST['filters'];
				$title        = stripslashes($_POST['title']);
				$target       = $_POST['target'];
				// $lightbox = $_POST['lightbox'];
				$link      = isset( $_POST['link'] ) ? esc_url_raw( $_POST['link'] ) : null;
				$imageId   = intval( $_POST['img_id'] );
				$sortOrder = intval( $_POST['sortOrder'] );
				$halign    = $_POST['halign'];
				$valign    = $_POST['valign'];

				// if($zoom == "T")
				// 	$link = null;
				$data = array(
					"target"      => $target,
					//"lightbox" => $lightbox,
					"link"        => $link,
					"imageId"     => $imageId,
					"description" => $imageCaption,
					'title'       => $title,
					"filters"     => $filters,
					"halign"      => $halign,
					"valign"      => $valign,
					"sortOrder"   => $sortOrder,
				);

				if ( ! empty( $_POST['id'] ) ) {
					$imageId = intval( $_POST['id'] );
					$result  = $this->ModulaDB->editImage( $imageId, $data );
				} else {
					$data["gid"] = intval( $_POST['galleryId'] );
					$result      = $this->ModulaDB->addFullImage( $data );
				}

				header( "Content-type: application/json" );

				if ( $result === false ) {
					print "{\"success\":false}";
				} else {
					print "{\"success\":true}";
				}

			}
			die();
		}

		public function list_images() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {
				$gid     = intval( $_POST["gid"] );
				$gallery = $this->ModulaDB->getGalleryById( $gid );

				$imageResults = $this->ModulaDB->getImagesByGalleryId( $gid );

				include( 'admin/include/image-list.php' );
			}
			die();
		}

		private function checkboxVal( $field ) {
			if ( isset( $_POST[ $field ] ) ) //return 'checked';
			{
				return 'T';
			}

			//return '';
			return 'F';
		}

		public function save_gallery() {
			if ( check_admin_referer( 'Modula', 'Modula' ) ) {

				$galleryName        = stripslashes( $_POST['tg_name'] );
				$galleryDescription = stripslashes( $_POST['tg_description'] );
				$slug               = strtolower( str_replace( " ", "", $galleryName ) );
				$margin             = intval( $_POST['tg_margin'] );
				$filterClick        = $this->checkboxVal( 'tg_filterClick' );
				$allFilterLabel     = $_POST['tg_allFilterLabel'];
				$shuffle            = $this->checkboxVal( 'tg_shuffle' );
				$width              = $_POST['tg_width'];
				$height             = $_POST['tg_height'];
				// $keepArea = $_POST['keepArea'];
				$enableTwitter   = $this->checkboxVal( 'tg_enableTwitter' );
				$enableFacebook  = $this->checkboxVal( 'tg_enableFacebook' );
				$enableGplus     = $this->checkboxVal( 'tg_enableGplus' );
				$enablePinterest = $this->checkboxVal( 'tg_enablePinterest' );
				$lightbox        = $_POST['tg_lightbox'];
				// $blank = $_POST['blank'];
				$wp_field_caption = $_POST['tg_wp_field_caption'];
				$wp_field_title   = $_POST['tg_wp_field_title'];
				$filters          = $_POST['tg_filter'];
				// $scrollEffect = $_POST['scrollEffect'];
				$captionColor    = $_POST['tg_captionColor'];
				$hoverColor      = $_POST['tg_hoverColor'];
				$hoverOpacity    = intval( $_POST['tg_hoverOpacity'] );
				$borderSize      = intval( $_POST['tg_borderSize'] );
				$loadedScale     = intval( $_POST['tg_loadedScale'] );
				$loadedRotate    = intval( $_POST['tg_loadedRotate'] );
				$loadedVSlide    = intval( $_POST['tg_loadedVSlide'] );
				$loadedHSlide    = intval( $_POST['tg_loadedHSlide'] );
				$socialIconColor = $_POST['tg_socialIconColor'];
				$hoverEffect     = $_POST['tg_hoverEffect'];
				$titleFontSize   = intval( $_POST['tg_titleFontSize'] );
				$captionFontSize = intval( $_POST['tg_captionFontSize'] );
				$borderColor     = $_POST['tg_borderColor'];
				$borderRadius    = intval( $_POST['tg_borderRadius'] );
				$shadowColor     = $_POST['tg_shadowColor'];
				$shadowSize      = intval( $_POST['tg_shadowSize'] );
				// $backgroundColor = $_POST['tg_backgroundColor'];
				$style  = $_POST['tg_style'];
				$script = $_POST['tg_script'];

				$id = isset( $_POST['ftg_gallery_edit'] ) ? intval( $_POST['ftg_gallery_edit'] ) : 0;

				$data = array(
					'name'             => $galleryName,
					'slug'             => $slug,
					'description'      => $galleryDescription,
					'lightbox'         => $lightbox,
					'img_size'         => intval( $_POST['tg_img_size'] ),
					'hasResizedImages' => true,
					// 'blank' => $blank,
					'wp_field_caption' => $wp_field_caption,
					'wp_field_title'   => $wp_field_title,
					'margin'           => $margin,
					'randomFactor'     => $_POST['tg_randomFactor'],
					'filterClick'      => $filterClick,
					'allFilterLabel'   => $allFilterLabel,
					'shuffle'          => $shuffle,
					'enableTwitter'    => $enableTwitter,
					'enableFacebook'   => $enableFacebook,
					'enableGplus'      => $enableGplus,
					'enablePinterest'  => $enablePinterest,
					'captionColor'     => $captionColor,
					'hoverColor'       => $hoverColor,
					'hoverOpacity'     => $hoverOpacity,
					'hoverEffect'      => $hoverEffect,
					'filters'          => $filters,
					'borderSize'       => $borderSize,
					'loadedScale'      => $loadedScale,
					'loadedHSlide'     => $loadedHSlide,
					'loadedVSlide'     => $loadedVSlide,
					'loadedRotate'     => $loadedRotate,
					'socialIconColor'  => $socialIconColor,
					'captionFontSize'  => $captionFontSize,
					'titleFontSize'    => $titleFontSize,
					'borderColor'      => $borderColor,
					// 'backgroundColor' => $backgroundColor,
					'borderRadius'     => $borderRadius,
					'shadowSize'       => $shadowSize,
					'shadowColor'      => $shadowColor,
					'width'            => $width,
					'height'           => $height,
					//'keepArea' => $keepArea,
					'maxImagesCount'   => intval( $_POST['tg_maxImagesCount'] ),
					'style'            => $style,
					'script'           => $script
					// 'scrollEffect' => $scrollEffect
				);

				header( "Content-type: application/json" );
				if ( $id > 0 ) {
					$result = $this->ModulaDB->editGallery( $id, $data );
					if ( intval( $this->loadedData->img_size ) != $data['img_size'] ) {
						$images = $this->ModulaDB->getImagesByGalleryId( $id );
						$images = ModulaTools::check_and_resize( $images, $data['img_size'] );

						foreach ( $images as $img ) {
							$this->ModulaDB->editImage( $img->Id, (array) $img );
						}
					}

					$this->loadedData = $this->ModulaDB->getGalleryById( $id );
				} else {
					$result = $this->ModulaDB->addGallery( $data );
					$id     = $this->ModulaDB->getNewGalleryId();
				}

				if ( $result ) {
					print "{\"success\":true,\"id\":" . $id . "}";
				} else {
					print "{\"success\":false}";
				}
			}
			die();
		}

		public function edit_gallery() {
			$this->loadedData = $this->ModulaDB->getGalleryById( intval( $_GET['galleryId'] ) );
			foreach ( $this->defaultValues as $k => $v ) {
				if ( ! isset( $this->loadedData->$k ) ) {
					$this->loadedData->$k = $v;
				}
			}
			$modula_fields      = $this->fields;
			$modula_parent_page = "dashboard";

			include( "admin/edit-gallery.php" );
		}

		public function add_images() {
			include( "admin/add-images.php" );
		}

		public function list_thumbnail_sizes() {
			global $_wp_additional_image_sizes;
			$sizes = array();
			foreach ( get_intermediate_image_sizes() as $s ) {
				$sizes[ $s ] = array( 0, 0 );
				if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
					$sizes[ $s ][0] = get_option( $s . '_size_w' );
					$sizes[ $s ][1] = get_option( $s . '_size_h' );
				} else {
					if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
						$sizes[ $s ] = array(
							$_wp_additional_image_sizes[ $s ]['width'],
							$_wp_additional_image_sizes[ $s ]['height'],
						);
					}
				}
			}

			return $sizes;
		}

		public function gallery_shortcode_handler( $atts ) {
			require_once( 'lib/gallery-class.php' );
			global $Modula;

			if ( class_exists( 'ModulaFE' ) ) {
				$Modula = new ModulaFE( $this->ModulaDB, $atts['id'], $this->defaultValues );


				$settings = $Modula->getGallery();
				switch ( $settings->lightbox ) {
					default:
					case "magnific":
						wp_enqueue_style( 'magnific_stylesheet' );
						wp_enqueue_script( 'magnific_script' );
						break;
					case "prettyphoto":
						wp_enqueue_style( 'prettyphoto_stylesheet' );
						wp_enqueue_script( 'prettyphoto_script' );
						break;
					case "fancybox":
						wp_enqueue_style( 'fancybox_stylesheet' );
						wp_enqueue_script( 'fancybox_script' );
						break;
					case "swipebox":
						wp_enqueue_style( 'swipebox_stylesheet' );
						wp_enqueue_script( 'swipebox_script' );
						break;
					case "lightbox2":
						wp_enqueue_style( 'lightbox2_stylesheet' );
						wp_enqueue_script( 'lightbox2_script' );
						break;
					case "lightgallery":
						wp_enqueue_style( 'lightgallery_stylesheet' );
						wp_enqueue_script( 'lightgallery_script' );
						break;
				}

				return $Modula->render();
			} else {
				return "Gallery not found.";
			}
		}

		public function shortcode_empty_paragraph_fix( $content ) {

	        $array = array (
	            '<p>[Modula' => '[Modula' ,
	            '<p>[/Modula' => '[/Modula',
	            'Modula]</p>' => 'Modula]',
	            'Modula]<br />' => 'Modula]'
	        );

	        $content = strtr( $content, $array );

		    return $content;
		}


		/* example */

		var $fields = array(

			"General"              => array(
				"icon"   => "mdi-action-settings",
				"fields" => array(
					"name"           => array(
						"name"        => "Name",
						"type"        => "text",
						"description" => "Name of the gallery, for internal use.",
						"excludeFrom" => array(),
					),
					"description"    => array(
						"name"        => "Description",
						"type"        => "text",
						"description" => "This description is for internal use.",
						"excludeFrom" => array(),
					),
					"width"          => array(
						"name"        => "Width",
						"type"        => "text",
						"description" => "Width of the gallery (i.e.: 100% or 500px)",
						"mu"          => "%",
						"excludeFrom" => array(),
					),
					"height"         => array(
						"name"        => "Height",
						"type"        => "number",
						"description" => "Height of the gallery in pixels",
						"mu"          => "px",
						"excludeFrom" => array(),
					),
					"img_size"       => array(
						"name"        => "Minimum image size",
						"type"        => "number",
						"description" => "Minimum width or height of the images",
						"mu"          => "px",
						"excludeFrom" => array(),
					),
					"margin"         => array(
						"name"        => "Margin",
						"type"        => "number",
						"description" => "Margin between images",
						"mu"          => "px",
						"excludeFrom" => array(),
					),
					"randomFactor"   => array(
						"name"        => "Random factor",
						"type"        => "slider",
						"description" => "",
						"min"         => 0,
						"max"         => 100,
						"mu"          => "%",
						"default"     => 20,
						"excludeFrom" => array(),
					),
					"filters"        => array(
						"name"        => "Filters",
						"type"        => "filter",
						"description" => "Add Filters",
						"excludeFrom" => array(),
					),
					"filterClick"    => array(
						"name"        => "Reload Page on filter click",
						"type"        => "toggle",
						"description" => "Turn this feature ON if you want to use filters with most lightboxes",
						"excludeFrom" => array(),
					),
					"allFilterLabel" => array(
						"name"        => "Text for 'All' filter",
						"type"        => "text",
						"description" => "Write here the label for the 'All' filter",
						"default"     => "All",
						"excludeFrom" => array(),
					),
					"lightbox"       => array(
						"name"        => "Lightbox &amp; Links",
						"type"        => "select",
						"description" => "Define here what happens when user click on the images.",
						"values"      => array(
							"Link"       => array( "|No link", "direct|Direct link to image", "|Attachment page" ),
							"Lightboxes" => array(
								"lightgallery|LightGallery",
								"magnific|Magnific popup",
								"prettyphoto|PrettyPhoto",
								"fancybox|FancyBox",
								"swipebox|SwipeBox",
								"lightbox2|Lightbox",
							),
						),
						"excludeFrom" => array(),
					),
					"shuffle"        => array(
						"name"        => "Shuffle images",
						"type"        => "toggle",
						"default"     => "T",
						"description" => "Choose 'Yes' if you want to shuffle the gallery at each page reload",
						"excludeFrom" => array(),
					),
					"maxImagesCount" => array(
						"name"        => "Max images count",
						"type"        => "number",
						"default"     => "0",
						"description" => "Use '0' to show all images",
						"excludeFrom" => array(),
					),
				),
			),
			"Captions"             => array(
				"icon"   => "mdi mdi-comment-text-outline",
				"fields" => array(
					"captionColor"     => array(
						"name"        => "Caption  color",
						"type"        => "color",
						"description" => "Color of the caption.",
						"default"     => "#ffffff",
						"excludeFrom" => array(),
					),
					"wp_field_caption" => array(
						"name"        => "WordPress caption field",
						"type"        => "select",
						"description" => "WordPress field used for captions. <strong>This field is used ONLY when images are added to the gallery, </strong> however, if you want to ignore captions just set it to '<i>Don't use captions</i>'.",
						"values"      => array(
							"Field" => array(
								"none|Don't use captions",
								"title|Title",
								"caption|Caption",
								"description|Description",
							),
						),
						"excludeFrom" => array( "shortcode" ),
					),
					"wp_field_title"   => array(
						"name"        => "WordPress title field",
						"type"        => "select",
						"description" => "WordPress field used for titles. <strong>This field is used ONLY when images are added to the gallery, </strong> however, if you want to ignore titles just set it to '<i>Don't use titles</i>'.",
						"values"      => array(
							"Field" => array( "none|Don't use titles", "title|Title", "description|Description" ),
						),
						"excludeFrom" => array( "shortcode" ),
					),
					"captionFontSize"  => array(
						"name"        => "Caption Font Size",
						"type"        => "number",
						"description" => "",
						"mu"          => "px",
						"excludeFrom" => array(),
					),
					"titleFontSize"    => array(
						"name"        => "Title Font Size",
						"type"        => "number",
						"description" => "",
						"mu"          => "px",
						"excludeFrom" => array(),
					),
				),

			),
			"Social"               => array(
				"icon"   => "mdi mdi-link-variant",
				"fields" => array(
					"enableTwitter"   => array(
						"name"        => "Add Twitter icon",
						"type"        => "toggle",
						"default"     => "T",
						"description" => "Enable Twitter Sharing",
						"excludeFrom" => array(),
					),
					"enableFacebook"  => array(
						"name"        => "Add Facebook icon",
						"type"        => "toggle",
						"default"     => "T",
						"description" => "Enable Facebook Sharing",
						"excludeFrom" => array(),
					),
					"enableGplus"     => array(
						"name"        => "Add Google Plus icon",
						"type"        => "toggle",
						"default"     => "T",
						"description" => "Enable Google Plus Sharing",
						"excludeFrom" => array(),
					),
					"enablePinterest" => array(
						"name"        => "Add Pinterest  icon",
						"type"        => "toggle",
						"default"     => "T",
						"description" => "Enable Pinterest Sharing",
						"excludeFrom" => array(),
					),
					"socialIconColor" => array(
						"name"        => "Color of social sharing icons",
						"type"        => "color",
						"description" => "Set the color of the social sharing icons",
						"default"     => "#ffffff",
						"excludeFrom" => array(),
					),
				),

			),
			"Image loaded effects" => array(
				"icon"   => "mdi mdi-reload",
				"fields" => array(
					"loadedScale"  => array(
						"name"        => "Scale",
						"description" => "",
						"type"        => "slider",
						"min"         => 0,
						"max"         => 200,
						"mu"          => "%",
						"default"     => 100,
						"excludeFrom" => array(),
					),
					"loadedRotate" => array(
						"name"        => "Rotate",
						"description" => "",
						"type"        => "slider",
						"min"         => - 180,
						"max"         => 180,
						"default"     => 0,
						"mu"          => "deg",
						"excludeFrom" => array(),
					),
					"loadedHSlide" => array(
						"name"        => "Horizontal slide",
						"description" => "",
						"type"        => "slider",
						"min"         => - 100,
						"max"         => 100,
						"mu"          => "px",
						"default"     => 0,
						"excludeFrom" => array(),
					),
					"loadedVSlide" => array(
						"name"        => "Vertical slide",
						"description" => "",
						"type"        => "slider",
						"min"         => - 100,
						"max"         => 100,
						"mu"          => "px",
						"default"     => 0,
						"excludeFrom" => array(),
					),

				),
			),
			"Hover effect"         => array(
				"icon"   => "mdi mdi-blur",
				"fields" => array(
					"Effect"       => array(
						"name"        => "Effect",
						"description" => "Select the effect you want to give your image",
						"type"        => "hover-effect",
						"excludeFrom" => array(),
					),
					"hoverColor"   => array(
						"name"        => "Hover Color",
						"description" => "Hover Color",
						"type"        => "color",
						"default"     => "#ffffff",
						"excludeFrom" => array(),
					),
					"hoverOpacity" => array(
						"name"        => "Hover opacity",
						"description" => "Opacity on hover",
						"type"        => "slider",
						"min"         => 5,
						"max"         => 100,
						"mu"          => "%",
						"default"     => "",
						"excludeFrom" => array(),
					),

				),
			),
			"Style"                => array(
				"icon"   => "mdi mdi-format-paint",
				"fields" => array(
					"borderSize"   => array(
						"name"        => "Border Size",
						"type"        => "slider",
						"description" => "",
						"mu"          => "px",
						"min"         => 0,
						"max"         => 10,
						"default"     => 0,
						"excludeFrom" => array(),
					),
					"borderRadius" => array(
						"name"        => "Border Radius",
						"type"        => "slider",
						"description" => "",
						"mu"          => "px",
						"min"         => 0,
						"max"         => 100,
						"default"     => 0,
						"excludeFrom" => array(),
					),
					"borderColor"  => array(
						"name"        => "Border Color",
						"type"        => "color",
						"description" => "",
						"default"     => "#ffffff",
						"excludeFrom" => array(),
					),
					"shadowSize"   => array(
						"name"        => "Shadow Size",
						"type"        => "slider",
						"description" => "",
						"mu"          => "px",
						"min"         => 0,
						"max"         => 20,
						"default"     => 0,
						"excludeFrom" => array(),
					),
					"shadowColor"  => array(
						"name"        => "Shadow Color",
						"type"        => "color",
						"description" => "",
						"default"     => "#ffffff",
						"excludeFrom" => array(),
					),

				),
			),
			"Customizations"       => array(
				"icon"   => "mdi mdi-puzzle",
				"fields" => array(
					"script" => array(
						"name"        => "Custom scripts",
						"type"        => "textarea",
						"description" => "This script will be called after the gallery initialization. Useful for custom lightboxes.
	                        <br />
	                        <br />
	                        <strong>Write just the code without using the &lt;script&gt;&lt;/script&gt; tags</strong>",
						"excludeFrom" => array( "shortcode" ),
					),
					"style"  => array(
						"name"        => "Custom css",
						"type"        => "textarea",
						"description" => "<strong>Write just the code without using the &lt;style&gt;&lt;/style&gt; tags</strong>",
						"excludeFrom" => array( "shortcode" ),
					),
				),
			),

		);
	}

	class ModulaHoverEffect {

		var $name;
		var $code;
		var $allowTitle;
		var $allowSubtitle;
		var $maxSocial;

		public function __construct( $name, $code, $allowTitle, $allowSubtitle, $maxSocial ) {
			$this->name          = $name;
			$this->code          = $code;
			$this->allowTitle    = $allowTitle;
			$this->allowSubtitle = $allowSubtitle;
			$this->maxSocial     = $maxSocial;
		}
	}
}

class ModulaTools {

	public static function get_image_size_links( $id ) {
		$result  = array();
		$sizes   = get_intermediate_image_sizes();
		$sizes[] = 'full';

		foreach ( $sizes as $size ) {
			$image = wp_get_attachment_image_src( $id, $size );

			if ( ! empty( $image ) && ( true == $image[3] || 'full' == $size ) ) {
				$result["$image[1]x$image[2]"] = $image[0];
			}
		}

		return $result;
	}

	public static function resize_image( $id, $img_size ) {
		$file   = get_attached_file( $id );
		$editor = wp_get_image_editor( $file );
		$size   = $editor->get_size();
		if ( $size["width"] > $size["height"] ) {
			$editor->resize( 10000, $img_size );
		} else {
			$editor->resize( $img_size, 10000 );
		}
		$path_parts = pathinfo( $file );
		$filename   = $path_parts['dirname'] . "/" . $path_parts['filename'] . "-" . $img_size . "x" . $img_size . "." . $path_parts["extension"];

		if ( ! file_exists( $filename ) ) {
			$editor->save( $filename );
		}

		return basename( $filename );
	}

	public static function check_and_resize( &$images, $size ) {
		foreach ( $images as &$img ) {
			$metadata = wp_get_attachment_metadata( $img->imageId );

			$wpdata   = get_post( $img->imageId );
			$baseurl  = str_replace( basename( $wpdata->guid ), "", $wpdata->guid );
			$res_name = ModulaTools::resize_image( $img->imageId, $size );

			if ( ! ( array_key_exists( "image_meta", $metadata ) && array_key_exists( "resized_images", $metadata["image_meta"] ) && in_array( $size . "x" . $size, $metadata["image_meta"]["resized_images"] ) ) ) {
				if ( isset( $metadata['image_meta'] ) ) {
					$md                                         = $size . 'x' . $size;
					$metadata['image_meta']['resized_images'][] = $md;
					wp_update_attachment_metadata( $img->imageId, $metadata );
				}
			}

			$img->imagePath = $baseurl . $res_name;
		}

		return $images;
	}
}

if ( class_exists( "Modula" ) ) {
	global $ob_Modula;
	$ob_Modula = new Modula();
}
?>