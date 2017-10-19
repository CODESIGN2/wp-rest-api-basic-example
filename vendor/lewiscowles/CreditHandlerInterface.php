<?php

namespace lewiscowles;

use lewiscowles\CreditDataInterface;

interface CreditHandlerInterface {
    public function getCreditData() : CreditDataInterface;
    public function checkCreditData(CreditDataInterface $data) : bool;
    public function setCreditData(CreditDataInterface $data);
    public function formatCreditReload(CreditDataInterface $data) : string;
}
