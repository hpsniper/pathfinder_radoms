<?php

class Random_Weapons {

    private $_page_url = 'http://www.d20pfsrd.com/equipment---final/weapons';
    private $_data_source = 'data_files/default/weapons.html';
    private $_data_array;
    private $_data_size;

    public function get_data_array() {
        $this->set_data_array_and_size();
        return $this->_data_array;
    }

    public function get_data_array_size() {
        $this->set_data_array_and_size();
        return $this->_data_size;
    }

    public function set_data_array_and_size() {
        if(!isset($this->_data_array)) {

            $raw = file_get_contents($this->_data_source);
            $doc = new DOMDocument;
            $doc->loadHTML('<?xml encoding="UTF-8">' . $raw);
            $tables = $doc->getElementsByTagName('tbody');
            $table_num = 0;

            $data_array = array();
            $data_array_size = 0;

            foreach($tables as $table) {
                $table_num++;
                if($table_num > 2 && $table_num < 19) {
                    $data_array['th'.$table_num] = array();
                    $first = true;
                    foreach($table->childNodes as $child) {
                        if($first) {
                            $data_array['th'.$table_num][] = preg_replace('/\n/', ' | ', $child->nodeValue);
                            $first = false;
                        } else {
                            $a_tags = $child->getElementsByTagName('a');
                            $href = '';
                            if($a_tags && $a_tags->item(0)) {
                                if($a_tags->item(0)->hasAttribute('href')) {
                                    $href = $a_tags->item(0)->getAttribute('href');
                                }
                            }
                            $data_array['th'.$table_num][] = preg_replace('/\n/', ' | ', $child->nodeValue) . $href;
                            $data_array_size++;
                        }
                    }
                }
            }

            $this->_data_size = $data_array_size;
            $this->_data_array = $data_array;
        }
    }

    public function generate_random() {
        $data_array = $this->get_data_array();
        $data_array_size = $this->get_data_array_size();
        $rand = rand(1, $data_array_size);
        foreach($data_array as $key => $table) {
            $first = true;
            foreach($table as $row) {
                if($first) {
                    $first = false;
                    continue;
                }
                if($rand == 1) {
                    echo "\n".$table[0];
                    echo "\n".$row;
                    echo "\n";
                    return ;
                }

                $rand--;
            }
        }
    }

}

$rw = new Random_Weapons();
$rw->generate_random();
$rw->generate_random();
$rw->generate_random();
$rw->generate_random();

