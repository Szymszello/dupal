<?php

namespace Drupal\liczniki\Form;

use Drupal\Core\ebiSoapEBOK;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class liczniki_form extends FormBase
{
    public function getFormId()
    {
        return 'liczniki_form';
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
        // drupal_set_message($this->t("@name, Twoje zgłoszenie zostało wysłane do systemu."), array('@name' => $name));
         
        $soap_request = new ebiSoapEBOK();
        $tab_dane = array(
            'name'=>$licznik,
            'nazwa_klienta'=>$name,
            'odczyt'=>$odczyt,
            'kod_miesiaca'=>date('Ym',time())
        );
        $soap_request->wyslij_rekord('ebi_liczniki',$tab_dane);
    }
  
}