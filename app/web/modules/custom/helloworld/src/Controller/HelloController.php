<?php

namespace Drupal\helloworld\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Hello Worldを表示するコントローラクラス。
 *
 * このクラスは、特定のURLパス（'/dice', '/hello', '/hello/submissions' など）へのアクセス時にコンテンツを提供し、
 * ページのレンダリングを管理します。
 */
class HelloController extends ControllerBase {
  /**
   * '/hello' パスのコンテンツを生成するメソッド。
   * 
   * このメソッドは '/hello' パスにアクセスされた際に呼び出され、
   * 'my-template' テーマを使用してページコンテンツを返します。
   *
   * @return array
   *   ページのコンテンツ領域用のレンダリング可能な配列。
   *   '#theme' は使用するテーマの指定、'#test_var' はテーマに渡す変数。
   */
  public function content() {
    return [
      '#theme' => 'my-template', // 'my-template' テーマを使用
      '#test_var' => $this->t('Test Value'), // 'test_var' 変数にテキストを設定
    ];
  }

  /**
   * '/hello/submissions' パスのフォーム送信データを降順で表示するメソッド。
   * 
   * このメソッドは、お問い合わせフォームから送信されたデータをデータベースから取得し、
   * テーブル形式で表示します。データベースからの取得はDrupalのデータベースAPIを使用して行います。
   * 取得されたデータはID列を基準に降順で表示され、Drupalのテーブルテーマ機能を使用して表示されます。
   *
   * @return array
   *   データベースから取得した送信データのテーブルを表示するためのレンダリング配列。
   */
  public function showSubmissions() {
    \Drupal::service('page_cache_kill_switch')->trigger(); // ページキャッシュを無効化

    // テーブルのヘッダーを定義
    $header = [
      ['data' => $this->t('ID')],       // ID列
      ['data' => $this->t('Name')],     // 名前列
      ['data' => $this->t('Email')],    // メールアドレス列
      ['data' => $this->t('Message')],  // メッセージ列
    ];

    // データベースから送信データを取得（ID列で降順に並び替え）
    $rows = [];
    $query = \Drupal::database()->select('helloworld_contact', 'hc')
      ->fields('hc', ['id', 'name', 'email', 'message'])
      ->orderBy('id', 'DESC') // ID列で降順に並び替え
      ->execute();

    // 取得したデータを行に追加
    foreach ($query as $row) {
      $rows[] = [
        'data' => [(string) $row->id, $row->name, $row->email, $row->message],
      ];
    }

    // テーブル構築と返却
    $build['table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No submissions available.'), // データがない場合のメッセージ
    ];

    return $build;
  }
}