<?php

namespace Drupal\liczniki\Plugin\Block;

 
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
 
/**
 * Provides a Liczniki block with which you can dodać odczyt gdziebądź.
 *
 * @Block(
 *   id = "liczniki_block",
 *   admin_label = @Translation("Blok dodawania odczytu"),
 * )
 */
class LicznikiBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Return the form @ Form/LicznikiBlockForm.php.
    return \Drupal::formBuilder()->getForm('Drupal\liczniki\Form\LicznikiBlockForm');
  }
   
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();
 
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
   // $this->configuration['liczniki_block_settings'] = $values['liczniki_block_settings'];
    
    $this->setConfigurationValue('liczniki_block_settings', $form_state->getValue('liczniki_block_settings'));
  }
 
}