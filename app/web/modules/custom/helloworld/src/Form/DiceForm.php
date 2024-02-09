<?php

namespace Drupal\helloworld\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DiceForm extends FormBase {

    /**
     * このメソッドはフォームの一意のIDを返します。
     * このIDは、Drupal内でこのフォームを特定するために使用されます。
     *
     * @return string
     *   フォームの一意の識別子。
     */
    public function getFormId() {
        return 'helloworld_dice_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        // カスタムテンプレートをレンダリングする配列
        $form['custom_tab'] = [
            '#theme' => 'tab-template',
            '#variables' => 'bugfix',
        ];
        // ユーザーが偶数または奇数を選択するためのラジオボタンを設定します。
        // これにより、ユーザーはサイコロの結果が偶数か奇数かを予想できます。
        $form['prediction'] = [
            '#type' => 'radios',
            '#title' => $this->t('あなたの予想を選んでください'),
            '#options' => ['odd' => $this->t('奇数'), 'even' => $this->t('偶数')],
            '#default_value' => 'odd', // デフォルトで「奇数」が選択されています。
        ];
    
        // フォームの送信ボタンを設定します。
        // このボタンをクリックすると、フォームがサブミットされ、サイコロが振られます。
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('サイコロを振る'),
        ];
    
        // 'helloworld_dice' テーブルから統計情報を取得するクエリを作成します。
        // このクエリは、総プレイ回数、当たった回数、間違った回数を計算します。
        $stats_query = \Drupal::database()->select('helloworld_dice', 'hd');
        $stats_query->addExpression('COUNT(id)', 'total_plays');
        $stats_query->addExpression('SUM(correct_answer)', 'total_correct');
        $stats_query->addExpression('SUM(incorrect_answer)', 'total_incorrect');
        $result = $stats_query->execute()->fetchAssoc();
    
        // 取得した結果を変数に格納します。
        $total_plays = $result['total_plays'];
        $total_correct = $result['total_correct'];
        $total_incorrect = $result['total_incorrect'];
    
        // 平均当たり回数を計算します。
        // プレイ回数が0の場合は平均値も0とします。
        $average_correct = $total_plays > 0 ? ($total_correct / $total_plays) : 0;
    
        // 取得した統計情報をフォームに追加するためのマークアップを作成します。
        // これにより、ユーザーはサイコロゲームの過去の成績を確認できます。
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
        // フォームからユーザーの予想（偶数か奇数か）を取得します。
        $prediction = $form_state->getValue('prediction');
    
        // 'helloworld.dice_service' サービスを使用してサイコロを振ります。
        // このサービスは1から6までのランダムな数値（サイコロの結果）を返します。
        $dice_service = \Drupal::service('helloworld.dice_service');
        $dice_result = $dice_service->rollDice();
    
        // サイコロの結果が偶数か奇数かを判定し、
        // それがユーザーの予想と一致するかどうかをチェックします。
        $is_even = ($dice_result % 2 === 0);
        $correct = ($prediction === 'even' && $is_even) || ($prediction === 'odd' && !$is_even);
    
        // 結果に基づいてユーザーにメッセージを表示し、
        // 統計情報をデータベースに記録します。
        if ($correct) {
            // ユーザーの予想が正しい場合、成功メッセージを表示します。
            \Drupal::messenger()->addMessage($this->t('当たっています。サイコロの結果は @number です。', ['@number' => $dice_result]));
    
            // データベースに正解（correct_answer）として記録します。
            \Drupal::database()->insert('helloworld_dice')
                ->fields([
                    'correct_answer' => 1,
                    'incorrect_answer' => NULL,
                ])
                ->execute();
        } else {
            // ユーザーの予想が間違っている場合、失敗メッセージを表示します。
            \Drupal::messenger()->addMessage($this->t('間違っています。サイコロの結果は @number です。', ['@number' => $dice_result]));
    
            // データベースに不正解（incorrect_answer）として記録します。
            \Drupal::database()->insert('helloworld_dice')
                ->fields([
                    'correct_answer' => NULL,
                    'incorrect_answer' => 1,
                ])
                ->execute();
        }
    }
}