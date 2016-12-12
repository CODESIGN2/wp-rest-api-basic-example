<?php

namespace lewiscowles\auth;

interface SystemUserInterface {
    public function hasCapability(string $capability) : bool;
    public function isLoggedIn() : bool;
    public function getPK() : string;
    public function login(string $username, string $password);
    public function logout();
}
