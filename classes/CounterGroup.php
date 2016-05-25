<?php

    class CounterGroup extends Threaded implements iCounterGroup
    {
        /**
         * Counters
         * --------
         * At first i used an array of counters, but this doesn't work
         * Gets wrapped in volitile, not same reference as in main thread no more
         * -> http://stackoverflow.com/questions/14796674/a-php-pthreads-thread-class-cant-use-array
         */
        private $average_c;
        private $histogram_c;
        private $dual_c;
        private $dual_c_succ;
        private $dual_c_fail;
        private $num_threads;

        public function __get($property) {
            return $this->synchronized(function($this) use ($property) {
                if (property_exists($this, $property)) {
                    return $this->$property;
                }
            }, $this);
        }

        public function __set($property, $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }

            return $this;
        }

        public function write($t1, $t2, $succeeded)
        {
            $this->synchronized(function($this) use ($t1, $t2, $succeeded) {
                $this->dual_c->write($t1, $t2);
                if($succeeded) {
                    $this->average_c->write($t1, $t2);
                    $this->dual_c_succ->write($t1, $t2);
                    $this->histogram_c->write($t1, $t2);
                } else {
                    $this->dual_c_fail->write($t1, $t2);
                }
            }, $this);
        }

        public function getStats()
        {
            return $this->synchronized(function($this) {
                return array_merge(
                    $this->dual_c->getStats(),
                    $this->dual_c_succ->getStats(),
                    $this->dual_c_fail->getStats(),
                    $this->average_c->getStats(),
                    $this->histogram_c->getStats()
                );
            }, $this);
        }
    }
