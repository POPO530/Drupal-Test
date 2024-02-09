<?php

namespace Drupal\helloworld\Controller;

use Drupal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContactController extends HelloWorldBaseController {

    public function index(Request $request) {
        $errors = [];
        $posts = $request->request->all();

        if ($posts) {
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

        if (is_array($errors) && count($errors) !== 0) {
            foreach ($errors as $error) {
                Drupal::messenger()->addError($error);
            }
        }

        return $this->renderTemplate('contact-template', []);
    }

    public function display() {
        $query = $this->database->select('helloworld_contact2', 'hc')
            ->fields('hc', ['id', 'name', 'email', 'phone', 'message']);

        $results = $query->execute()->fetchAll();
        
        return $this->renderTemplate('contact-display-template', $results);
    }

    public function edit($contact_id, Request $request) {
        $errors = [];

        if ($request->isMethod('POST')) {
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

                Drupal::messenger()->addMessage($this->t('更新完了しました。'));
                return new RedirectResponse('/test/contact/display');
            }
        }

        $query = $this->database->select('helloworld_contact2', 'hc')
            ->fields('hc')
            ->condition('id', $contact_id);
        $contact = $query->execute()->fetchAssoc();

        if (!$contact) {
            Drupal::messenger()->addError('Contact not found.');
            return new RedirectResponse('/test/contact/display');
        }

        if (is_array($errors) && count($errors) !== 0) {
            foreach ($errors as $error) {
                Drupal::messenger()->addError($error);
            }
        }

        return $this->renderTemplate('contact-edit-template', $contact);
    }

    public function delete($contact_id) {
        $query = $this->database->select('helloworld_contact2', 'hc')
            ->fields('hc')
            ->condition('id', $contact_id);
        $contact = $query->execute()->fetchAssoc();

        if (!$contact) {
            Drupal::messenger()->addError('Contact not found.');
            return new RedirectResponse('/test/contact/display');
        }

        $this->database->delete('helloworld_contact2')
            ->condition('id', $contact_id)
            ->execute();

        Drupal::messenger()->addMessage('削除完了しました');
        return new RedirectResponse('/test/contact/display');
    }
}