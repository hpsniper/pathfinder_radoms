<?php

require_once 'Base.php';

class Spell extends Base {

    protected $filename = 'spells/';

    protected function add_masterwork($item) { return $item; }

    public function generate_random() {
        $letters = range('a','z');
        $letter = rand(0,25);
        $this->set_data_source('spells/'.$letters[$letter].'.json');
        $data_array = $this->get_data_array();
        $rand = rand(0, count($data_array) - 1);
        $this->display($data_array[$rand]);
    }

}
