<?php

class Weapons {

    private $_page_url = 'http://www.d20pfsrd.com/equipment---final/weapons';
    private $_data_source = '../data_files/default/weapons.html';
    private $_data_array;

    public function get_data_array() {
        if(!isset($this->_data_array)) {

            $raw = file_get_contents($this->_page_url);
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
                            $set_array['Classification'] = $header[0];
                            $set_array['Name'] = $row[0];
                            for($i=1;$i<9;$i++) {
                                $set_array[$header[$i]] = $row[$i];
                            }

                            $set_array['Source'] = $a_info['source'];
                            $set_array['href'] = $a_info['href'];
                            $set_array['Cost in GP'] = $this->convertStringCostToGpFloat($set_array['Cost']);

                            $data_array[] = $set_array;
                        }
                    }
                }
            }

            echo json_encode($data_array);
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

    private function convertStringCostToGpFloat($cost) {
        $parts = explode(' ', preg_replace('/,/','',$cost));
        $mul = 1;
        $return = $parts[0];
        if(count($parts) == 2) {
            switch($parts[1]) {
                case 'pp':
                    $mul = 10;
                break;
                case 'gp':
                    $mul = 1;
                break;
                case 'sp':
                    $mul = 0.1;
                break;
                case 'cp':
                    $mul = 0.01;
                break;
            }

            $return = $parts[0] * $mul;
        }

        return (float) $return;
    }

}

$rw = new Weapons();
$rw->get_data_array();
