<?php
if (!defined('ABSPATH')) die();

class RenderController 
{
    private $model;
    private $dir;
    private $files;

    public function __construct($model, $dir = PLUGIN_DIR . 'View/html/') {
        $this->model = $model;
        $this->dir = $dir;
        
        if (is_dir($this->dir)) {
            $this->files = scandir($this->dir);
        } else {
            $this->files = [];
        }
    }

    public function render() 
    {
        $content = ''; 

        foreach ($this->files as $file) {
            if ($file !== '.' && $file !== '..') {
                if (is_file($this->dir . $file)) {
                    $content .= file_get_contents($this->dir . $file);
                }
            }
        }

        $content = preg_replace_callback('/\{\{(.*?)\}\}/', function($matches) {
            $tag = trim($matches[1]);
            
            if ($tag === 'QUERY_OPTIONS') {
                return $this->getSql();
            }

            $template_path = $this->dir . $tag . '.html';
            if (is_file($template_path)) {
                return file_get_contents($template_path);
            }
            
            return $matches[0]; 
        }, $content);

        echo $content;
    }

    public function getSql() {
        $queries = $this->model->getAvailableQueries();
        $options_html = '<option value="">-- Zadna data, jen volny dotaz --</option>';
        
        foreach ($queries as $id => $q) {
            $options_html .= '<option value="' . esc_attr($id) . '">' . esc_html($q['name']) . '</option>';
        }
        return $options_html;
    }
}