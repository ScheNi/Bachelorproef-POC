<?php

    class HistogramCounter extends Threaded implements iCounter
    {
        private $counter_group;
        /**-----+-----------------------------------------------------------
         * Slice|   Beschrijving
         * -----+-----------------------------------------------------------
         * 1:   |    Aantal connecties die slaagden in <= 50 ms
         * 2:   |    Aantal connecties die slaagden in > 50 ms en <= 100 ms
         * 3:   |    Aantal connecties die slaagden in > 100 ms en <= 200 ms
         * 4:   |    Aantal connecties die slaagden in > 200 ms en <= 500 ms
         * 5:   |    Aantal connecties die slaagden in > 500 ms
         **/
        private $slice_1=0;
        private $slice_2=0;
        private $slice_3=0;
        private $slice_4=0;
        private $slice_5=0;

        public function __construct(iCounterGroup $counter_group)
        {
            $this->counter_group = $counter_group;
        }

        public function getStats()
        {
            return $this->synchronized(function($this) {
                return array(
                    $this->slice_1,
                    $this->slice_2,
                    $this->slice_3,
                    $this->slice_4,
                    $this->slice_5,
                );
            }, $this);
        }

        public function write($time1, $time2)
        {
            $this->synchronized(function($this) use ($time1, $time2) {
                $time = ($time2 - $time1)*1000;
                if($time <= 50) {
                    $this->slice_1++;
                } elseif ($time <= 100) {
                    $this->slice_2++;
                } elseif ($time <= 200) {
                    $this->slice_3++;
                } elseif ($time <= 500) {
                    $this->slice_4++;
                } else {
                    $this->slice_5++;
                }
            }, $this);
        }

    }
