<?php
class Wp_TagGen_Main {
	
	public function __construct() {
		add_action('save_post', array($this, 'generate_tags'));
	}
	
	public function generate_tags($post_id) {
		// Get the current post tags
		$posttags = get_the_tags($post_id);

		// If the post has no tags
		if (!$posttags) {
			// Get the post content and strip all HTML tags
			$content = wp_strip_all_tags(apply_filters('the_content', get_post_field('post_content', $post_id)));
            $content = str_replace('&nbsp;', ' ', $content);
			$content = preg_replace('/[\n]{2,}/', '\n', $content);
			
			// Get the WPTagGen options
			$options = get_option('wptaggen_option_name');

			// If the API key exists and the post content is longer than 1000 characters
			if ($options !== false && !empty($options['api_key']) && strlen($content) > 1000) {
				// Include the WPTagGen API
				include(WPTAGGEN_PATH . 'api.php');

				// Create a new WPTagGen instance with the API key and options
				$instance = new Wp_TagGen($options['api_key'], $options['max_tokens'], $options['temperature']);

				// Generate tags string for the post content
				$tags_str = $instance->generateContent($content);
				$tags_str = preg_replace('/[\n]{2,}/', '\n', $tags_str);
				
				// Split the tags string into an array of tags
				$tags = explode("\n", (string)$tags_str);

				// Create a new array of tags organized by tag name
				$new_tags = [];
				foreach ($tags as $value) {
					$str = trim(preg_replace('/\n/', '', trim(preg_replace('/\d{1,2}\./', '', strval($value)))));
					$str = preg_replace('/^nn/', '', $str);
					if ((strlen($str) >= 2) && (strlen($str) <= 20)) {
						$new_tags[] = (string)$str;
					}
				}
				unset($value);
				
				// Set the post tags to the new array of tags
				wp_set_post_tags($post_id, $new_tags, true);
			}
		}
	}
}

$wp_taggen_main = new Wp_TagGen_Main();
