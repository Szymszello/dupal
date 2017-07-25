<?php

namespace Drupal\Payu\Form;

use Drupal\Core\ebiSoapEBOK;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing;

class payuForm extends FormBase
{
    public function getFormId()
    {
        return 'payu_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['desc'] = [
            '#type' => 'textarea',
            '#title' => t('Opis zamówienia'),
            '#rows'=> 1
        ];
        $form['productName'] = [
            '#type' => 'textarea',
            '#title' => t('Nazwa produktu'),
            '#required' => 'TRUE',
            '#rows'=> 1
        ];
        $form['amount'] = [
            '#type' => 'number',
            '#title' => t('Cena'),
            '#required' => 'TRUE',
            '#rows'=> 1
        ];
        $form['quantity'] = [
            '#type' => 'number',
            '#title' => t('Ilość'),
            '#required' => 'TRUE',
            '#rows'=> 1
        ];
        $form['imie'] = [
            '#type' => 'textarea',
            '#title' => t('Imie'),
            '#required' => 'TRUE',
            '#rows'=> 1
        ];
        $form['nazwisko'] = [
            '#type' => 'textarea',
            '#title' => t('nazwisko'),
            '#required' => 'TRUE',
            '#rows'=> 1
        ];
        $form['email'] = [
            '#type' => 'textarea',
            '#title' => t('Email'),
            '#required' => 'TRUE',
            '#rows'=> 1
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Zapłać'),
            '#button_type' => 'primary',
        );

        return $form;
    }

    /**
     * @param array $form
     * @param FormStateInterface $form_state
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->configure();

        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id()); //load current user
        $name = $user->get('name')->value; //load current user login

        $description = $form_state->getValue('desc');
        $productName = $form_state->getValue('productName');
        $quantity = $form_state->getValue('quantity');
        $cena = $form_state->getValue('amount');
        $totalAmount = $cena * $quantity;
        $imie = $form_state->getValue('imie');
        $nazwisko = $form_state->getValue('nazwisko');
        $email = $form_state->getValue('email');

        //drupal_set_message($this->t("@name, Twoje zgłoszenie zostało wysłane do systemu."), array('@name' => $name));

        $order['notifyUrl'] = 'http://localhost/platnosc';
        $order['continueUrl'] = 'http://localhost/platnosc';

        $order['customerIp'] = '127.0.0.1';
        $order['merchantPosId'] = \OpenPayU_Configuration::getMerchantPosId();
        if (!empty($description))
        $order['description'] = $description;
        else $order['description'] = 'Płatność';
        $order['currencyCode'] = 'PLN';
        $order['totalAmount'] = $totalAmount;
        $order['extOrderId'] = rand(1000, 1000000);

        $order['products'][0]['name'] = $productName;
        $order['products'][0]['unitPrice'] = $cena;
        $order['products'][0]['quantity'] = $quantity;

        // $order['products'][1]['name'] = 'Product2';
        // $order['products'][1]['unitPrice'] = 2200;
        // $order['products'][1]['quantity'] = 1;

        $order['buyer']['email'] = $email;
        $order['buyer']['phone'] = '123123123';
        $order['buyer']['firstName'] = $imie;
        $order['buyer']['lastName'] = $nazwisko;

        $response = \OpenPayU_Order::create($order);
        $odpowiedz = new Routing\TrustedRedirectResponse($response->getResponse()->redirectUri,302);
        $form_state->setResponse($odpowiedz);
    }
  private function configure(){
      \OpenPayU_Configuration::setEnvironment('sandbox');
      \OpenPayU_Configuration::setMerchantPosId('301762');
      \OpenPayU_Configuration::setSignatureKey('a4822d86cc5a7bba9ed79006c8bfe6fb');
      \OpenPayU_Configuration::setOauthClientId('301762');
      \OpenPayU_Configuration::setOauthClientSecret('310f0611b12ac30d9ad59c764b6df143');

  }
}