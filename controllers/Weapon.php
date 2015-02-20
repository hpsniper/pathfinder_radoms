<?php

require_once 'Base.php';

class Weapon extends Base {

    protected $filename = 'weapons.json';

}

$rw = new Weapon();
$rw->generate_random();
$rw->search('Name', 'Glad');
