<?php
/*
 * Plugin Name:       wordpressAI
 * Plugin URI:        https://github.com/Chillosh/wordpressAI
 * Description:       A simple tool for integration of google ai studio to wordpress.
 * Version:           0.01
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Miloš Červinka
 * Author URI:        https://github.com/Chillosh
 * License:           MIT
 * License URI:       https://mit-license.org/
 * Update URI:        https://github.com/Chillosh/wordpressAI/commits/main/
 * Text Domain:       wordpress-ai
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) die();

define('PLUGIN_URL', plugin_dir_url(__FILE__));
define('PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once PLUGIN_DIR . 'includes/queries.php';
require_once PLUGIN_DIR . 'Model/AiModel.php';
require_once PLUGIN_DIR . 'Controller/DashboardController.php';
require_once PLUGIN_DIR . 'Controller/SenderController.php';

$ai_model = new AiModel();
new DashboardController($ai_model);
new SenderController($ai_model);