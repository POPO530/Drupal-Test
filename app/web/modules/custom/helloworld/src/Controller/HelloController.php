<?php

namespace Drupal\helloworld\Controller;

use Drupal\helloworld\classes\Contact;

class HelloController extends HelloWorldBaseController {

    /**
     * コンテンツを処理するメソッド
     * このメソッドでは、Contact クラスを使用してデータベースの操作を行います。
     * まず、指定されたテーブル名を使って Contact オブジェクトを作成します。
     * 次に、getList メソッドを使ってデータベースからリストを取得します。
     * 次に、delete メソッドを使用して id が 5 のエントリを削除します。
     * その後、新しいデータを挿入するために、データの連想配列を作成し、set メソッドを使用して挿入します。
     * 最後に、リストとともにテンプレートをレンダリングして返します。
     */
    public function content() {
        // Contact クラスのインスタンスを作成し、指定されたテーブル名を渡します
        $contact = new Contact("helloworld_contact2");

        // リストを取得します
        //$list = $contact->getList();

        // ランダムな回数だけエントリを削除します
        for ($i = 0; $i < rand(10, 100); $i++) {
            // id がランダムに選択されたエントリを削除します
            $contact->delete(rand(1, $contact->getId()));
        }

        // // 新しいデータを挿入するための連想配列を作成します
        // $contactData = [
        //     'name' => 'John Doe',
        //     'email' => 'john@example.com',
        //     'phone' => '123-456-7890',
        //     'message' => 'Hello, world!'
        // ];

        // 連想配列を使って新しいデータを挿入します
        //$contact->set($contactData);

        // テンプレートをレンダリングしてリストと共に返します
        return $this->renderTemplate('my-template', 'bugfix');
    }

    public function getJson() {
        $contact = new Contact("helloworld_contact2");
        $list = $contact->getJsonList();
        return $list;
    }

    public function showSubmissions() {
        // テーブルのヘッダーを定義
        $header = [
            'data1' => $this->t('ID'),
            'data2' => $this->t('Name'),
            'data3' => $this->t('Email'),
            'data4' => $this->t('Message'),
        ];
    
        // データベースからのデータを格納するための配列を初期化
        $rows = [];
        // データベースクエリを定義
        $query = $this->database->select('helloworld_contact', 'hc')
            ->fields('hc', ['id', 'name', 'email', 'message'])
            ->orderBy('id', 'DESC');
    
        // クエリを実行し、結果を$rowsに追加
        foreach ($query->execute() as $row) {
            $rows[] = [
                'data' => [(string) $row->id, $row->name, $row->email, $row->message],
            ];
        }
    
        // 表示用の配列を初期化
        $build = [];
        // カスタムテンプレートを使用してタブを作成
        $build['custom_tab'] = $this->renderTemplate('tab-template', $this->t('bugfix'));
        // テーブルの設定を追加
        $build['table'] = [
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => $this->t('No submissions available.'),
        ];
    
        // 作成した配列を返す
        return $build;
    }
}