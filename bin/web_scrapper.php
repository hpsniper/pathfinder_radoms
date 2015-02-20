<?php

class Random_Weapons {

    private $_page_url = 'http://www.d20pfsrd.com/equipment---final/weapons';
    private $_data_source = 'data_files/default/weapons.html';
    private $_data_array;

    public function get_data_array() {
        if(!isset($this->_data_array)) {

            $raw = file_get_contents($this->_data_source);
            $doc = new DOMDocument;
            $doc->loadHTML('<?xml encoding="UTF-8">' . $raw);
            $tables = $doc->getElementsByTagName('tbody');
            $table_num = 0;

            $data_array = array();

            foreach($tables as $table) {
                $table_num++;
                if($table_num > 2 && $table_num < 19) {
                    $first = true;
                    $header = array();
                    foreach($table->childNodes as $child) {
                        if($first) {
                            // remvoe a trailing and the first br
                            $node_val = preg_replace("/\n/", " ", trim($child->nodeValue), 1);
                            $header = explode("\n", $node_val);
                            $first = false;
                        } else {
                            $a_info = $this->search_a_tags($child);
                            $set_array = array();
                            $row = explode("\n", trim($child->nodeValue));
                            for($i=0;$i<9;$i++) {
                                $set_array[$header[$i]] = $row[$i];
                            }

                            $set_array['Source'] = $a_info['source'];
                            $set_array['href'] = $a_info['href'];

                            $data_array[] = $set_array;
                        }
                    }
                }
            }

            $this->_data_array = $data_array;
        }

        return $this->_data_array;
    }

    private function search_a_tags($row) {
        $a_tags = $row->getElementsByTagName('a');
        $item_href = '';
        $source = '';
        foreach($a_tags as $tag) {
            if( $tag->hasAttribute('href') && preg_match('/weapon-description/', $tag->getAttribute('href')) ) {
                $item_href = $tag->getAttribute('href');
            }
            if($tag->hasAttribute('title')) {
                $source = $tag->getAttribute('title');
            }
        }

        return array('href' => $item_href, 'source' => $source);
    }

    public function display($row) {
        foreach($row as $key => $value) {
            if($key == 'href') {
                continue;
            }
            echo "\n$key: $value";
        }
        echo "\n".$row['href']."\n";
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

