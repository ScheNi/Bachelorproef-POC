<?php

interface iCounter
{
    public function __construct(iCounterGroup $counter_group);
    public function getStats();
    public function write($time1, $time2);
}
