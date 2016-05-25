<?php
class WorkerFactory implements iWorkerFactory {

    public function makeWorker($ssl_resumption, $counter_group, $ip)
    {
        if($ssl_resumption) return new ResumptionWorker($counter_group, $ip);
        return new FullWorker($counter_group, $ip);
    }
}
