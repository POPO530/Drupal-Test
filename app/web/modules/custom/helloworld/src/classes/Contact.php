<?php

// Drupal\helloworld\classes ネームスペースに Contact クラスを定義します。
namespace Drupal\helloworld\classes;

// Symfony の RedirectResponse クラスをインポートします。
use Symfony\Component\HttpFoundation\RedirectResponse;

// Contact クラスの定義
class Contact extends AbstractContact {
    // リストを取得するメソッド
    public function getList() {
        try {
            // テーブルから全てのフィールドを取得するためのクエリを作成します。
            $query = $this->connection->select($this->table, 'tn');
            $query->fields('tn');
            // クエリを実行して結果を取得し、フェッチします。
            $result = $query->execute();
            return $result->fetchAll(); // 取得した結果を返します。
        } catch(\Exception $e) {
            // エラーログを記録します。
            \Drupal::logger('helloworld')->error("Error: " . $e->getMessage());
            // エラー処理
            return new RedirectResponse('/dice'); // エラー時にはリダイレクトします。
        }
    }

    // 条件に基づいて id を取得するメソッド
    public function getId($condition = []) {
        try {
            // テーブルから id を取得するためのクエリを作成します。
            $query = $this->connection->select($this->table, 'tn');
            $query->addField('tn', 'id');
            // 条件が指定されている場合は条件を追加します。
            if (!empty($condition)) {
                foreach ($condition as $field => $value) {
                    $query->condition($field, $value);
                }
            }
            // クエリを実行し、最初のフィールドの値を取得します。
            $result = $query->execute();
            return $result->fetchField(); // 取得した id を返します。
        } catch(\Exception $e) {
            // エラーログを記録します。
            \Drupal::logger('helloworld')->error("Error: " . $e->getMessage());
            // エラー処理
            return null;
        }
    }

    // 指定された id のエントリを削除するメソッド
    public function delete($deleteId) {
        try {
            // 指定された id のエントリを削除するためのクエリを実行します。
            $this->connection->delete($this->table)
                ->condition('id', $deleteId)
                ->execute();
            // 削除が成功した場合はメッセージを表示します。
            \Drupal::messenger()->addMessage('削除完了しました');
            // リダイレクトレスポンスを返します。
            return new RedirectResponse('/hello');
        } catch(\Exception $e) {
            // エラーログを記録します。
            \Drupal::logger('helloworld')->error("Error: " . $e->getMessage());
            // エラー処理
            \Drupal::messenger()->addError('削除中にエラーが発生しました');
            // リダイレクトレスポンスを返します。
            return new RedirectResponse('/dice');
        }
    }

    // データをセットするメソッド
    public function set($data) {
        try {
            // データを挿入するためのクエリを実行します。
            $this->connection->insert($this->table)
                ->fields($data)
                ->execute();
        } catch(\Exception $e) {
            // エラーログを記録します。
            \Drupal::logger('helloworld')->error("Error: " . $e->getMessage());
            // エラー処理
            \Drupal::messenger()->addError('データの挿入中にエラーが発生しました');
            // リダイレクトレスポンスを返します。
            return new RedirectResponse('/dice');
        }
    }
}