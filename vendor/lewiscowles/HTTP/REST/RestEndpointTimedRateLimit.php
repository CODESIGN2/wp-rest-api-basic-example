<?php

namespace lewiscowles\HTTP\REST;

use lewiscowles\CreditHandlerInterface;
use lewiscowles\CreditDataInterface;
use lewiscowles\storage\TTLStorageInterface;
use lewiscowles\HTTP\REST\RestEndpointCreditData;

class RestEndpointTimedRateLimit implements CreditHandlerInterface {
    protected $HashId;
    protected $Time;
    protected $Credits;
    protected $Storage;
    protected $Ttl;

    public function __construct(string $hash_id, int $time, int $credits,
        TTLStorageInterface $ttsi, int $ttl) {
        $this->HashId = $hash_id;
        $this->Time = $time;
        $this->Credits = $credits;
        $this->Storage = $ttsi;
        $this->Ttl = $ttl;
    }

    public function getCreditData() : CreditDataInterface {
        return $this->Storage->get($this->HashId, new RestEndpointCreditData(
            $this->Time, $this->Credits
        ));
    }

    public function checkCreditData(CreditDataInterface $data) : bool {
        return ($data->getBalance() > 0);
    }

    public function setCreditData(CreditDataInterface $data) {
        $this->Storage->set($this->HashId, $data, $this->getRefreshTtl($data));
    }

    public function formatCreditReload(CreditDataInterface $data) : string {
        $refresh = $this->getRefreshTtl($data);
        $out = date('s', $refresh).' seconds';
        if($refresh > 60) {
            $out = date('i', $refresh).' minutes, ' . $out;
        }
        if($refresh > 3600) {
            $out = intval($refresh / 3600).' hours, '.$out;
        }
        return $out;
    }

    protected function getRefreshTtl(CreditDataInterface $data) {
        return ($this->Ttl - ($this->Time - $data->getCreated()));
    }
}
