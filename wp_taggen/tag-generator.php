<?php
/*
Plugin Name: WP TagGen
Plugin URI: https://external.software/wp-taggen
Description: WP TagGen is a plugin for WordPress that allows you to generate high-quality, unique tags for your website using the power of artificial intelligence. The plugin utilizes OpenAI's GPT-3 (Generative Pre-trained Transformer 3) language model, which is one of the most advanced AI language models available.
Author: Pamnard
Version: 1.1
Author URI: https://external.software
*/

// Define the path to your plugin's directory
define( 'WPTAGGEN_PATH', trailingslashit(plugin_dir_path( __FILE__ )) );
define( 'WPTAGGEN_TEXT_DOMAIN', 'wp-taggen');

// register activation and deactivation hooks
register_activation_hook( __FILE__, 'wptaggen_activate' );
add_action( 'admin_init', 'wptaggen_activation_redirect' );

function wptaggen_activate() {
    add_option('wptaggen_activation_redirect', true);
}

function wptaggen_activation_redirect() {
    // Make sure it's the correct user
    if (get_option('wptaggen_activation_redirect', false)) {
        // Make sure we don't redirect again after this one
        delete_option('wptaggen_activation_redirect');
        wp_safe_redirect(admin_url('admin.php?page=wptaggen'));
        exit;
    }
}

// Use the path to include a PHP class
include( WPTAGGEN_PATH . 'loader.php' );