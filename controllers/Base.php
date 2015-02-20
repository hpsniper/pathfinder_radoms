<?php

class Base {

    protected $_data_source;
    private $_data_array;

    public function get_data_source($subdir = 'default') {
        if(!isset($this->_data_source)) {
            $file = 'data_files/'.$subdir.'/'.$this->filename;
            $this->_data_source = $file;
        }

        return $this->_data_source;
    }

    public function get_data_array() {
        if(!isset($this->_data_array)) {
            $raw = file_get_contents($this->get_data_source());
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
        $mw = rand(0, 1);
        $item = $data_array[$rand];
        if($mw) {
            $item = $this->add_masterwork($item);
        }
        $this->display($data_array[$rand]);
    }

    public function search($field, $search) {
        $data_array = $this->get_data_array();
        foreach($data_array as $item) {
            if(preg_match("/{$search}/i", $item->$field)) {
                $this->display($item);
            }
        }
    }

    protected function add_masterwork($item) {
        return $item;
    }

}
