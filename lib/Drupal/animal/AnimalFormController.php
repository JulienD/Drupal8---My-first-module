<?php

/**
 * @file
 * Definition of Drupal\animal\AnimalFormController.
 */

namespace Drupal\animal;

use Drupal\Core\Entity\EntityFormControllerNG;
use Drupal\Core\Language\Language;

/**
 * Form controller for the user account forms.
 */
class AnimalFormController extends EntityFormControllerNG {

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, array &$form_state) {
    $element = parent::actions($form, $form_state);

    // The user account being edited.
    $animal = $this->entity;

    // The user doing the editing.
    $user = $this->currentUser();
    $element['delete']['#type'] = 'submit';
    $element['delete']['#value'] = $this->t('Cancel account');
    $element['delete']['#submit'] = array(array($this, 'editCancelSubmit'));
    $element['delete']['#access'] = $animal->id() > 1 && (($animal->id() == $user->id() && $user->hasPermission('cancel account')) || $user->hasPermission('administer users'));

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, array &$form_state) {
    $animal = $this->entity;
    $animal->save();
    $form_state['values']['aid'] = $animal->id();

    drupal_set_message($this->t('The changes have been saved.'));
  }


  /**
   * {@inheritdoc}
   */
  public function form(array $form, array &$form_state) {
    $animal = $this->entity;

    $language_interface = language(Language::TYPE_INTERFACE);

    $admin = user_access('administer users');

    // Account information.
    $form['animal'] = array(
      '#type'   => 'container',
      '#weight' => -10,
    );

    // Only show name field on registration form or user can change own username.
    $form['animal']['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => USERNAME_MAX_LENGTH,
      '#description' => $this->t('Spaces are allowed; punctuation is not allowed except for periods, hyphens, apostrophes, and underscores.'),
      '#required' => TRUE,
      '#attributes' => array('class' => array('name'), 'autocorrect' => 'off', 'autocomplete' => 'off', 'autocapitalize' => 'off',
      'spellcheck' => 'false'),
      '#default_value' => ($animal->getAnimalName() ? $animal->getAnimalName() : ''),
      '#weight' => -10,
    );

    $form['account']['status'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Status'),
      '#default_value' => TRUE,
      '#options' => array($this->t('Blocked'), $this->t('Active')),
      '#access' => $admin,
    );

    return parent::form($form, $form_state, $animal);
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntity(array $form, array &$form_state) {
    return parent::buildEntity($form, $form_state);
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::submit().
   */
  public function validate(array $form, array &$form_state) {
    parent::validate($form, $form_state);
  }

}
