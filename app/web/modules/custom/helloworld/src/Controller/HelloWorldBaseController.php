<?php

namespace Drupal\helloworld\Controller;

use Drupal\Core\Controller\ControllerBase;

// 基底コントローラークラス
abstract class HelloWorldBaseController extends ControllerBase {

    // データベースへの接続
    protected $database;

    // コンストラクター
    public function __construct() {
        // Drupalのデータベースサービスへの参照を取得
        $this->database = \Drupal::database();
        // ページキャッシュを無効にする
        \Drupal::service('page_cache_kill_switch')->trigger();
    }

    // テンプレートをレンダリングするメソッド
    protected function renderTemplate($theme, $variables = []) {
        // レンダリング配列を生成して返す
        return [
            '#theme' => $theme,
            '#variables' => $variables,
        ];
    }
}