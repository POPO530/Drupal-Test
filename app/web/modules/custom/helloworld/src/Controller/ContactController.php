<?php

namespace Drupal\helloworld\Controller;

use Drupal; // Drupal名前空間をインポート
use Symfony\Component\HttpFoundation\Request; // SymfonyのRequestクラスをインポート
use Symfony\Component\HttpFoundation\RedirectResponse; // SymfonyのRedirectResponseクラスをインポート

class ContactController extends HelloWorldBaseController {

    public function index(Request $request) {
        if ($request->isMethod('POST')) { // リクエストがPOSTメソッドかどうかを確認
            $errors = $this->validateContactForm($request->request->all()); // フォームデータをバリデーションする
            
            if (empty($errors)) { // エラーがない場合
                $this->saveContact($request->request->all()); // フォームデータを保存する
                Drupal::messenger()->addMessage($this->t('お問い合わせありがとうございます。')); // 成功メッセージを表示
            } else { // エラーがある場合
                foreach ($errors as $error) { // 各エラーメッセージに対して
                    Drupal::messenger()->addError($error); // エラーメッセージを表示
                }
            }
        }
        
        return $this->renderTemplate('contact-template', $this->t('bugfix')); // テンプレートをレンダリング
    }

    public function display() {
        $results = $this->getContactInformation(); // 連絡先情報を取得
        return $this->renderTemplate('contact-display-template', $results); // テンプレートをレンダリング
    }

    public function edit($contact_id, Request $request) {
        $errors = $this->validateContactForm($request->request->all()); // フォームデータをバリデーションする

        if (empty($errors)) { // エラーがない場合
            $this->updateContact($contact_id, $request->request->all()); // 連絡先を更新
            Drupal::messenger()->addMessage($this->t('更新完了しました。')); // 成功メッセージを表示
            return new RedirectResponse('/test/contact/display'); // 表示ページにリダイレクト
        } else { // エラーがある場合
            foreach ($errors as $error) { // 各エラーメッセージに対して
                Drupal::messenger()->addError($error); // エラーメッセージを表示
            }
        }

        $contact = $this->getContactById($contact_id); // 指定された連絡先IDの情報を取得
        return $this->renderTemplate('contact-edit-template', $contact); // 編集テンプレートをレンダリング
    }

    public function delete($contact_id) {
        $this->deleteContact($contact_id); // 連絡先を削除
        Drupal::messenger()->addMessage('削除完了しました'); // 削除完了メッセージを表示
        return new RedirectResponse('/test/contact/display'); // 表示ページにリダイレクト
    }

    // フォームデータをバリデーションするメソッド
    private function validateContactForm($formData) {
        $errors = []; // エラーメッセージを格納する配列を初期化

        if (empty($formData['name'])) { // 名前が空かどうかを確認
            $errors[] = "名前は必須です。"; // エラーメッセージを配列に追加
        } elseif (strlen($formData['name']) > 50) { // 名前が50文字を超えているかどうかを確認
            $errors[] = "名前は50文字以下である必要があります。"; // エラーメッセージを配列に追加
        }

        if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) { // メールアドレスが空か、または有効でないかどうかを確認
            $errors[] = "有効なメールアドレスを入力してください。"; // エラーメッセージを配列に追加
        }

        if (empty($formData['phone'])) { // 電話番号が空かどうかを確認
            $errors[] = "電話番号は必須です。"; // エラーメッセージを配列に追加
        } elseif (!preg_match('/^\d{10,15}$/', $formData['phone'])) { // 電話番号が10〜15桁の数字であるかどうかを確認
            $errors[] = "電話番号は10〜15桁の数字である必要があります。"; // エラーメッセージを配列に追加
        }

        if (empty($formData['message'])) { // メッセージが空かどうかを確認
            $errors[] = "メッセージは必須です。"; // エラーメッセージを配列に追加
        } elseif (strlen($formData['message']) < 10 || strlen($formData['message']) > 500) { // メッセージが10文字未満または500文字を超えているかどうかを確認
            $errors[] = "メッセージは10文字以上500文字以下である必要があります。"; // エラーメッセージを配列に追加
        }

        return $errors; // エラーメッセージの配列を返す
    }

    // データベースに連絡先情報を保存するメソッド
    private function saveContact($formData) {
        $this->database->insert('helloworld_contact2') // 'helloworld_contact2' テーブルに挿入
            ->fields([ // フィールドを指定
                'name' => $formData['name'], // 名前
                'email' => $formData['email'], // メールアドレス
                'phone' => $formData['phone'], // 電話番号
                'message' => $formData['message'], // メッセージ
            ])
            ->execute(); // 実行
    }

    // 連絡先情報を取得するメソッド
    private function getContactInformation() {
        $query = $this->database->select('helloworld_contact2', 'hc') // 'helloworld_contact2' テーブルを選択
            ->fields('hc', ['id', 'name', 'email', 'phone', 'message']) // フィールドを指定
            ->orderBy('hc.id', 'DESC'); // idフィールドを降順で並び替え
        return $query->execute()->fetchAll(); // クエリを実行し、結果を取得
    }

    // 連絡先を更新するメソッド
    private function updateContact($contact_id, $formData) {
        $this->database->update('helloworld_contact2') // 'helloworld_contact2' テーブルを更新
            ->fields([ // 更新するフィールドを指定
                'name' => $formData['name'], // 名前
                'email' => $formData['email'], // メールアドレス
                'phone' => $formData['phone'], // 電話番号
                'message' => $formData['message'], // メッセージ
            ])
            ->condition('id', $contact_id) // 条件を指定
            ->execute(); // 実行
    }

    // 指定されたIDの連絡先情報を取得するメソッド
    private function getContactById($contact_id) {
        $query = $this->database->select('helloworld_contact2', 'hc') // 'helloworld_contact2' テーブルを選択
            ->fields('hc') // フィールドを指定
            ->condition('id', $contact_id); // 条件を指定
        return $query->execute()->fetchAssoc(); // クエリを実行し、結果を取得
    }

    // 指定されたIDの連絡先を削除するメソッド
    private function deleteContact($contact_id) {
        $this->database->delete('helloworld_contact2') // 'helloworld_contact2' テーブルから削除
            ->condition('id', $contact_id) // 条件を指定
            ->execute(); // 実行
    }
}