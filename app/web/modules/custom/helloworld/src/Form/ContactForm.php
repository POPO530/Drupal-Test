<?php

namespace Drupal\helloworld\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ContactForm extends FormBase {

  public function getFormId() {
    // フォームの識別子を返す
    return 'helloworld_contact_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    // カスタムタブ要素を定義
    $form['custom_tab'] = [
        '#theme' => 'tab-template',
        '#variables' => 'bugfix',
    ];
    
    // 名前入力フィールドを定義
    $form['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('名前'),
        '#required' => TRUE,
    ];

    // メールアドレス入力フィールドを定義
    $form['email'] = [
        '#type' => 'email',
        '#title' => $this->t('メールアドレス'),
        '#required' => TRUE,
    ];

    // メッセージ入力エリアを定義
    $form['message'] = [
        '#type' => 'textarea',
        '#title' => $this->t('内容'),
    ];

    // 送信ボタンを定義
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('送信'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    // 名前が1文字未満の場合はエラーメッセージをセット
    if (strlen($form_state->getValue('name')) < 1) {
        $form_state->setErrorByName('name', $this->t('名前は1文字以上である必要があります。'));
    }

    // メールアドレスが有効でない場合はエラーメッセージをセット
    if (!\Drupal::service('email.validator')->isValid($form_state->getValue('email'))) {
        $form_state->setErrorByName('email', $this->t('有効なメールアドレスを入力してください。'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // フォームから値を取得
    $name = $form_state->getValue('name');
    $email = $form_state->getValue('email');
    $message = $form_state->getValue('message');
  
    // データベースにデータを挿入
    \Drupal::database()->insert('helloworld_contact')
      ->fields([
        'name' => $name,
        'email' => $email,
        'message' => $message,
      ])
      ->execute();
  
    // ユーザーに成功メッセージを表示
    \Drupal::messenger()->addMessage($this->t('お問い合わせありがとうございます。'));
  }
}