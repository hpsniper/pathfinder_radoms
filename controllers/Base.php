<?php

class Base {

    protected $_data_source;
    private $_data_array;

    public function get_data_source($subdir = 'default') {
        if(!isset($this->_data_source)) {
            $file_pattern = 'data_files/'.$subdir.'/'.$this->file_pattern;
            $this->_data_source = glob($file_pattern);
        }

        return $this->_data_source;
    }

    public function set_data_source($filename, $subdir = 'default') {
        $this->_data_source = ["data_files/$subdir/$filename"];
        $this->_data_array = NULL;
    }

    public function get_data_array() {
        if(!isset($this->_data_array)) {
            $this->_data_array = [];
            foreach($this->get_data_source() as $filename) {
                $raw = file_get_contents($filename);
                $this->_data_array = array_merge($this->_data_array, json_decode($raw, true));
            }
        }

        return $this->_data_array;
    }

    public function display($row) {
        foreach($row as $key => $value) {
            if($key == 'href') {
                continue;
            }
            if(is_string($value) || is_int($value)) {
                echo "\n$key: $value";
            } else {
                foreach($value as $k => $v) {
                    echo "\n\t$k: $v";
                }
            }
        }

        echo "\n".$this->get_href($row)."\n############################################################\n";
    }

    public function generate_random() {
        $data_array = $this->get_data_array();
        $rand = rand(0, count($data_array) - 1);
        $mw = rand(0, 1);
        $item = $data_array[$rand];
        if($mw) {
            $item = $this->add_masterwork($item);
        }
        $this->display($item);
    }

    public function search($search, $field) {
        $data_array = $this->get_data_array();
        foreach($data_array as $item) {
            if($this->search_r($search, $item, $field)) {
                $this->display($item);
            }
        }
    }

    private function search_r($search, $obj, $search_key = '') {
        $return = false;
        foreach($obj as $obj_key => $obj_val) {
            if($return) {
                return $return;
            }

            if(!empty($search_key)) {
                if($search_key == $obj_key) {
                    if(is_numeric($obj_val)) {
                        return $search == $obj_val;
                    } else if(is_string($obj_val)) {
                        return $this->string_find($search, $obj_val);
                    } else {
                        return $this->search_r($search, $obj_val, '');
                    }
                } 
            } else {
                if(is_numeric($obj_val)) {
                    $return = $return || $search === $obj_val;
                } else if(is_string($obj_val)) {
                    $return = $return || $this->string_find($search, $obj_val);
                } else {
                    return $this->search_r($search, $obj_val);
                }
            }
        }

        return $return;
    }

    private function string_find($search, $string) {
        return preg_match("/{$search}/i", $string);
    }

    protected function add_masterwork($item) {
        return $item;
    }

    protected function get_href($row) {
        return $row['href'];
    }

}
