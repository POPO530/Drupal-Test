<?php

namespace Drupal\helloworld\Controller;

use Drupal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContactController extends HelloWorldBaseController {

    public function index(Request $request) {
        // 初期エラー配列とリクエストからの全入力データを取得
        $errors = [];
        $posts = $request->request->all();
    
        // 入力データが存在する場合の処理
        if ($posts) {
            // 各フィールドのデータを取得（存在しない場合はnull）
            $name = $posts['name'] ?? null;
            $email = $posts['email'] ?? null;
            $phone = $posts['phone'] ?? null;
            $message = $posts['message'] ?? null;
    
            // 名前のバリデーション
            if (empty($name)) {
                $errors[] = "名前は必須です。";
            } elseif (strlen($name) > 50) {
                $errors[] = "名前は50文字以下である必要があります。";
            }
    
            // メールアドレスのバリデーション
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "有効なメールアドレスを入力してください。";
            }
    
            // 電話番号のバリデーション
            if (empty($phone)) {
                $errors[] = "電話番号は必須です。";
            } elseif (!preg_match('/^\d{10,15}$/', $phone)) {
                $errors[] = "電話番号は10〜15桁の数字である必要があります。";
            }
    
            // メッセージのバリデーション
            if (empty($message)) {
                $errors[] = "メッセージは必須です。";
            } elseif (strlen($message) < 10 || strlen($message) > 500) {
                $errors[] = "メッセージは10文字以上500文字以下である必要があります。";
            }
    
            // バリデーションエラーがない場合、データベースに保存し、成功メッセージを表示
            if (empty($errors)) {
                $this->database->insert('helloworld_contact2')
                    ->fields([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'message' => $message,
                    ])
                    ->execute();
                Drupal::messenger()->addMessage($this->t('お問い合わせありがとうございます。'));
            }
        }
    
        // バリデーションエラーがある場合、それぞれのエラーを表示
        if (is_array($errors) && count($errors) !== 0) {
            foreach ($errors as $error) {
                Drupal::messenger()->addError($error);
            }
        }
    
        // テンプレートを使用してレンダリング
        return $this->renderTemplate('contact-template', $this->t('bugfix'));
    }

    public function display() {
        // データベースからhelloworld_contact2テーブルを選択し、必要なフィールドを指定
        $query = $this->database->select('helloworld_contact2', 'hc')
            ->fields('hc', ['id', 'name', 'email', 'phone', 'message']);
    
        // クエリを実行して結果を取得
        $results = $query->execute()->fetchAll();
        
        // 取得した結果をcontact-display-templateテンプレートに渡してレンダリング
        return $this->renderTemplate('contact-display-template', $results);
    }

    public function edit($contact_id, Request $request) {
        // 初期エラー配列を設定
        $errors = [];
    
        // POSTメソッドのリクエストの場合の処理
        if ($request->isMethod('POST')) {
            // リクエストから入力データを取得
            $posts = $request->request->all();
            $name = $posts['name'] ?? null;
            $email = $posts['email'] ?? null;
            $phone = $posts['phone'] ?? null;
            $message = $posts['message'] ?? null;
    
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
                $this->database->update('helloworld_contact2')
                    ->fields([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'message' => $message,
                    ])
                    ->condition('id', $contact_id)
                    ->execute();
    
                // 更新完了メッセージを表示し、表示ページにリダイレクト
                Drupal::messenger()->addMessage($this->t('更新完了しました。'));
                return new RedirectResponse('/test/contact/display');
            }
        }
    
        // 指定された連絡先IDに基づいて連絡先情報を取得
        $query = $this->database->select('helloworld_contact2', 'hc')
            ->fields('hc')
            ->condition('id', $contact_id);
        $contact = $query->execute()->fetchAssoc();
    
        // 連絡先が見つからない場合の処理
        if (!$contact) {
            Drupal::messenger()->addError('Contact not found.');
            return new RedirectResponse('/test/contact/display');
        }
    
        // バリデーションエラーがある場合、エラーメッセージを表示
        if (is_array($errors) && count($errors) !== 0) {
            foreach ($errors as $error) {
                Drupal::messenger()->addError($error);
            }
        }
    
        // 編集フォームをレンダリングし、既存の連絡先情報を表示
        return $this->renderTemplate('contact-edit-template', $contact);
    }

    public function delete($contact_id) {
        // データベースから指定されたIDを持つ連絡先情報を検索
        $query = $this->database->select('helloworld_contact2', 'hc')
            ->fields('hc')
            ->condition('id', $contact_id);
        $contact = $query->execute()->fetchAssoc();
    
        // 連絡先が見つからない場合の処理
        if (!$contact) {
            Drupal::messenger()->addError('Contact not found.');
            return new RedirectResponse('/test/contact/display');
        }
    
        // 連絡先が存在する場合、そのIDを使って連絡先情報をデータベースから削除
        $this->database->delete('helloworld_contact2')
            ->condition('id', $contact_id)
            ->execute();
    
        // 削除完了のメッセージを表示し、連絡先表示ページへリダイレクト
        Drupal::messenger()->addMessage('削除完了しました');
        return new RedirectResponse('/test/contact/display');
    }
}