// JSONデータを取得してテーブルに表示する関数
async function fetchJson() {
    try {
        // JSONデータを取得するリクエストを送信
        const response = await fetch(`http://localhost/hello/json`);
        // リクエストが成功したかどうかを確認
        if (!response.ok) throw new Error("Network response was not ok");
        // レスポンスからJSONデータを取得
        const data = await response.json();
        // テーブルのtbody要素を取得
        const tbody = document.querySelector('tbody');
        // テーブルの内容をクリア
        tbody.innerHTML = '';
        // 取得したJSONデータをテーブルに追加
        data.forEach(item => {
            // 新しいテーブル行を作成
            const row = document.createElement('tr');
            // 各項目をテーブル行に追加
            row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td>${item.email}</td>
                <td>${item.phone}</td>
                <td>${item.message}</td>
            `;
            // テーブルのtbodyに行を追加
            tbody.appendChild(row);
        });
    } catch (error) {
        // エラーが発生した場合はアラートを表示
        alert(`エラーが発生しました: ${error.message}`);
    }
}

// fetchJson関数を呼び出してJSONデータを取得し、テーブルに表示
fetchJson();

// テキストボックスに入力があるたびにテーブルをフィルタリングする関数
function filterTable() {
    // テキストボックスの値を取得
    const filterValue = document.getElementById('searchInput').value.toUpperCase();
    // テーブルの行を取得
    const rows = document.querySelectorAll('tbody tr');
    // 各行を反復処理してフィルタリング
    rows.forEach(row => {
        // 行のテキストコンテンツを取得
        const textContent = row.textContent.toUpperCase();
        // テキストボックスの値が行のテキストコンテンツ内に存在するかどうかをチェック
        if (textContent.includes(filterValue)) {
            // マッチする場合は表示
            row.style.display = '';
        } else {
            // マッチしない場合は非表示
            row.style.display = 'none';
        }
    });
}