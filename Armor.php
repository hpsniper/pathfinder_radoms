<?php

require_once 'Base.php';

class Armor extends Base {

    protected $_data_source = 'data_files/default/armor.json';

}

$rw = new Armor();
$rw->generate_random();
$rw->search('Name', 'rose');
