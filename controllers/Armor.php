<?php

require_once 'Base.php';

class Armor extends Base {

    protected $file_pattern = 'armor.json';

    protected function add_masterwork($item) {
        preg_match('/([\d,]+)/', $item->{'Armor Cost'}, $matches);
        if(count($matches)) {
            $item->Name = 'Masterwork '.$item->Name;
            $item->{'Armor Cost'} = (str_replace(',','',$matches[1]) + 150).' gp';
            $item->{'Armor Check Penalty'} = $item->{'Armor Check Penalty'}.' +1(mw)';
        }

        return $item;
    }
}
