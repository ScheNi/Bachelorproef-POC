<?php

abstract class DualCounter extends Threaded implements iCounter
{
    private $counter_group;
    protected $last_requests = 0;
    protected $number_of_requests=0;

    public function __construct(iCounterGroup $counter_group)
    {
        $this->counter_group = $counter_group;
    }

    public abstract function getStats();
    public abstract function write($time1, $time2);
}
