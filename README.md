ファイル構成
>main.php　　　　　　 メイン
>admin-input.php　　 管理画面のUI
>modal.html　　　　　フロント側(html+js)
>style.css　　　　　 フロント側

main.php
  管理画面に「モーダル画像」メニューを追加
  POST値をサニタイズしてwp_optionに追加
  投稿記事にアクションフックでモーダルを追加

admin-input.php
  管理画面の中身のhtml
  フィールド初期値に入力値を表示
  form送信

modal.html
