<?php

namespace Drupal\helloworld\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContactController extends ControllerBase {
    public function index(Request $request) {
        // 初期化されたエラー配列
        $errors = [];
    
        // POSTリクエストからデータを取得
        $posts = $request->request->all();
    
        // POSTデータが存在する場合のみ処理を実行
        if ($posts) {
            // 各フィールドの値を取得。存在しない場合はnullに設定
            $name = $posts['name'] ?? null;
            $email = $posts['email'] ?? null;
            $phone = $posts['phone'] ?? null;
            $message = $posts['message'] ?? null;
    
            // 名前のバリデーション: 空でないことと文字数制限
            if (empty($name)) {
                $errors[] = "名前は必須です。";
            } elseif (strlen($name) > 50) {
                $errors[] = "名前は50文字以下である必要があります。";
            }
    
            // メールアドレスのバリデーション: 空でないことと有効な形式
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "有効なメールアドレスを入力してください。";
            }
    
            // 電話番号のバリデーション: 空でないことと文字数制限
            if (empty($phone)) {
                $errors[] = "電話番号は必須です。";
            } elseif (!preg_match('/^\d{10,15}$/', $phone)) {
                $errors[] = "電話番号は10〜15桁の数字である必要があります。";
            }
    
            // メッセージのバリデーション: 空でないことと文字数制限
            if (empty($message)) {
                $errors[] = "メッセージは必須です。";
            } elseif (strlen($message) < 10 || strlen($message) > 500) {
                $errors[] = "メッセージは10文字以上500文字以下である必要があります。";
            }
    
            // エラーがない場合、データベースにデータを挿入
            if (empty($errors)) {
                Drupal::database()->insert('helloworld_contact2')
                ->fields([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'message' => $message,
                ])
                ->execute();
            
                // ユーザーに確認メッセージを表示
                Drupal::messenger()->addMessage($this->t('お問い合わせありがとうございます。'));
            }
        }
        
        // テンプレートとエラー情報を返す
        return [
            '#theme' => 'contact-template',
            '#vars' => [
                "errors" => $errors
            ],
        ];
    }

    public function display() {
        // データベースから 'helloworld_contact2' テーブルを選択し、
        // 必要なフィールド (id, name, email, phone, message) を取得するクエリを作成
        $query = Drupal::database()->select('helloworld_contact2', 'hc')
            ->fields('hc', ['id', 'name', 'email', 'phone', 'message']);
    
        // クエリを実行し、結果を取得
        $results = $query->execute()->fetchAll();
        
        // 結果を 'contact-display-template' テンプレートに渡し、
        // 各コンタクトの情報を表示するために必要なデータを提供
        return [
            '#theme' => 'contact-display-template',
            '#contacts' => $results,
        ];
    }

    public function edit($contact_id, Request $request) {
        // Drupalのデータベース接続を取得
        $connection = Drupal::database();
    
        // リクエストがPOSTの場合、フォームからのデータを処理
        if ($request->isMethod('POST')) {
            // POSTされたデータを取得
            $posts = $request->request->all();
    
            // 各フィールドの値を変数に割り当て
            $name = $posts['name'] ?? null;
            $email = $posts['email'] ?? null;
            $phone = $posts['phone'] ?? null;
            $message = $posts['message'] ?? null;
    
            // バリデーションエラーを格納する配列
            $errors = [];
    
            // 名前のバリデーション: 空でないこと、文字数制限
            if (empty($name)) {
                $errors[] = "名前は必須です。";
            } elseif (strlen($name) > 50) {
                $errors[] = "名前は50文字以下である必要があります。";
            }
    
            // メールアドレスのバリデーション: 空でないこと、有効なメールアドレスであること
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "有効なメールアドレスを入力してください。";
            }
    
            // 電話番号のバリデーション: 空でないこと、数字のみ、文字数制限
            if (empty($phone)) {
                $errors[] = "電話番号は必須です。";
            } elseif (!preg_match('/^\d{10,15}$/', $phone)) {
                $errors[] = "電話番号は10〜15桁の数字である必要があります。";
            }
    
            // メッセージのバリデーション: 空でないこと、文字数制限
            if (empty($message)) {
                $errors[] = "メッセージは必須です。";
            } elseif (strlen($message) < 10 || strlen($message) > 500) {
                $errors[] = "メッセージは10文字以上500文字以下である必要があります。";
            }
    
            // バリデーションエラーがなければ、データベースを更新
            if (empty($errors)) {
                $connection->update('helloworld_contact2')
                    ->fields([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'message' => $message,
                    ])
                    ->condition('id', $contact_id)
                    ->execute();
    
                // 更新完了のメッセージをユーザーに表示
                Drupal::messenger()->addMessage($this->t('更新完了しました。'));
                // ページキャッシュを無効化
                Drupal::service('page_cache_kill_switch')->trigger();
        
                // 更新後のページへリダイレクト
                return new RedirectResponse('/test/contact/display');
            }
        }
    
        // 指定されたIDのコンタクト情報をデータベースから取得
        $query = $connection->select('helloworld_contact2', 'hc')
            ->fields('hc')
            ->condition('id', $contact_id);
        $contact = $query->execute()->fetchAssoc();
    
        // コンタクトが見つからない場合、エラーメッセージを表示し、リダイレクト
        if (!$contact) {
            Drupal::messenger()->addError('Contact not found.');
            return new RedirectResponse('/test/contact/display');
        }
    
        // 編集用のフォームを表示するためのデータをテンプレートに渡す
        return [
            '#theme' => 'contact-edit-template',
            '#contact' => $contact,
        ];
    }

    public function delete($contact_id) {
        // Drupalのデータベース接続を取得
        $connection = Drupal::database();
    
        // 指定されたIDを持つコンタクトが存在するか確認するためのクエリを実行
        $query = $connection->select('helloworld_contact2', 'hc')
            ->fields('hc')
            ->condition('id', $contact_id);
        $contact = $query->execute()->fetchAssoc();
    
        // 指定されたIDのコンタクトが存在しない場合
        if (!$contact) {
            // ユーザーにエラーメッセージを表示し、リダイレクト
            Drupal::messenger()->addError('Contact not found.');
            return new RedirectResponse('/test/contact/display');
        }
    
        // コンタクトが存在する場合、指定されたIDを持つレコードを削除
        $connection->delete('helloworld_contact2')
            ->condition('id', $contact_id)
            ->execute();
    
        // 削除完了のメッセージをユーザーに表示し、リダイレクト
        Drupal::messenger()->addMessage('削除完了しました');
        return new RedirectResponse('/test/contact/display');
    }
}