<?php

class Weapons {

    private $_data_source = 'data_files/default/spells/names_urls.json';
    private $_data_array;

    public function get_data_array() {
        if(!isset($this->_data_array)) {

            $spell_raw = file_get_contents($this->_data_source);
            $spell_array = json_decode($spell_raw);

            $data_array = array();
            $last_letter = '0';
            $fh;

            foreach($spell_array as $name => $url) {
                $page_raw = file_get_contents("http://www.d20pfsrd.com/$url");
                preg_match('/all-spells\/(\w)/',$url,$matches);
                if($last_letter != $matches[1]) {
                    if(isset($fh)) {
                        fwrite($fh, json_encode($data_array));
                        fclose($fh);
                    }
                    $fh = fopen('data_files/default/spells/'.$matches[1].'.json', 'w+');
                    $data_array = array();
                    $last_letter = $matches[1];
                }

                $doc = new DOMDocument;
                $doc->loadHTML('<?xml encoding="UTF-8">' . $page_raw);

                $content = $doc->getElementById('sites-canvas-main-content');
                if(!$content) {
                    continue;
                }
                $p_tags = $content->getelementsByTagName('p');

                $spell_object = (object) array();
                $spell_object->Name = $name;

                for($i=0;$i<$p_tags->length - 1;$i++) {
                    $p = $p_tags->item($i);
                    $this->parsePTag($p, $spell_object);
                }

                $data_array[] = $spell_object;
            }

            fwrite($fh, json_encode($data_array));
        }
    }

    public function parsePTag($p, $spell_object) {
        $value = $p->nodeValue;
        if(preg_match('/School (.*); Level (.*)/', $value, $line)) {
            $spell_object->School = $line[1];
            $class_array = array();
            foreach(explode(', ',$line[2]) as $class) {
                $parts = explode(' ', $class);
                $val = count($parts) > 1 ? $parts[1] : 999;
                $class_array[$parts[0]] = (int) $val; // TODO
            }
            $spell_object->Level = $class_array;
        } else if(preg_match('/Casting Time (.*)Components (.*)/', $value, $line)) {
            $spell_object->Casting_Time = $line[1];
            $comp_array = array();
            foreach(explode(', ',$line[2]) as $comp) {
                $parts = explode(' ', $comp);
                $extra = count($parts) > 1 ? substr($comp, 2) : NULL;
                $comp_array[$parts[0]] = $this->expandComponenet($parts[0], $extra);
            }
            $spell_object->Components = $comp_array;
        } else if(preg_match('/Range (.*)Target (.*)Duration (.*)Saving Throw (.*) Spell Resistance (.*)/', $value, $line)) {
            $spell_object->Range = $line[1];
            $spell_object->Target = $line[2];
            $spell_object->Duration = $line[3];
            $spell_object->Saving_Throw = $line[4];
            $spell_object->Spell_Resistance = $line[5];
        } else {
            if(isset($spell_object->Description)) {
                $spell_object->Description = $spell_object->Description."\n".$value;
            } else {
                $spell_object->Description = $value;
            }
        }
    }

    public function expandComponenet($comp, $extra = NULL) {
        Switch($comp) {
            case 'DF':
                return 'Divine Focus';
            break;
            case 'M':
                return 'Material '.$extra;
            break;
            case 'S':
                return 'Somatic';
            break;
            case 'V':
                return 'Verbal';
            break;
            default:
                return '';
            break;
        }

        return '';
    }

    public function save_spell($spell_object) {
        var_dump($spell_object);
        exit();
    }

    public function generate_random() {
        $data_array = $this->get_data_array();
        $rand = rand(0, count($data_array) - 1);
        //$this->display($data_array[$rand]);
    }

}

$rw = new Weapons();
$rw->generate_random();
