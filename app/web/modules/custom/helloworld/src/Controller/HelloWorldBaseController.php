<?php

namespace Drupal\helloworld\Controller;

use Drupal\Core\Controller\ControllerBase;

abstract class HelloWorldBaseController extends ControllerBase {

    protected $database;

    public function __construct() {
        // Drupalのデータベースサービスへの参照を取得
        $this->database = \Drupal::database();
        // ページキャッシュを無効にする
        \Drupal::service('page_cache_kill_switch')->trigger();
    }

    protected function renderTemplate($theme, $variables = []) {
        // レンダリング配列を生成
        // 指定されたテーマと変数を使用
        return [
            '#theme' => $theme,
            '#variables' => $variables,
        ];
    }
}