<?php

use WP_REST_Server;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;

class TestController extends WP_REST_Controller {

    public function __construct() {
        $this->namespace = 'test/v1';
    }

    public function register_routes() {
        register_rest_route(
            "{$this->namespace}",
            "/date",
            [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'index'],
                    'permission_callback' => [$this, 'index_permissions_check'],
                    'args' => [],
                ]
            ]
        );
    }

    public function index(WP_REST_Request $request) {
        $data = ['now'=> date('Y-m-d H:i:s')];
        return new WP_REST_Response( $data, 200);
    }

    public function index_permissions_check(WP_REST_Request $request) {
        // Really basic example for permissions checking
        // (https://codex.wordpress.org/Roles_and_Capabilities#read)
        return $this->user_permission_check(get_current_user_id(), 'read'); 
    }

    public function user_permission_check($user_id='', $cap='admin') {
        if(!empty($user_id)) {
            return user_can($user_id, $cap);
        }
        return false;
    }
}
