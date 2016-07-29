<?php

require_once 'Base.php';

class Weapon extends Base {

    protected $file_pattern = 'weapons.json';

    protected function add_masterwork($item) {
        preg_match('/([\d,]+) (\D+)/', $item['Cost'], $matches);
        $increase = 300;
        if(count($matches)) {
            $cost = str_replace(',','',$matches[1]);
            if(preg_match('/\((\d+)\)/', $item['Name'], $quantity)) {
                $increase = $quantity[1] * 6;
            } else if(preg_match('/double/', $item['Special'])) {
                $increase = 600;
            }

            switch($matches[2]) {
                case 'sp':
                    $cost = ($cost / 10) + $increase;
                break;
                case 'cp':
                    $cost = ($cost / 100) + $increase;
                break;
                default:
                    $cost = $cost + $increase;
                break;
            }
            $item['Name'] = 'Masterwork '.$item['Name'];
            $item['Cost'] = $cost.' gp';
        }

        return $item;
    }
}
