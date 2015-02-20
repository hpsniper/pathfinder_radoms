<?php

require_once 'Base.php';

class Weapon extends Base {

    protected $_data_source = 'data_files/default/weapons.json';

}

$rw = new Weapon();
$rw->generate_random();
$rw->search('Name', 'Glad');
