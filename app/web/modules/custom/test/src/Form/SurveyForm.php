<?php

namespace Drupal\test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SurveyForm extends FormBase {

  public function getFormId() {
    return 'survey_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];

    for ($i = 1; $i <= 4; $i++) {
      $form['answer'.$i] = [
        '#type' => 'radios',
        '#title' => $this->t('Question @num', ['@num' => $i]),
        '#options' => ['Yes' => $this->t('Yes'), 'No' => $this->t('No')],
        '#required' => TRUE,
      ];
    }

    $form['answer5'] = [
      '#type' => 'radios',
      '#title' => $this->t('Question 5'),
      '#options' => [
        'Option 1' => $this->t('Option 1'),
        'Option 2' => $this->t('Option 2'),
        'Option 3' => $this->t('Option 3'),
        'Option 4' => $this->t('Option 4'),
        'Option 5' => $this->t('Option 5'),
      ],
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = \Drupal::database();
    $connection->insert('survey_results')
      ->fields([
        'name' => $form_state->getValue('name'),
        'answer1' => $form_state->getValue('answer1'),
        'answer2' => $form_state->getValue('answer2'),
        'answer3' => $form_state->getValue('answer3'),
        'answer4' => $form_state->getValue('answer4'),
        'answer5' => $form_state->getValue('answer5'),
      ])
      ->execute();
  
    // Use the Messenger service for setting a message.
    \Drupal::messenger()->addMessage($this->t('Thank you for your submission.'));
  }
}