<?php

namespace Drupal\helloworld\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * お問い合わせフォームクラス。
 *
 * このクラスは、ユーザーからのお問い合わせ情報を収集するフォームを提供します。
 * フォームは、名前、メールアドレス、およびメッセージのフィールドを含んでいます。
 */
class ContactForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    // フォームの一意のIDを返します。
    return 'helloworld_contact_form';
  }

  /**
   * フォームの構造を定義します。
   *
   * このメソッドは、フォームの要素とプロパティを定義し、ユーザー入力のためのフィールドを構築します。
   * 
   * @param array $form
   *   初期フォーム構造の配列。
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   フォームの現在の状態。
   * @return array
   *   完成したフォーム構造の配列。
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // カスタムテンプレートをレンダリングする配列
    $form['custom_tab'] = [
      '#theme' => 'tab-template',
    ];
    
    // 名前入力フィールドの定義
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('名前'),
      '#required' => TRUE,
    ];

    // メールアドレス入力フィールドの定義
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('メールアドレス'),
      '#required' => TRUE,
    ];

    // メッセージ入力エリアの定義
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('内容'),
    ];

    // 送信ボタンの定義
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('送信'),
    ];

    return $form;
  }

  /**
   * フォームのバリデーションを行います。
   *
   * このメソッドは、フォームが送信される前に呼び出され、
   * 入力されたデータが特定の検証基準を満たしているかを確認します。
   *
   * @param array $form
   *   フォーム構造の配列。
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   フォームの現在の状態。
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // 名前の長さを検証
    if (strlen($form_state->getValue('name')) < 1) {
      $form_state->setErrorByName('name', $this->t('名前は1文字以上である必要があります。'));
    }

    // メールアドレスの形式を検証
    if (!\Drupal::service('email.validator')->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t('有効なメールアドレスを入力してください。'));
    }

    // ここに他の検証条件を追加することができます。
  }

  /**
   * フォームの送信処理を定義します。
   *
   * このメソッドは、フォームが送信された後に実行され、入力されたデータを取得し処理します。
   * ここでは、送信されたデータをデータベースに保存し、ユーザーに確認メッセージを表示します。
   *
   * @param array $form
   *   フォーム構造の配列。
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   フォームの現在の状態。
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // フォームからデータを取得
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
  
    // ユーザーに確認メッセージを表示
    \Drupal::messenger()->addMessage($this->t('お問い合わせありがとうございます。'));
  }
}