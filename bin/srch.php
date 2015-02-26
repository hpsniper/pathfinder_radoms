#!/usr/bin/php -q
<?php

require_once 'controllers/Armor.php';
require_once 'controllers/Spell.php';
require_once 'controllers/Weapon.php';

array_shift($argv);
switch($argv[0]) {
    case 'armor':
        $arm = new Armor();
        $arm->search($argv[1], $argv[2]);
    break;
    case 'wpn':
        $wpn = new Weapon();
        $wpn->search($argv[1], $argv[2]);
    break;
    case 'spell':
        $spell = new Spell();
        $spell->search($argv[1], $argv[2]);
    break;
}

?>
