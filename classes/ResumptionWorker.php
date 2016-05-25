<?php

    class ResumptionWorker extends Thread implements iWorker
    {
        private $counter_group;
        private $ip;
        private $atomic_run_state;
        private $before;
        private $after;

        /**
         * Since php 5 all objects are passed by reference
         *
         * @param iCounterGroup $counter_group
         * @param $ssl_resumption
         */
        public function __construct($counter_group, $ip)
        {
            $this->counter_group = $counter_group;
            $this->ip = $ip;
            $this->atomic_run_state = true;
        }

        public function __get($property)
        {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
        }

        public function stop()
        {
            $this->synchronized(function($this){
                return ($this->atomic_run_state = false);
            }, $this);
        }


        public function run()
        {
            $url = 'https://'.$this->ip.'/';
            //dummy data
            $data = array('name' => 'Nicolas', 'bank account' => '123462343');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSLVERSION, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile");
            curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile");
            curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);

            while ($this->atomic_run_state) {
                $this->synchronized(function($this){
                    $this->before = microtime(true);
                }, $this);

                $result = curl_exec($ch);


                $this->synchronized(function($this){
                    $this->after = microtime(true);
                }, $this);

                $this->counter_group->write($this->before, $this->after, $result);

            }
        }
    }
