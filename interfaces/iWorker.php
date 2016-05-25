<?php
interface iWorker
{
    public function run();
    public function stop();
    public function __get($property);
}
