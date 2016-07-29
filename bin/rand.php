#!/usr/bin/php -q
<?php

require_once 'controllers/Armor.php';
require_once 'controllers/Spell.php';
require_once 'controllers/Weapon.php';

array_shift($argv);
switch($argv[0]) {
    case 'armor':
        $arm = new Armor();
        $arm->generate_random();
    break;
    case 'wpn':
        $wpn = new Weapon();
        $wpn->generate_random();
    break;
    case 'spell':
        $spell = new Spell();
        $spell->generate_random();
    default:
        echo "Unrecognized: '".$argv[0]."'\n";
}

?>
