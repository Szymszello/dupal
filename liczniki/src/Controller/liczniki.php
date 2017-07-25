<?php

namespace Drupal\liczniki\Controller;

use Drupal\Core\ebiSoapEBOK;
use Drupal\Core\Controller\ControllerBase;

class liczniki extends ControllerBase
{
    public function wyswietl()
    {
        $soap = new ebiSoapEBOK();
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $name = $user->get('name')->value;
        $output = array();
        $pola = array('name','kod_miesiaca','odczyt');

        $wynik = $soap->pobierz_rekordy('ebok_liczniki', $pola, 'ebok_liczniki.nazwa_klienta ='."'$name'");
       // $wynik = $soap->pobierz_rekordy('ebok_liczniki');
        $wynik = json_encode($wynik);
       
        array_push($output,'<div id=asd>'.$wynik.'</div>');
        array_push($output, '<table id = "example" class="display" cellspacing="0" width="100%"></table>');
  
        return array('#markup'=>implode($output,''));
        
    }
 
}