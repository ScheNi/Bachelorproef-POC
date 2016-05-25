<?php

interface iCounterGroup
{
    public function getStats();
    public function write($t1, $t2, $succeeded);
    public function __get($property);
    }
