<?php

namespace lewiscowles;

use WP_REST_Server;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;

use lewiscowles\CreditDataInterface;
use lewiscowles\CreditHandlerInterface;
use lewiscowles\auth\SystemUserInterface;
use lewiscowles\wordpress\auth\WPUser;
use lewiscowles\storage\TTLStorageInterface;
use lewiscowles\HTTP\REST\RestEndpointCreditData;
use lewiscowles\HTTP\REST\RestEndpointTimedRateLimit;
use lewiscowles\wordpress\storage\WPTransientStorage;

class TestController extends \WP_REST_Controller {
    const RATE_FIELD = 'cd2_rest_rate_user';
    const RATE_TIME = 60; // One Minute
    const RATE_LIMIT = 5;

    protected $limiter;
    protected $auth;

    public function __construct() {
        $this->namespace = 'test/v1';
        $this->auth = new WPUser();
        $this->limiter = new RestEndpointTimedRateLimit(
            self::RATE_FIELD."_".$this->auth->getPk(),
            time(),
            self::RATE_LIMIT,
            new WPTransientStorage(),
            self::RATE_TIME
        );
    }

    public function register_routes() {
        \register_rest_route(
            "{$this->namespace}",
            "/date",
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'index'],
                    'permission_callback' => [$this, 'index_permissions_check'],
                    'args' => [],
                ]
            ]
        );
    }

    public function index(\WP_REST_Request $request) {
        $creditData = $this->limiter->getCreditData();
        $refresh = $this->limiter->formatCreditReload($creditData);
        if(!$this->limiter->checkCreditData($creditData)) {
            $left = $creditData->getBalance();
            $limit = self::RATE_LIMIT;
            return new \WP_REST_Response(
                "API Limit reached {$left}/{$limit} calls refresh in {$refresh}",
                429
            );
        }

        $data = [
            'now'=> date('Y-m-d H:i:s'),
            'time_to_refresh' => $refresh
        ];

        $creditData->deductBalance(1);
        $this->limiter->setCreditData($creditData);

        return new \WP_REST_Response( $data, 200);
    }

    public function index_permissions_check(WP_REST_Request $request) {
        // Really basic example for permissions checking
        // (https://codex.wordpress.org/Roles_and_Capabilities#read)
        return $this->auth->hasCapability('read');
    }
}
