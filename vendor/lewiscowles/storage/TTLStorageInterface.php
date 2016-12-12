<?php

namespace lewiscowles\storage;

interface TTLStorageInterface {
    public function get(string $hash, $default);
    public function set(string $hash, $value, int $ttl);
}
