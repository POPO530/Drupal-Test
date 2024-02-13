<?php

namespace Drupal\helloworld\classes;

use Symfony\Component\HttpFoundation\RedirectResponse;

// Contact クラスの定義
class Contact {
    // プロパティの定義
    protected $connection; // データベース接続オブジェクト
    protected $table; // テーブル名
    // コンストラクタ
    public function __construct($table) {
        // データベース接続の取得
        $this->connection = \Drupal::database();
        // テーブル名の設定
        $this->table = $table;
    }
    // リストを取得するメソッド
    public function getList() {
        try {
            // クエリの生成
            $query = $this->connection->select($this->table, 'tn');
            // 取得するフィールドの指定
            $query->fields('tn');
            // クエリの実行
            $result = $query->execute();
            // 結果の取得
            $list = $result->fetchAll();
            // 結果を返す
            return $list;
        } catch(\Exception $e) {
            // エラーが発生した場合はログに記録
            \Drupal::logger('helloworld')->error("Error: " . $e->getMessage());
            // エラーを再度スローする
            throw $e;
        }
    }
    // 削除するメソッド
    public function delete($deleteId) {
        try {
            // 削除クエリの実行
            $this->connection->delete($this->table)
                ->condition('id', $deleteId)
                ->execute();
            // 削除完了メッセージの表示
            \Drupal::messenger()->addMessage('削除完了しました');
            // リダイレクトレスポンスの生成
            return new RedirectResponse('/hello');
        } catch(\Exception $e) {
            // エラーが発生した場合はログに記録
            \Drupal::logger('helloworld')->error("Error: " . $e->getMessage());
            // エラーを再度スローする
            throw $e;
        }
    }
    // データを挿入するメソッド
    public function set($data) {
        // 挿入クエリの実行
        $this->connection->insert($this->table)
            ->fields($data)
            ->execute();
    }
}