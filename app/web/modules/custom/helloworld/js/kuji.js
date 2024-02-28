(function ($, Drupal) {
  // Drupalの振る舞いを定義する
  Drupal.behaviors.kujiGame = {
    attach: function (context, settings) {
      // 初期設定
      let box = []; // くじボックス
      let drawResult = []; // 引いたくじの結果
      let revealed = []; // 明かされたくじのインデックス
      let remainingTickets = 80; // 残りのくじ数
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

      // 結果表示を更新する関数
      const updateResultsDisplay = () => {
        // 残りのくじ数を画面上に表示します。'remainingTickets'変数の値を、指定されたDOM要素のテキストとして設定します。
        $('#remainingTickets', context).text(remainingTickets);
        // 結果を表示するDIV要素を取得します。この要素内に、各くじの結果または未開封の状態を表示します。
        const resultsDiv = $('#results', context);
        // resultsDivがページ上に存在する場合のみ、以下の処理を実行します。
        if (resultsDiv.length > 0) {
          // まず、resultsDivの内容を空にします。これにより、前回の結果表示がクリアされます。
          resultsDiv.html('');
          // 引かれたくじの結果に基づいて、結果の表示を更新します。
          drawResult.forEach((result, index) => {
            // 各くじの結果を表示するための新しいDIV要素を作成します。
            const resultElement = $('<div></div>');
            // くじが明かされている場合はその結果を、明かされていない場合は「未開封」と表示します。
            const text = revealed.includes(index) ? drawResult[index] : `くじ#${index + 1} 未開封`;
            // 作成したDIV要素にテキストを設定します。
            resultElement.text(text);
            // DIV要素にスタイルを適用します。クリック可能な見た目にするためにカーソルをポインタに設定し、マージン、パディング、ボーダー、表示スタイルを設定します。
            resultElement.css({'cursor': 'pointer', 'margin': '5px', 'padding': '5px', 'border': '1px solid #ccc', 'display': 'inline-block'});
            // DIV要素にクリックイベントリスナーを追加します。くじが未開封の場合に限り、クリック時にそのくじを明かし、結果を表示し、以降のクリックイベントを無効化します。
            resultElement.on('click', function() {
              if (!revealed.includes(index)) {
                revealTicket(index);
                $(this).text(drawResult[index]);
                $(this).off('click');
              }
            });
            // 更新された結果のDIV要素をresultsDivに追加します。
            resultsDiv.append(resultElement);
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
        const prizeDisplayDiv = $('#prizeDisplay', context);
        // もし該当するDIV要素が存在しない場合、body要素に新しいDIV要素を追加します。
        if (!prizeDisplayDiv.length) {
          $('body').append('<div id="prizeDisplay" style="margin-top: 20px;"></div>');
        }
        // 賞品表示用DIVの中身を初期化し、タイトルを設定します。
        $('#prizeDisplay').html('<h4>各賞の残り数:</h4>');
        // 賞品の種類ごとに残り数を表示します。
        Object.keys(prizeCounts).sort().forEach(prize => {
          // 賞品の種類とその残り数を含むDIV要素をprizeDisplayに追加します。
          $('#prizeDisplay').append(`<div>${prize}: ${prizeCounts[prize]}枚</div>`);
        });
      };

      // イベントリスナーを設定
      // 「ゲームをリセットする」ボタンに対して、クリックイベントリスナーを設定します。このイベントは、指定された関数（resetGame）を実行します。
      $('#resetGame', context).once('kujiGame').on('click', resetGame);
      // 「くじを引く」ボタンに対して、クリックイベントリスナーを設定します。このイベントは、指定された関数（drawTickets）を実行します。
      $('#drawTickets', context).once('kujiGame').on('click', drawTickets);

      // ゲームの初期化
      // ドキュメントが完全に読み込まれた後に、ゲームをリセットする関数（resetGame）を実行します。
      // これにより、ページの読み込みが完了した時点でゲームが初期状態にリセットされ、準備が整います。
      $(document).ready(function() {
        resetGame();
      });
    }
  };
})(jQuery, Drupal);