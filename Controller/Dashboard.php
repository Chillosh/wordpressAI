<?php

function my_custom_menu_page() {
    add_menu_page(
        'Wordpress AI',
        'Wordpress AI',
        'manage_options',
        'custom-menu-slug',
        'my_custom_menu_page_callback',
        'dashicons-editor-textcolor',
        99
    );
}

add_action( 'admin_menu', 'my_custom_menu_page' );

function my_custom_menu_page_callback() {
    $idk = file_get_contents(STATS_PLUGIN_DIR . 'View/main.html') ? file_get_contents(STATS_PLUGIN_DIR . 'View/main.html') : null;
    echo($idk);
}

