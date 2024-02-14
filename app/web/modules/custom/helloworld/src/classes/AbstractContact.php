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

    // 抽象メソッド：リストを取得するためのメソッド
    abstract public function getList();

    // 抽象メソッド：条件に基づいて id を取得するメソッド
    abstract public function getId($condition = []);

    // 抽象メソッド：指定された id のエントリを削除するメソッド
    abstract public function delete($deleteId);
    
    // 抽象メソッド：データをセットするメソッド
    abstract public function set($data);
}