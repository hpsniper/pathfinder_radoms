<?php

class Spells {

    private $_page_url = 'http://www.d20pfsrd.com/magic/all-spells';
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
                if($table_num > 2 && $table_num < 4) {
                    $header = array();
                    $classification = '';
                    $count = 0;
                    foreach($table->childNodes as $child) {
                        $a_info = $this->search_a_tags($child);
                        echo(json_encode($a_info));
                    }
                }
            }
        }

        return $this->_data_array;
    }

    private function search_a_tags($row) {
        $a_tags = $row->getElementsByTagName('a');
        $item_href = '';
        $source = '';
        $data_array = array();
        foreach($a_tags as $tag) {
            if( $tag->hasAttribute('href') && preg_match('/all-spells/', $tag->getAttribute('href')) ) {
                $item_href = $tag->getAttribute('href');
                $item_text = $tag->nodeValue;
                $data_array[$item_text] = $item_href;
            }
        }

        return $data_array;
    }

}

$rw = new Spells();
$rw->get_data_array();
