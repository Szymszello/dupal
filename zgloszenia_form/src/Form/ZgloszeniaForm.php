<?php

namespace Drupal\zgloszenia_form\Form;

use Drupal\Core\ebiSoapEBOK;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ZgloszeniaForm extends FormBase
{

    /**
     * metody dziedziczone z klasy FormBase
     * @return string
     */
    public function getFormId()
    {
        return 'zgloszenia_form';
    }


    /**
     * Buduje formularz https://www.drupal.org/docs/8/api/form-api
     * @param array $form
     * @param FormStateInterface $form_state
     * @return array
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['typ_zgloszenia'] = [
            '#type' => 'select',
            '#title' => t('Typ zgłoszenia'),
            '#required' => 'TRUE',
            '#options' => [
                '#zepsuty_licznik' => t('Zepsuty licznik'),
                '#wyciek' => t('Wyciek rury'),
                '#inne' => t('Inne')
            ]
        ];

        $form['wiadomosc'] = [
            '#type' => 'textarea',
            '#required' => 'TRUE',
            '#rows' => 20,
            '#title' => t('Wiadomość')
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Wyślighj'),
            '#button_type' => 'primary',
        );

        return $form;
    }

    /**
     * tutaj dodajesz obsluge danych
     * @param array $form
     * @param FormStateInterface $form_state tutaj jest formularz jako tablica asocjacyjna
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id()); //load current user
        $name = $user->get('name')->value; //load current user login
        $typ_zgloszenia = $form_state->getValue('typ_zgloszenia');
        $tresc = $form_state->getValue('wiadomosc');
        $soap_request = new ebiSoapEBOK();
        $tab_danych = array(
            'typ'=>$typ_zgloszenia,
            'opis'=>$tresc,
            'autor'=>$name,
            'stan'=>'w toku'
        );
        $soap_request->wyslij_rekord('ebok_zgloszenia',$tab_danych);
    }
}