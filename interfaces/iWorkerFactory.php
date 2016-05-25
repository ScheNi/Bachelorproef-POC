<?php

    interface iWorkerFactory {
        public function makeWorker($ssl_resumption, $counter_group, $ip);
    }
