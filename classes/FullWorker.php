<?php

    class FullWorker extends Thread implements iWorker
    {
        private $counter_group;
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
            $this->atomic_run_state = true;
            $this->ip = $ip;
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
            $data = array('name' => 'Nicolas', 'bank account' => '123462343');

            // use key 'http' even if you send the request to https://...
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                ),
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "ciphers" => "HIGH:!SSLv2:!SSLv3",
                    "reconnect" => true
                )
            );

            //http://stackoverflow.com/questions/26148701/file-get-contents-ssl-operation-failed-with-code-1-and-more
            $context = stream_context_create($options);


            while ($this->atomic_run_state) {
                $this->synchronized(function($this){
                    $this->before = microtime(true);
                }, $this);

                $result = file_get_contents($url, false, $context);

                $this->synchronized(function($this){
                    $this->after = microtime(true);
                }, $this);

                $this->counter_group->write($this->before, $this->after, $result);

            }
        }
    }
