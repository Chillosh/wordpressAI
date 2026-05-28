<?php
if (!defined('ABSPATH')) die();

class SenderController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
        add_action('wp_ajax_wp_ai_ask', [$this, 'handleAjaxRequest']);
    }

    public function handleAjaxRequest() {
        if (!isset($_POST['prompt'])) {
            wp_send_json_error('Chybi prompt.');
        }

        $prompt = sanitize_text_field($_POST['prompt']);
        $query_id = isset($_POST['query_id']) ? sanitize_text_field($_POST['query_id']) : '';

        if (!empty($query_id)) {
            $db_data = $this->model->getQueryData($query_id);
            if (!empty($db_data)) {
                $prompt .= "\n\nData z databaze k analyze:\n" . json_encode($db_data, JSON_UNESCAPED_UNICODE);
            }
        }

        $result = $this->model->sendToGemini($prompt);

        if (isset($result['error'])) {
            wp_send_json_error($result['error']);
        } else {
            wp_send_json_success($result['success']);
        }
    }
}