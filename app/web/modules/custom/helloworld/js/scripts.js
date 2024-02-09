document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tabs-navigation a');
    tabs.forEach(tab => {
      tab.addEventListener('click', function(event) {
        // 以前のアクティブなタブのスタイルを削除
        tabs.forEach(t => t.parentNode.classList.remove('active-tab'));
  
        // クリックされたタブにアクティブなスタイルを適用
        tab.parentNode.classList.add('active-tab');
  
        // タブに紐づくコンテンツの表示を切り替えるためのコードをここに追加できます
        // 例えば、表示したいコンテンツに対応するIDやクラスに基づいて
        // 必要な表示/非表示のロジックを実装できます。
      });
    });
  
    // URLからアクティブなタブを判断する
    const currentPath = window.location.pathname;
    tabs.forEach(tab => {
      if(tab.getAttribute('href') === currentPath) {
        tab.parentNode.classList.add('active-tab');
      }
    });
  });