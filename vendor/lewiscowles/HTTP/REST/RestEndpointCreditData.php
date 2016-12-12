<?php

namespace lewiscowles\HTTP\REST;

use lewiscowles\CreditDataInterface;

class RestEndpointCreditData implements CreditDataInterface {
    protected $Created;
    protected $Balance;

    public function __construct(int $time, int $balance) {
        $this->Created = $time;
        $this->Balance = $balance;
    }

    public function getCreated() : int {
        return $this->Created;
    }

    public function getBalance() : int {
        return $this->Balance;
    }

    public function deductBalance(int $value) {
        $this->Balance -= abs($value);
    }

    public function topUpBalance(int $value) {
        $this->Balance += abs($value);
    }
}
