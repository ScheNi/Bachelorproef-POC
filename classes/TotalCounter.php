<?php
class TotalCounter {
    private $results = array(0,0,0,0,0,0,0,0,0,0,0,0);
    private $times = 0;
    private $num_threads;

    public function accumulate(iCounterGroup $counter, $num_threads){
        $this->num_threads = $num_threads;
        $stats = $counter->getStats();
        for($i = 0; $i < sizeof($stats); $i++ ) {
            if(!is_nan($stats[$i])) {
                $this->results[$i] += $stats[$i];
            }
        }
        $this->times++;
    }

    public function printData() {
        $res = $this->results;
        //reset the results
        $this->results = array(0,0,0,0,0,0,0,0,0,0,0,0);

        //calculate the average
        $res[6] = $res[6] / $this->times;
        //reset times
        $this->times = 0;

        return array_merge([date("Y-m-d"), $this->udate("h:i:s,u "), $this->num_threads], $res);
    }

    private function udate($format = 'u', $utimestamp = null) {
        date_default_timezone_set("Europe/Brussels");
        if (is_null($utimestamp))
            $utimestamp = microtime(true);

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }
}
