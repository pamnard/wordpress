<?php
class Wp_TagGen_Loader {
	// plugin version
	const VERSION = '1.1';

	// plugin prefix
	const PREFIX = 'wptaggen_';

	// plugin options
	private $options;

	// plugin default options
	private $default_options = array();

	// plugin text domain
	const TEXT_DOMAIN = 'wp-taggen';

	// plugin constructor
	public function __construct() {

		// register activation and deactivation hooks
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	
		// load admin settings
		include( WPTAGGEN_PATH . 'settings.php' );
		include( WPTAGGEN_PATH . 'main.php' );
		
		// load options
		$this->options = wp_parse_args( get_option( self::PREFIX . 'options', array() ), $this->default_options );
	}

	// plugin deactivation function
	public function deactivate() {
		// delete options
		delete_option( 'wptaggen_activation_redirect' );
		delete_option( self::PREFIX . 'options' );
	}
}

// create plugin instance
$wp_taggen_loader = new Wp_TagGen_Loader();