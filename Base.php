<?php

class Base {

    protected $_data_source;
    private $_data_array;

    public function set_data_source($data_source) {
        $this->_data_source = $data_source;
    }

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

    public function search($field, $search) {
        $data_array = $this->get_data_array();
        foreach($data_array as $wpn) {
            if(preg_match("/{$search}/i", $wpn->$field)) {
                $this->display($wpn);
            }
        }
    }

}
