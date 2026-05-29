<?php
if (!defined('ABSPATH')) die();

class AiModel {
    private $wpdb;
    private $queries;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->queries = require PLUGIN_DIR . 'includes/queries.php';
    }

    public function getAvailableQueries() {
        return $this->queries;
    }

    public function getQueryData($query_id) {
        if (!isset($this->queries[$query_id])) {
            return [];
        }
        
        $sql = $this->queries[$query_id]['sql'];
        
        $sql = str_replace('{prefix}', $this->wpdb->prefix, $sql);
        
        $sql = str_replace('{interval}', '1 YEAR', $sql);
        
        return $this->wpdb->get_results($sql, ARRAY_A);
    }

    public function sendToGemini($prompt) {
        $api_key = 'YOUR_API_KEY_HERE';
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $api_key;
        
        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $response = wp_remote_post($url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode($body),
            'timeout' => 15,
            'sslverify' => false
        ]);

        if (is_wp_error($response)) {
            return ['error' => 'Chyba sítě WP: ' . $response->get_error_message()];
        }

        $response_body = wp_remote_retrieve_body($response);
        $data = json_decode($response_body, true);

        if (isset($data['error']['message'])) {
            return ['error' => 'Google API hlásí: ' . $data['error']['message']];
        }

        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return ['success' => $data['candidates'][0]['content']['parts'][0]['text']];
        }

        return ['error' => 'Neočekávaná odpověď: ' . print_r($data, true)];
    }
}