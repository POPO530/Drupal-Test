<?php

namespace Drupal\helloworld\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends ControllerBase {
    public function index(Request $request) {
        // エラーメッセージを格納する配列を初期化
        $errors = [];

        // POSTリクエストから全データを取得
        $posts = $request->request->all();

        // POSTリクエストが存在する場合のみ以下の処理を実行
        if ($posts) {
            // 各POST変数を取得し、存在しない場合はnullを設定
            $name = $posts['name'] ?? null;
            $email = $posts['email'] ?? null;
            $phone = $posts['phone'] ?? null;
            $message = $posts['message'] ?? null;

            // バリデーションセクション
            // 名前のバリデーション
            if (empty($name)) {
                $errors[] = "名前は必須です。";
            }

            // メールアドレスのバリデーション（空でないこと、有効な形式であること）
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "有効なメールアドレスを入力してください。";
            }

            // 電話番号のバリデーション
            if (empty($phone)) {
                $errors[] = "電話番号は必須です。";
            }

            // メッセージのバリデーション
            if (empty($message)) {
                $errors[] = "メッセージは必須です。";
            }

            // エラーがない場合のみデータベースへの挿入処理を実行
            if (empty($errors)) {
                // データベースへの挿入処理
                Drupal::database()->insert('helloworld_contact2')
                ->fields([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'message' => $message,
                ])
                ->execute();
            
                // 処理完了の確認メッセージをユーザーに表示
                \Drupal::messenger()->addMessage($this->t('お問い合わせありがとうございます。'));
            }
        }
        
        // テンプレートと変数を設定して返す
        // ここでエラーメッセージもテンプレートに渡される
        return [
            '#theme' => 'contact-template',
            '#vars' => [
                "errors" => $errors
            ],
        ];
    }
}