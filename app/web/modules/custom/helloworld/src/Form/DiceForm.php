<?php

namespace Drupal\helloworld\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DiceForm extends FormBase {

    public function getFormId() {
        // このフォームの識別子を返す
        return 'helloworld_dice_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        // カスタムタブを設定する
        $form['custom_tab'] = [
            '#theme' => 'tab-template',
            '#variables' => 'bugfix',
        ];
        
        // ユーザーが選択できるラジオボタンを設定する
        $form['prediction'] = [
            '#type' => 'radios',
            '#title' => $this->t('あなたの予想を選んでください'),
            '#options' => ['odd' => $this->t('奇数'), 'even' => $this->t('偶数')],
            '#default_value' => 'odd',
        ];
    
        // フォームの送信ボタンを設定する
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('サイコロを振る'),
        ];
    
        // サイコロの統計情報を取得するクエリを実行し、結果を変数に格納する
        $stats_query = \Drupal::database()->select('helloworld_dice', 'hd');
        $stats_query->addExpression('COUNT(id)', 'total_plays');
        $stats_query->addExpression('SUM(correct_answer)', 'total_correct');
        $stats_query->addExpression('SUM(incorrect_answer)', 'total_incorrect');
        $result = $stats_query->execute()->fetchAssoc();
    
        // 結果から統計情報を取得し、変数に格納する
        $total_plays = $result['total_plays'];
        $total_correct = $result['total_correct'];
        $total_incorrect = $result['total_incorrect'];
    
        // 平均当たり回数を計算し、変数に格納する
        $average_correct = $total_plays > 0 ? ($total_correct / $total_plays) : 0;
    
        // 統計情報を表示するマークアップを設定する
        $form['statistics'] = [
            '#markup' => $this->t('総プレイ回数: @total_plays<br />当たった回数: @total_correct<br />間違った回数: @total_incorrect<br />平均当たり回数: @average_correct', [
                '@total_plays' => $total_plays,
                '@total_correct' => $total_correct,
                '@total_incorrect' => $total_incorrect,
                '@average_correct' => number_format($average_correct, 2),
            ]),
        ];
    
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        // フォームからユーザーの予想を取得する
        $prediction = $form_state->getValue('prediction');
    
        // サイコロを振るためのサービスを呼び出す
        $dice_service = \Drupal::service('helloworld.dice_service');
        $dice_result = $dice_service->rollDice();
    
        // サイコロの結果が偶数かどうかを判定する
        $is_even = ($dice_result % 2 === 0);
    
        // ユーザーの予想が正しいかどうかを判定する
        $correct = ($prediction === 'even' && $is_even) || ($prediction === 'odd' && !$is_even);
    
        // ユーザーに結果に応じたメッセージを表示し、データベースに記録する
        if ($correct) {
            // ユーザーの予想が正しい場合の処理
            \Drupal::messenger()->addMessage($this->t('当たっています。サイコロの結果は @number です。', ['@number' => $dice_result]));
    
            // データベースに正解を記録する
            \Drupal::database()->insert('helloworld_dice')
                ->fields([
                    'correct_answer' => 1,
                    'incorrect_answer' => NULL,
                ])
                ->execute();
        } else {
            // ユーザーの予想が間違っている場合の処理
            \Drupal::messenger()->addMessage($this->t('間違っています。サイコロの結果は @number です。', ['@number' => $dice_result]));
    
            // データベースに不正解を記録する
            \Drupal::database()->insert('helloworld_dice')
                ->fields([
                    'correct_answer' => NULL,
                    'incorrect_answer' => 1,
                ])
                ->execute();
        }
    }
}