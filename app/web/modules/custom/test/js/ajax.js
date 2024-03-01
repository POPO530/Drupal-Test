// DrupalのJavaScript APIを利用するための標準的なパターン
(function ($, Drupal) {
    // 'testBehavior'という名前のDrupalビヘイビアを定義
    Drupal.behaviors.testBehavior = {
      // 'attach'関数は、ページが読み込まれた時やAjax呼び出し後にDrupalによって自動的に呼び出されます
      attach: function (context, settings) {
        // ボタンクリックイベントの設定
        $('.my-ajax-submit', context).once('testBehavior').each(function () {
          $(this).click(function () {
            // 入力フィールドからテキストを取得
            var inputText = $('.my-text-input').val();
            // AJAXリクエストを送信
            $.ajax({
              url: '/test/ajax', // サーバーのエンドポイント
              method: 'POST', // HTTPメソッド
              data: { 'inputText': inputText }, // 送信するデータ
              success: function (response) {
                // サーバーからのレスポンスをアラートで表示
                alert(response.processedText);
              }
            });
          });
        });
      }
    };
  })(jQuery, Drupal); // 自己実行関数の終わり