<?php

namespace lewiscowles\wordpress\storage;

use lewiscowles\storage\TTLStorageInterface;

class WPTransientStorage implements TTLStorageInterface {
    public function __construct() {
        if(!function_exists('set_transient') ||
            !function_exists('get_transient')) {
            throw new \RuntimeException("Required To Use Inside WordPress");
        }
    }

    public function get(string $hash, $default) {
        $out = \get_transient($hash);
        if(false !== $out) {
            return $out;
        }
        return $default;
    }

    public function set(string $hash, $value, int $ttl) {
        \set_transient($hash, $value, $ttl);
    }
}
