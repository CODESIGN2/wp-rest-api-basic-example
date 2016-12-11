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
                    'args' => [],,
                ]
            ]
        );
    }

    public function index(WP_REST_Request $request) {
        $data = ['now'=> date('Y-m-d H:i:s')];
        return new WP_REST_Response( $data, 200);
    }

    public function index_permissions_check(WP_REST_Request $request) {
        return true;
    }
}
