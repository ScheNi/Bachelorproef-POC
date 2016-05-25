<?php

class AverageCounter extends Threaded implements iCounter
{
    private $counter_group;
    private $total_time=0;


    public function __construct(iCounterGroup $counter_group)
    {
        $this->counter_group = $counter_group;
    }

    public function getStats()
    {
        return $this->synchronized(function($this) {
            return array($this->total_time / $this->counter_group->dual_c_succ->number_of_requests);
        }, $this);
    }

    public function write($time1, $time2)
    {
        $this->synchronized(function($this) use ($time1, $time2) {
            $this->total_time += ($time2 - $time1)*1000;
        }, $this);
    }


}
