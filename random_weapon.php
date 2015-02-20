<?php

class Random_Weapons {

    private $_data_source = 'data_files/default/weapons.json';
    private $_data_array;

    public function get_data_array() {
        if(!isset($this->_data_array)) {
            $raw = file_get_contents($this->_data_source);
            $this->_data_array = json_decode($raw);
        }

        return $this->_data_array;
    }

    public function display($row) {
        foreach($row as $key => $value) {
            if($key == 'href') {
                continue;
            }
            echo "\n$key: $value";
        }
        echo "\n".$row->href."\n";
    }

    public function generate_random() {
        $data_array = $this->get_data_array();
        $rand = rand(0, count($data_array) - 1);
        $this->display($data_array[$rand]);
    }

}

$rw = new Random_Weapons();
$rw->generate_random();
$rw->generate_random();
$rw->generate_random();
$rw->generate_random();

