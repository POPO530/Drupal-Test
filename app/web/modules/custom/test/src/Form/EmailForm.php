<?php

namespace Drupal\test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Component\Utility\EmailValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailForm extends FormBase {

  protected $mailManager;
  protected $languageManager;
  protected $emailValidator;

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mail'),
      $container->get('language_manager'),
      $container->get('email.validator')
    );
  }

  public function __construct(MailManagerInterface $mail_manager, LanguageManagerInterface $language_manager, EmailValidatorInterface $email_validator) {
    $this->mailManager = $mail_manager;
    $this->languageManager = $language_manager;
    $this->emailValidator = $email_validator;
  }

  public function getFormId() {
    return 'email_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('E-mail address'),
      '#required' => TRUE,
    ];

    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#required' => TRUE,
    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!$this->emailValidator->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t('The e-mail address is not valid.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $to = $form_state->getValue('email');
    $params = [
      'subject' => $form_state->getValue('subject'),
      'message' => $form_state->getValue('message'),
    ];
    $langcode = $this->languageManager->getDefaultLanguage()->getId();

    $result = $this->mailManager->mail('test', 'contact_message', $to, $langcode, $params);

    \Drupal::logger('test')->notice('Mail sent result: @result', ['@result' => var_export($result, TRUE)]);

    if ($result['result'] === TRUE) {
      $this->messenger()->addMessage($this->t('Your message has been sent.'));
    } else {
      $this->messenger()->addError($this->t('There was a problem sending your message and it was not sent.'));
    }
  }
}