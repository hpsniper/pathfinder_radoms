<?php

require_once 'Base.php';

class Spell extends Base {

    protected $file_pattern = 'spells/?.json';

    protected function add_masterwork($item) { return $item; }

    public function generate_random() {
        $letters = range('a','z');
        $letter = rand(0,25);
        $this->set_data_source('spells/'.$letters[$letter].'.json');
        $data_array = $this->get_data_array();
        $rand = rand(0, count($data_array) - 1);
        $this->display($data_array[$rand]);
    }

    protected function get_href($row) {
        $this->set_data_source('spells/names_to_urls.json');
        $data_array = $this->get_data_array();
        $key = $row['Name'];
        return 'http://www.d20pfsrd.com'.$data_array[$key];
    }
}
