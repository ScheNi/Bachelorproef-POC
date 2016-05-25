<?php

class StatisticsWriter {
  private $file;

  private $time;

  public function __construct($filename, $resumption)
  {
    date_default_timezone_set("Europe/Brussels");
    $this->file = fopen($filename." ".date("d_m_Y h_i_s")." ".$resumption.".txt", "a") or die("Unable to open file!");
  }

  public function write($stats) {
    fwrite($this->file, $stats);
    $this->close();
  }

  public function close() {
    fclose($this->file);
  }
}
