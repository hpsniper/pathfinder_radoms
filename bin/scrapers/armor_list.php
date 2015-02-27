<?php

class Armor {

    private $_page_url = 'http://www.d20pfsrd.com/equipment---final/armor';
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
                if($table_num > 2 && $table_num < 8) {
                    $header = array();
                    $classification = '';
                    $count = 0;
                    foreach($table->childNodes as $child) {
                        if($count == 0) {
                            // remvoe a trailing and the first br
                            $node_val = preg_replace("/\n/", " ", trim($child->nodeValue), 1);
                            $classification = $node_val;
                        } else if($count == 1) {
                            // remvoe a trailing and the first br
                            $node_val = preg_replace("/\n/", " ", trim($child->nodeValue), 1);
                            $header = explode("\n", $node_val);
                            array_unshift($header, 'Name');
                        } else if($count == 2) {
                            $header[6] = 'Speed_30';
                            $header[7] = 'Speed_20';
                            $header[8] = 'Weight';
                            $header[9] = 'Source';
                        } else {
                            $a_info = $this->search_a_tags($child);
                            $set_array = array();
                            $row = explode("\n", trim($child->nodeValue));
                            $set_array['Classification'] = $classification;
                            for($i=0;$i<count($row)-1;$i++) {
                                $set_array[$header[$i]] = $row[$i];
                            }

                            $set_array['Source'] = $a_info['source'];
                            $set_array['href'] = $a_info['href'];
                            if(isset($set_array['Armor Cost'])) {
                                $set_array['Cost In GP'] = $this->convertStringCostToGpFloat($set_array['Armor Cost']);
                            } else if(isset($set_array['Shield Cost'])) {
                                $set_array['Cost In GP'] = $this->convertStringCostToGpFloat($set_array['Shield Cost']);
                            } else if(isset($set_array['Extra Cost'])) {
                                $set_array['Cost In GP'] = $this->convertStringCostToGpFloat($set_array['Extra Cost']);
                            }

                            $data_array[] = $set_array;
                        }

                        $count++;
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
            if( $tag->hasAttribute('href') && preg_match('/equipment---final\/armor/', $tag->getAttribute('href')) ) {
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

$arms = new Armor();
$arms->get_data_array();
