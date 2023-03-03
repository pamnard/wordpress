<?php
class Wp_TagGen_Setting_Page {
	private $options;
	
	public function __construct() {
    	add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}
	
	public function add_plugin_page() {
		add_menu_page(
			'WP TagGen',
			'WP TagGen',
			'manage_options',
			'wptaggen',
      		array( $this, 'create_admin_page' ),
      		'dashicons-welcome-write-blog'
		);
	}

	public function create_admin_page() {
		// check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}
		$this->options = get_option('wptaggen_option_name');
		// add error/update messages

		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if (isset($_GET['settings-updated'])) {
			// add settings saved message with the class of "updated"
			add_settings_error( 
				'wptaggen_messages', 
				'wptaggen_message', 
				__( 'Settings Saved', 'wptaggen' ), 
				'updated' 
			);
		}

		// show error/update messages
		settings_errors( 'wptaggen_messages' );
		?>
			<div class="wrap">
				<form action="options.php" method="post">
					<?php
					settings_fields( 'wptaggen_option_group' );
					do_settings_sections( 'wptaggen' );
					submit_button( 'Save Settings' );
					?>
				</form>
			</div>
		<?php
	}

	public function wptaggen_section_developers_callback($args) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>">
			<?php esc_html_e( 'Customize everything the way you like.', 'wptaggen' ); ?>
		</p>
		<?php
	}

	public function page_init() {
		// Register a new setting for "wptaggen" page.
		register_setting(
			'wptaggen_option_group', 
			'wptaggen_option_name',
			array( $this, 'sanitize' ) // Sanitize
    	);

		// Register a new section in the "wptaggen" page.
		add_settings_section(
			'wptaggen_section_developers',
			__( 'OpenAI API Settings', 'wptaggen' ), 
			array( $this, 'wptaggen_section_developers_callback' ), // Callback
			'wptaggen'
		);
	
		add_settings_field(
			'api_key', // ID
			__( 'Api key:', 'wptaggen' ), // Title 
			array( $this, 'api_key_callback' ), // Callback
			'wptaggen', // Page
			'wptaggen_section_developers' // Section           
		); 

		add_settings_field(
			'max_tokens', // ID
			__( 'Max tokens:', 'wptaggen' ), // Title 
			array( $this, 'max_tokens_callback' ), // Callback
			'wptaggen', // Page
			'wptaggen_section_developers', // Section
			array( // $args
				'id' => 'max_tokens',
				'default' => 80
			)          
		); 

		add_settings_field(
			'temperature', // ID
			__( 'Temperature:', 'wptaggen' ), // Title 
			array( $this, 'temperature_callback' ), // Callback
			'wptaggen', // Page
			'wptaggen_section_developers', // Section
			array( // $args
				'id' => 'temperature',
				'default' => 0
			)          
		); 
	}
	
	public function api_key_callback($args) {
		printf(
			'<input type="text" class="inputf" id="api_key" name="wptaggen_option_name[api_key]" value="%s" size=60 />&nbsp;<a class="wptaggen_help_link" href="https://beta.openai.com/account/api-keys" target="_blank"><span>?</span> Get Your Api Key</a>',
			isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
		);
	}
	
	public function max_tokens_callback($args) {
    	printf(
			'<input type="text" class="inputf" id="max_tokens" name="wptaggen_option_name[max_tokens]" value="%s" size=20 />',
			isset( $this->options['max_tokens'] ) ? esc_attr( $this->options['max_tokens']) : 80
		);
	}

	public function temperature_callback($args) {
    	printf(
			'<input type="text" class="inputf" id="temperature" name="wptaggen_option_name[temperature]" value="%s" size=20 />',
			isset( $this->options['temperature'] ) ? esc_attr( $this->options['temperature']) : '0'
		);
	}
	
	public function sanitize( $input ) {
		$new_input = array();
		if( isset( $input['api_key'] ) )
			$new_input['api_key'] = sanitize_text_field( $input['api_key'] );

		if( isset( $input['temperature'] ) )
			$new_input['temperature'] = sanitize_text_field( $input['temperature'] );

		if( isset( $input['max_tokens'] ) )
			$new_input['max_tokens'] = absint( $input['max_tokens'] );

		return $new_input;
	}
}

if (is_admin()) {
	$wptaggen_settings_page = new Wp_TagGen_Setting_Page();
}
