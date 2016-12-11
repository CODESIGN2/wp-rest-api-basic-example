<?php

use WP_REST_Server;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;

class TestController extends WP_REST_Controller {
    const RATE_FIELD = 'cd2_rest_rate';
    const RATE_TIME = 60; // One Hour
    const RATE_LIMIT = 5;

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
        $calls = $this->user_rate_check();
        if(!($calls > 0)) {
            return new WP_REST_Response("API Limit reached {$calls} left", 429);
        }
        $data = ['now'=> date('Y-m-d H:i:s')];
        return new WP_REST_Response( $data, 200);
    }

    public function index_permissions_check(WP_REST_Request $request) {
        // Really basic example for permissions checking
        // (https://codex.wordpress.org/Roles_and_Capabilities#read)
        return $this->user_permission_check('read');
    }

    public function user_permission_check($cap='admin') {
        return current_user_can($cap);
    }

    public function user_rate_check() {
        if($this->user_permission_check('read')) {
            $user_id = get_current_user_id();
            $transient_name = self::RATE_FIELD."_{$user_id}";
            $left = get_transient($transient_name);
            if(!is_array($left)) {
                set_transient($transient_name, [
                    'limit'=>self::RATE_LIMIT,
                    'time'=>time()
                ], self::RATE_TIME);
                $left = [
                    'limit'=>self::RATE_LIMIT,
                    'time'=>time()
                ];
            }
            $ret = intval($left['limit']);
            if($ret > 0) {
                $left['limit']--;
            }
            $time = max(0, (self::RATE_TIME-(time()-$left['time'])));
            set_transient($transient_name, $left, $time);
            return $ret;
        }
        return false;
    }
}
