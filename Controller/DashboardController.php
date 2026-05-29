<?php
if (!defined('ABSPATH')) die();

class DashboardController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
        add_action('admin_menu', [$this, 'registerMenu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function registerMenu() {
        add_menu_page("WordPress AI", "WP AI", "manage_options", "wp-ai-menu", [$this, 'render'], "dashicons-format-chat", 99);
    }

    public function enqueueScripts($hook) {
        if ($hook !== 'toplevel_page_wp-ai-menu') return;
        
        wp_enqueue_style('wp-ai-style', PLUGIN_URL . 'View/css/style.css', [], '0.01');
        wp_enqueue_script('wp-ai-send', PLUGIN_URL . 'View/js/send.js', [], '0.01', true);
        
        wp_localize_script('wp-ai-send', 'wpAiData', [
            'ajaxurl' => admin_url('admin-ajax.php')
        ]);
    }

    public function render() {
        $queries = $this->model->getAvailableQueries();
        $options_html = '<option value="">-- Zadna data, jen volny dotaz --</option>';
        
        foreach ($queries as $id => $q) {
            $options_html .= '<option value="' . esc_attr($id) . '">' . esc_html($q['name']) . '</option>';
        }
        
        $html = file_get_contents(PLUGIN_DIR . 'View/html/dashboard.html');
        $html = str_replace('{{QUERY_OPTIONS}}', $options_html, $html);

        $nav_html = file_get_contents(PLUGIN_DIR . 'View/html/nav.html');
        $html = str_replace('{{NAVIGATION}}', $nav_html, $html);
        
    }

    
}