<?php
/*
Plugin Name: CODESIGN2 WordPress REST API Example Plugin
Plugin URI: https://www.codesign2.co.uk
Description: Really basic WordPress REST API Example Plugin
Version: 1.0.0
Author: CD2Team
Author URI: https://www.codesign2.co.uk
*/

add_action('rest_api_init', function(){
    foreach([
        'Test'
    ] as $endpoint) {
        require_once( __DIR__."/controllers/{$endpoint}Controller.php");
        $controller_class = "{$endpoint}Controller";
        $controller = new $controller_class();
        $controller->register_routes();
    }
});
