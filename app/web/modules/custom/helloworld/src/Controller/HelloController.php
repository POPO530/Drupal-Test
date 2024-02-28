<?php

namespace Drupal\helloworld\Controller;

use Drupal\helloworld\classes\Contact;

class HelloController extends HelloWorldBaseController {

    public function kuji() {
        return $this->renderTemplate('kuji-template', 'bugfix');
    }

    /**
     * コンテンツを処理するメソッド
     * このメソッドでは、Contact クラスを使用してデータベースの操作を行います。
     * まず、指定されたテーブル名を使って Contact オブジェクトを作成します。
     * 次に、ランダムな回数だけエントリを削除します。
     * その後、新しいデータを挿入するための連想配列を作成し、データベースに挿入します。
     * 最後に、テンプレートをレンダリングして返します。
     */
    public function content() {
        // Drupal のサービスコンテナーからデータベース接続サービスを取得
        $connection = \Drupal::database();
        
        // Contact クラスのインスタンスを作成し、データベース接続サービスとテーブル名を渡す
        $contact = new Contact($connection, "helloworld_contact2");

        $contact->delete($contact->getId());

        $contactData = [
            'name' => $this->generateRandomName(), // ランダムな名前を生成
            'email' => $this->generateRandomEmail(), // ランダムなメールアドレスを生成
            'phone' => $this->generateRandomPhoneNumber(), // ランダムな電話番号を生成
            'message' => $this->generateRandomMessage() // ランダムなメッセージを生成
        ];

        $contact->set($contactData);

        return $this->renderTemplate('my-template', 'bugfix');
    }

    /**
     * ランダムな名前を生成するメソッド
     */
    private function generateRandomName() {
        // ランダムな名前の生成
        $names = ['John Doe', 'Jane Smith', 'Michael Johnson', 'Emily Brown'];
        return $names[array_rand($names)];
    }

    /**
     * ランダムなメールアドレスを生成するメソッド
     */
    private function generateRandomEmail() {
        // ランダムなメールアドレスの生成
        $domains = ['example.com', 'test.org', 'mail.net'];
        $username = strtolower($this->generateRandomName());
        $domain = $domains[array_rand($domains)];
        return $username . '@' . $domain;
    }

    /**
     * ランダムな電話番号を生成するメソッド
     */
    private function generateRandomPhoneNumber() {
        // ランダムな番号の生成（例：123-456-7890）
        $phoneNumber = sprintf("%03d-%03d-%04d", rand(0, 999), rand(0, 999), rand(0, 9999));
        return $phoneNumber;
    }

    /**
     * ランダムなメッセージを生成するメソッド
     */
    private function generateRandomMessage() {
        // ランダムなメッセージの生成
        $messages = ['Hello, world!', 'This is a test message.', 'Lorem ipsum dolor sit amet.'];
        return $messages[array_rand($messages)];
    }

    /**
     * JSONリストを取得するメソッド
     */
    public function getJson() {
        // Drupal のサービスコンテナーからデータベース接続サービスを取得
        $connection = \Drupal::database();
        
        // Contact クラスのインスタンスを作成し、データベース接続サービスとテーブル名を渡す
        $contact = new Contact($connection, "helloworld_contact2");
        
        $list = $contact->getJsonList();
        return $list;
    }

    /**
     * 提出内容を表示するメソッド
     */
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