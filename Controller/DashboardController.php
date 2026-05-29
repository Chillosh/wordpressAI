<?php
if (!defined('ABSPATH')) die();

class DashboardController {
    private $model;
    private $renderController;

    public function __construct($model, $renderController) {
        $this->model = $model;
        $this->renderController = $renderController;
        add_action('admin_menu', [$this, 'registerMenu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }
    
    public function registerMenu() {
        add_menu_page("WordPress AI", "WP AI", "manage_options", "wp-ai-menu", [$this->renderController, 'render'], "dashicons-format-chat", 99);
    }

    public function enqueueScripts($hook) {
        if ($hook !== 'toplevel_page_wp-ai-menu') return;
        
        wp_enqueue_style('wp-ai-style', PLUGIN_URL . 'View/css/style.css', [], '0.01');
        wp_enqueue_script('wp-ai-send', PLUGIN_URL . 'View/js/send.js', [], '0.01', true);
        
        wp_localize_script('wp-ai-send', 'wpAiData', [
            'ajaxurl' => admin_url('admin-ajax.php')
        ]);
    }
}