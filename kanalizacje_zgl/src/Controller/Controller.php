<?php

namespace Drupal\kanalizacje_zgl\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\ebiSoapEBOK;

/**
 * Provides route responses for the Example module.
 */
class Controller extends ControllerBase
{

    function getTickets()
    {
        $soap = new ebiSoapEBOK();
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $results = [];

        foreach ($user->getRoles() as $role) {
            $dzial = "";
            switch ($role) {
                case 'dzial_kanalizacji':
                    $dzial = "kanalizacja";
                    break;
                case 'dzial_wodociagow':
                    $dzial = "wodociagi";
                    break;
            }
            array_push($results, $soap->pobierz_zgloszenia($dzial));
        }

        return $results;
    }

    /**
     * Returns a simple page.
     *
     * @return array
     *   A simple renderable array.
     */
    public function page()
    {
        $data = [];
        $data = $this->getTickets();;
        $element = [];
        $i = 0;
        foreach ($data as $zgloszenie) {
            $i++;
            array_push($element, array(
                '#markup'.$i => print_r($zgloszenie,1),
            ));
        }
//        $element = array('#markup'=>"asdasdasdasda");

        return $element;
    }


}