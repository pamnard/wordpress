<?php
class Wp_TagGen {
    
    private $api_key;
    private $max_tokens;
    private $temperature;
    
    public function __construct($api_key = '', $max_tokens = 100, $temperature = 0) {
        $this->api_key = $api_key;         // sets the API key for OpenAI
        $this->max_tokens = $max_tokens;   // sets the maximum number of tokens to generate
        $this->temperature = $temperature; // sets the "temperature" parameter for generating more or less diverse results
    }
    
    public function generateContent($article_str) {
        $post_fields = array();
        
        // Prompt for the OpenAI API to generate tags based on the input article
        $myprompt = 'Give me a numbered list of up to 10 tags for this text:\n\n '.$article_str;
        
        // Set the parameters for generating the tags using the OpenAI API
        $post_fields['messages'] = array(array("role" => "user", "content" => $myprompt));
        $post_fields['model'] = 'gpt-3.5-turbo';
        $post_fields['max_tokens'] = intval($this->max_tokens);
        $post_fields['temperature'] = intval($this->temperature);
        $post_fields['top_p'] = 0.5;
        $post_fields['frequency_penalty'] = 0.8;
        $post_fields['presence_penalty'] = 0.0;
        
        // Convert the post fields to JSON
        $post_fields = json_encode($post_fields);
        
        // Set up the arguments for the WP remote post request to the OpenAI API
        $body = $post_fields;
        $args = array(
            'body' => $body,
            'headers' => array(
                'content-type' => 'application/json',
                'Authorization' => 'Bearer '.$this->api_key
            ),
            'timeout' => '200',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'data_format' => 'body',
        );
        
        // Send the WP remote post request to the OpenAI API
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', $args);
        
        // Process the response from the OpenAI API
        if (!is_wp_error($response)) {
            $response_decoded = json_decode(wp_remote_retrieve_body($response), true);
            
            // If there is an error in the response, return the error message
            if ($response_decoded['error']) {
                $texttoreturn = $response_decoded['error']['message'];
            } else {
                // Otherwise, return the generated text
                $texttoreturn = $response_decoded['choices'][0]['message']['content'];
                $texttoreturn = trim($texttoreturn);
            }
            
            return $texttoreturn;
        } else {
            // If there is an error with the WP remote post request, return the error message
            $error_message = $response->get_error_message();
            return $texttoreturn = $error_message;
        }
    }
}
