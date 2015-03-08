<?php 

/*
Plugin Name: Disable RSS Feeds
Plugin URI: http://wordpress.org/
Description: Disable RSS Feeds
Author: Pamnard
Author URI: http://context.tips/
Version: 1.0
*/ 

// Disable RSS Feeds

add_action('do_feed', 'theme_disable_feed', 1);
add_action('do_feed_rdf', 'theme_disable_feed', 1);
add_action('do_feed_rss', 'theme_disable_feed', 1);
add_action('do_feed_rss2', 'theme_disable_feed', 1);
add_action('do_feed_atom', 'theme_disable_feed', 1);

function theme_disable_feed() {
	wp_die( __('No feed available, please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}