<?php
class SucceedDualCounter extends DualCounter {

    public function __construct(iCounterGroup $counter_group)
    {
        parent::__construct($counter_group);
    }

    public function getStats()
    {
        return $this->synchronized(function($this) {
            $last_req = $this->last_requests;
            $this->last_requests = 0;
            return array($this->number_of_requests, $last_req);
        }, $this);
    }

    public function write($time1, $time2)
    {
        $this->synchronized(function($this) {
            $this->last_requests++;
            $this->number_of_requests++;
        }, $this);
    }
}
