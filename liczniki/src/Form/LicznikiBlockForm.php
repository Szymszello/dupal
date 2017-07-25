<?php

namespace Drupal\liczniki\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\ebiSoapEBOK;

/**
 * Formularz logowania
 */
class LicznikiBlockForm extends FormBase {

   
  public function getFormId() {
    return 'liczniki_block_form';
  }
  
   public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['stan_licznika'] = [
            '#type' => 'number',
            '#title' => t('Stan licznika'),
            '#required' => 'TRUE',
            '#rows'=> 1
        ];
        $form['nazwa_licznika'] = [
            '#type' => 'textarea',
            '#title' => t('Nazwa licznika'),
            '#required' => 'TRUE',
            '#rows'=> 1
        ];
 
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Wyślij'),
            '#button_type' => 'primary',
        );

        return $form;
    }

   
  public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id()); //load current user
        $name = $user->get('name')->value; //load current user login
        $odczyt = $form_state->getValue('stan_licznika');
        $licznik = $form_state->getValue('nazwa_licznika');

         
        $soap_request = new ebiSoapEBOK();
        $tab_dane = array(
            'name'=>$licznik,
            'nazwa_klienta'=>$name,
            'odczyt'=>$odczyt,
            'kod_miesiaca'=>date('Ym',time())
        );
        if($soap_request->wyslij_rekord('ebok_liczniki',$tab_dane))
        drupal_set_message(t('Twoje zgłoszenie zostało wysłane do systemu.'),'status');
        else  drupal_set_message(t('Twoje zgłoszenie nie zostało wysłane do systemu.'),'error');

    }
  
}