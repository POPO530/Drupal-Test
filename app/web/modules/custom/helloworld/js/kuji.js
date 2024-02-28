(function ($, Drupal) {
  // Drupalの振る舞いを定義する
  Drupal.behaviors.kujiGame = {
    attach: function (context, settings) {
      // 初期設定
      let box = []; // くじボックス
      let drawResult = []; // 引いたくじの結果
      let revealed = []; // 明かされたくじのインデックス
      let remainingTickets = 80; // 残りのくじ数
      let totalAmount = 0; // 使用金額を初期化
      let ticketPrice = 800; // 1枚あたりのくじの価格
      // 各賞品の数
      let prizeCounts = {
        A: 1, B: 1, C: 1, D: 1, E: 1, // 当選賞品
        F: 15, G: 15, H: 15, I: 15, J: 15 // 非当選賞品
      };

      // ゲームをリセットする関数
      const resetGame = () => {
        box = []; // くじボックスを初期化します。この配列は、ゲームで引ける全てのくじを格納します。
        drawResult = []; // 引かれたくじの結果を格納する配列を初期化します。プレイヤーがくじを引くたびに、この配列に結果が追加されます。
        revealed = []; // プレイヤーによって明かされたくじのインデックスを格納する配列を初期化します。これにより、どのくじがすでに見られたかを追跡します。
        remainingTickets = 80; // 残りのくじ数を80にリセットします。この数はゲームのスタート時に全くじの数です。
        totalAmount = 0; // ゲームをリセットするときに使用金額もリセット

        // 賞品をボックスに追加するためのループ処理
        for (let prize in prizeCounts) {
          // 賞品数をリセットします。賞品が'A'から'E'の場合は1枚、それ以外（'F'から'J'）は15枚に設定します。
          prizeCounts[prize] = prize < 'F' ? 1 : 15;
          // 各賞品に対して、設定された数だけくじボックスに追加します。
          for (let i = 0; i < prizeCounts[prize]; i++) {
            box.push(prize); // 賞品をくじボックスに追加します。この操作により、賞品がくじボックス内に均等に分布します。
          }
        }
        
        // くじボックスをシャッフルします。この関数は配列を引数に取り、その配列の要素の順番をランダムに入れ替えます。
        box = shuffleArray(box);
        // 結果表示を更新する関数を呼び出します。この関数は現在のくじの残り数を画面上に表示します。
        updateResultsDisplay();
        // 賞品の残り数を表示する関数を呼び出します。この関数は各賞品の残り数を画面上に表示します。
        updatePrizeDisplay();
        // 使用金額の表示を更新
        updateTotalAmountDisplay();
      };

    // くじを引く関数
    const drawTickets = () => {
      // 残りのくじが0以下の場合、くじがなくなったことをユーザーに警告し、関数の実行を停止します。
      if (remainingTickets <= 0) {
        alert("くじがもうありません！");
        return;
      }
      // ユーザーが選んだくじの数を取得します。'selectedCount'はHTML内の特定の要素の値で、
      // ユーザーがいくつくじを引きたいかを示します。この値は整数に変換されます。
      const selectedCount = parseInt($('#selectedCount', context).val(), 10);
      // 選択されたくじの数が不正（数値でない、1未満、または10を超える）の場合、警告を表示し、関数の実行を停止します。
      if (isNaN(selectedCount) || selectedCount < 1 || selectedCount > 10) {
        alert("1枚から10枚の間で選んでください。");
        return;
      }
      // 引いたくじの結果を格納する配列と、明かされたくじのインデックスを格納する配列を初期化します。
      drawResult = [];
      revealed = [];
      // 実際にくじを引く処理を行います。選択された回数分だけループを回します。
      for (let i = 0; i < selectedCount; i++) {
        // くじボックスから最後の要素を取り出します（くじを引きます）。
        let drawn = box.pop();
        // 引いたくじの結果をdrawResult配列に追加します。
        drawResult.push(drawn);
        // 残りのくじの数を1減らします。
        remainingTickets--;
      }
      // 結果表示を更新する関数を呼び出します。この関数は現在のくじの残り数と引いた結果を画面上に表示します。
      updateResultsDisplay();
      // 選択されたくじの枚数に基づいて使用金額を更新
      totalAmount += selectedCount * ticketPrice;
      // 使用金額の表示を更新
      updateTotalAmountDisplay();
    };

    // くじを明かす関数
    const revealTicket = (index) => {
      // 引いたくじの結果の中で、まだ明かされていない（revealed配列に含まれていない）くじのみを処理します。
      if (!revealed.includes(index)) {
        // 明かされたくじのインデックスをrevealed配列に追加します。
        revealed.push(index);
        // 引いたくじの結果に基づいて、該当する賞品の残り数を更新します。
        updatePrizeCounts(drawResult[index]);
        // くじの結果表示を更新します。これには、新たに明かされたくじの情報が含まれます。
        updateResultsDisplay();
        // 賞品の残り数の表示を更新します。これにより、ユーザーは各賞品の残り数を最新の状態で確認できます。
        updatePrizeDisplay();
      }
    };

    // 配列をシャッフルする関数
    const shuffleArray = (array) => {
      // 配列の最後の要素から開始して、最初の要素に達するまで逆順にループします。
      for (let i = array.length - 1; i > 0; i--) {
        // ランダムに選ばれたインデックスの要素と現在の要素を交換します。
        // このランダムなインデックスは、0から現在のインデックスiまでの範囲で選ばれます。
        const j = Math.floor(Math.random() * (i + 1));
        // 分割代入を使用して、要素の交換を行います。これにより、array[j]の値がarray[i]に、
        // array[i]の値がarray[j]に設定されます。
        [array[i], array[j]] = [array[j], array[i]];
      }
      // シャッフルされた配列を返します。
      return array;
    };

      // updateResultsDisplay関数: 画面に表示されるくじの結果を更新する関数
      const updateResultsDisplay = () => {
        // 残りのチケット数を表示する要素に現在の残りチケット数を設定します。
        $('#remainingTickets', context).text(remainingTickets);

        // IDが'results'の要素を取得します。この要素はくじの結果を表示するために使用されます。
        const resultsDiv = $('#results', context);

        // resultsDivが存在する場合のみ、以下の処理を実行します。
        if (resultsDiv.length > 0) {
          // resultsDivの内容を空にします。これにより、前回の結果がクリアされます。
          resultsDiv.html('');

          // drawResult配列をループして、各くじの結果に基づいて要素を生成します。
          drawResult.forEach((result, index) => {
            // 新しいdiv要素を作成し、'result-element'クラスを追加します。
            // このくじが既に明かされている場合はその結果をテキストとして設定し、
            // そうでない場合は「くじ#インデックス 未開封」というテキストを設定します。
            const resultElement = $('<div></div>')
              .addClass('result-element')
              .text(revealed.includes(index) ? drawResult[index] : `くじ#${index + 1} 未開封`)
              .appendTo(resultsDiv); // この要素をresultsDivに追加します。

            // 作成したdiv要素にdata属性を使用してくじのインデックスを保存します。
            // これにより、後でどのくじがクリックされたかを識別できます。
            resultElement.data('index', index);
          });

          // resultsDivに対してクリックイベントをデリゲートします。
          // これにより、'result-element'クラスを持つすべての要素に対してイベントが適用されます。
          resultsDiv.off('click').on('click', '.result-element', function() {
            // クリックされた要素のdata属性からインデックスを取得します。
            const index = $(this).data('index');

            // このくじがまだ明かされていない場合にのみ処理を実行します。
            if (!revealed.includes(index)) {
              // revealTicket関数を呼び出して、くじを明かします。
              revealTicket(index);

              // クリックされた要素のテキストを更新し、'revealed'クラスを追加して、
              // これ以上のクリックイベントが発火しないようにします。
              $(this)
                .text(drawResult[index])
                .addClass('revealed')
                .off('click'); // クリックイベントを無効化します。
            }
          });
        }
      };

      // 賞品の残り数を更新する関数
      const updatePrizeCounts = (prize) => {
        // 指定された賞品の残り数を1減らします。これにより、くじを引くたびに対応する賞品の在庫が減少します。
        prizeCounts[prize]--;
      };

      // 賞品の残り数を表示する関数
      const updatePrizeDisplay = () => {
        // 賞品の残り数を表示するDIV要素を取得します。
        let prizeDisplayDiv = $('#prizeDisplay', context);
        // もし該当するDIV要素が存在しない場合、body要素に新しいDIV要素を追加します。
        if (!prizeDisplayDiv.length) {
          $('body').append('<div id="prizeDisplay"></div>');
          prizeDisplayDiv = $('#prizeDisplay', context); // 新しく追加した要素を取得します。
        }
        // 賞品表示用DIVの中身を初期化し、タイトルを設定します。
        prizeDisplayDiv.empty().append('<h4>各賞の残り数:</h4>');
        // 賞品の種類ごとに残り数を表示します。
        Object.keys(prizeCounts).sort().forEach(prize => {
          // 賞品の種類とその残り数を含むDIV要素をprizeDisplayに追加します。
          prizeDisplayDiv.append(`<div class="prize-count">${prize}: ${prizeCounts[prize]}枚</div>`);
        });
      };

      // 使用金額の表示を更新する関数
      const updateTotalAmountDisplay = () => {
        $('#totalAmount').html(`使用金額: ${totalAmount}円`);
      };

      // 'context'がドキュメントのルートである場合にのみ、resetGameを実行します。
      if (context === document) {
        resetGame(); // これにより、ページ全体が初めてロードされたときにのみresetGameが実行されます。
      }

      // イベントリスナーを設定
      // 「ゲームをリセットする」ボタンに対して、クリックイベントリスナーを設定します。このイベントは、指定された関数（resetGame）を実行します。
      $('#resetGame', context).once('kujiGame').on('click', resetGame);
      // 「くじを引く」ボタンに対して、クリックイベントリスナーを設定します。このイベントは、指定された関数（drawTickets）を実行します。
      $('#drawTickets', context).once('kujiGame').on('click', drawTickets);
    }
  };
})(jQuery, Drupal);