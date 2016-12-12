<?php
/*
Plugin Name: CODESIGN2 WordPress REST API Example Plugin
Plugin URI: https://www.codesign2.co.uk
Description: Really basic WordPress REST API Example Plugin
Version: 1.0.0
Author: CD2Team
Author URI: https://www.codesign2.co.uk
*/

namespace lewiscowles;

\add_action('rest_api_init', function() {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    foreach([
        'Test'
    ] as $endpoint) {
        require_once( __DIR__."/controllers/{$endpoint}Controller.php");
        $controller_class = __NAMESPACE__."\\{$endpoint}Controller";
        $controller = new $controller_class();
        $controller->register_routes();
    }
});

\add_action('wp_ajax_apinonce', function() {
    die(\wp_create_nonce( 'wp_rest' ));
});

\spl_autoload_register( function($classname) {
    $file = sprintf(
        '%s/vendor/%s.php',
        __DIR__, str_replace('\\', DIRECTORY_SEPARATOR, $classname)
    );
    if(file_exists($file)) {
        require $file;
    }
});
