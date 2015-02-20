<?php

require_once 'Base.php';

class Armor extends Base {

    protected $filename = 'armor.json';

}

$rw = new Armor();
$rw->generate_random();
$rw->search('Name', 'rose');
