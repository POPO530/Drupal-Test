<?php

// Drupalの名前空間と必要なクラスを利用するための宣言
namespace Drupal\test\Controller;

// 必要な基底クラスとレスポンスクラスをuse宣言
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

// ControllerBaseクラスを継承したAjaxControllerクラス
class AjaxController extends ControllerBase {
  // 'myAjaxFunction'メソッドは、AJAXリクエストを処理します
  public function myAjaxFunction(Request $request) {
    // リクエストからデータを取得
    $inputText = $request->get('inputText');

    // 取得したデータを加工（この例では大文字に変換）
    $processedText = strtoupper($inputText);

    // 加工したテキストをJSONレスポンスとして返す
    $response_data = ['processedText' => $processedText];

    // JsonResponseオブジェクトを生成して返す
    return new JsonResponse($response_data);
  }
}