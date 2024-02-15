// DOMContentLoaded イベントが発生したときに実行される関数
document.addEventListener('DOMContentLoaded', function() {
  // タブ要素のリストを取得
  const tabs = document.querySelectorAll('.tabs-navigation a');
  
  // 各タブにクリックイベントリスナーを追加
  tabs.forEach(tab => {
    tab.addEventListener('click', function(event) {
      // すべてのタブからアクティブなスタイルを削除
      tabs.forEach(t => t.parentNode.classList.remove('active-tab'));
      // クリックされたタブにアクティブなスタイルを追加
      tab.parentNode.classList.add('active-tab');
    });
  });

  // 現在のURLからアクティブなタブを判断
  const currentPath = window.location.pathname;
  tabs.forEach(tab => {
    if(tab.getAttribute('href') === currentPath) {
      tab.parentNode.classList.add('active-tab');
    }
  });
});