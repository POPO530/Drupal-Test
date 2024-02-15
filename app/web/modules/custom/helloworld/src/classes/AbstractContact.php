<?php

// Drupal\helloworld\classes ネームスペースに AbstractContact 抽象クラスを定義します。
namespace Drupal\helloworld\classes;

// AbstractContact クラスの定義
abstract class AbstractContact {
    // データベース接続オブジェクトを保持するプロパティ
    protected $connection;
    // テーブル名を保持するプロパティ
    protected $table;

    // コンストラクター
    public function __construct($table = null) {
        // テーブル名が提供されている場合
        if ($table) {
            // データベース接続オブジェクトを取得
            $this->connection = \Drupal::database();
            // テーブル名を設定
            $this->table = $table;
        }
    }

    // JSON形式のリストを取得するメソッド（サブクラスで実装される）
    abstract public function getJsonList();

    // 条件に基づいてIDを取得するメソッド（サブクラスで実装される）
    abstract public function getId($condition = []);

    // 指定されたIDのエントリを削除するメソッド（サブクラスで実装される）
    abstract public function delete($deleteId);
    
    // データをセットするメソッド（サブクラスで実装される）
    abstract public function set($data);
}