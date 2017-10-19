<?php

namespace lewiscowles\wordpress\auth;

use lewiscowles\auth\SystemUserInterface;

class WPUser implements SystemUserInterface {
    protected $Pk;

    public function __construct() {
        if(!function_exists('get_current_user_id') ||
            !function_exists('current_user_can') ||
            !function_exists('wp_authenticate') ||
            !function_exists('wp_logout') ||
            !function_exists('is_user_logged_in')) {
            throw new \RuntimeException("Required To Use Inside WordPress");
        }
        $this->Pk = \get_current_user_id();
    }

    public function hasCapability(string $capability) : bool {
        return \current_user_can($capability);
    }

    public function isLoggedIn() : bool {
        return \is_user_logged_in();
    }

    public function getPK() : string {
        return $this->Pk;
    }

    public function login(string $username, string $password) {
        \wp_authenticate($username, $password);
    }

    public function logout() {
        \wp_logout();
    }
}
