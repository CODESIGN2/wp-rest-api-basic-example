<?php

namespace lewiscowles;

interface CreditDataInterface {
    public function getCreated() : int;
    public function getBalance() : int;
    public function deductBalance(int $deduction);
    public function topupBalance(int $deduction);
}
