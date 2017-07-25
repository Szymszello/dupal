<?php
/**
*MOCNY PROTOPTYP 
*
*
*
*
 * @file
 * Contains \Drupal\resume\Form\ResumeForm.
 */
namespace Drupal\resume\Form;

//include_once 'C:\inetpub\wwwroot\drupal2\core\lib\Drupal\Core\ebiSoapEBOK.php';

use Drupal\Core\ebiSoapEBOK;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ResumeForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'resume_form';
  }

  /**
  name pobierz sobie z kontekstu!
  
  1
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['imie'] = array(
      '#type' => 'textfield',
      '#title' => t('Imie'),
      '#required' => TRUE,
    );

    $form['nazwisko'] = array(
      '#type' => 'textfield',
      '#title' => t('Nazwisko'),
      '#required' => TRUE,
    );
	
	$form['pesel'] = array(
      '#type' => 'textfield',
      '#title' => t('PESEL.'),
	  '#required' => TRUE,
    );
	

    $form['id_klienta_erp'] = array (
      '#type' => 'textfield',
      '#title' => t('Numer Klienta MPWIK'),
      '#required' => TRUE,
    );
	
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    )
	;
	$form['klient_zgoda'] = array (
      '#type' => 'radios',
      '#title' => ('Zgadasz sie na przetwarzanie danych i potwierdzasz <a href = "http://google.pl">regulamin</a>'),
      '#options' => array(
        'Yes' =>t('Tak'),
        'No' =>t('Nie')
      ),
    );
    return $form;
  }

  //TODO ERROR HANDLER
  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {

	  // if(strlen($form_state->getValue('klient_pesel')) != 11) {
		  // $form_state->setErrorByName('klient_pesel',$this->t('Zły PESEL'));
	  // }
//	  if(preg_match('/@/' ,$form_state->getValue('klient_email'))){
//		  $form_state ->setErrorByName('klient_email', $this -> t('Podaj prawidłowy email'));
//	  }
	  if($form_state -> getValue('klient_zgoda') != 'Yes') {
		   $form_state->setErrorByName('klient_zgoda',$this->t('Musisz zaakceptowac zeby sie zarejestrowac'));
	  }
}
  /**
   * {@inheritdoc}
   */
   
   //TODO refraktoryzacja kodu go kupa
  public function submitForm(array &$form, FormStateInterface $form_state) {
   drupal_set_message($this->t('@imie ,Twój formularz jest w trakcie weryfikacji', array('@imie' => $form_state->getValue('imie'))));
   $soap = new ebiSoapEBOK;
   $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id()); //load current user
	$name = $user->get('name')->value; //load current user login 
	$temp_arr = $form_state->getValues();
   $soap_array = [
   'name' => $name,
   'imie' => $temp_arr['imie'],
   'pesel' =>$temp_arr['pesel'],
   'nazwisko' => $temp_arr['nazwisko'],
   'id_klienta_erp' => $temp_arr['id_klienta_erp'],
   ];
   $soap -> wyslij_dane_klienta($soap_array);
   error_log(print_r($soap_array,1));

   }
}