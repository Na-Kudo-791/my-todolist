# 📝 PHP Simple ToDo List

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://www.w3.org/Style/CSS/)
[![JSON](https://img.shields.io/badge/JSON-000000?style=for-the-badge&logo=json&logoColor=white)](https://www.json.org/json-en.html)

PHPとJSONファイルだけで動作する、データベース不要のシンプルなToDoリストアプリケーションです。PHPの基本的なスキルを証明するために作成しました。

![ToDoリスト](https://i.imgur.com/top.png)

---

## ✨ このプロジェクトで出来ること (Key Features)

* **基本的なCRUD操作:** タスクの追加(Create)、表示(Read)、編集(Update)、削除(Delete)
  
* **状態管理:** タスクの「完了」「未完了」の切り替え
* **優先度設定:** 特定のタスクをリスト上部に「ピン止め」する機能
* **カスタマイズ:** 5色のパステルカラーからタスクの背景色を選択可能

---

## 🛠️ 技術スタック (Technology Stack)

* **バックエンド:** PHP
* **フロントエンド:** HTML, CSS
* **データ保存:** JSON

---

## 🚀 セットアップと使い方 (Setup & Usage)

1.  **リポジトリのクローン:**
    ```bash
    git clone [https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git](https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git)
    ```

2.  **ディレクトリへ移動:**
    ```bash
    cd YOUR_REPOSITORY
    ```

3.  **データファイルの作成:**
    プロジェクトフォルダ直下に `todos.json` という名前の空のファイルを作成し、中身を以下のように記述します。
    ```json
    []
    ```

4.  **サーバーの起動:**
    XAMPPやMAMPでApacheを起動するか、PHPのビルトインサーバーを起動します。
    ```bash
    php -S localhost:8000
    ```

5.  **ブラウザでアクセス:**
    Webブラウザで `http://localhost:8000` にアクセスしてください。

---

## 💡 こだわった点・学んだこと (What I Learned)

* **データベース不要の設計:** PHPの基本的なファイル操作 (`file_get_contents`, `file_put_contents`) とJSON形式のデータハンドリング (`json_encode`, `json_decode`) だけでCRUD機能を実現しました。
* **ソート処理の工夫:** ピン止め機能を実現するため、`usort` 関数を使って「ピン止めされたもの」を優先的に表示するロジックを実装しました。
* **ユーザー体験の向上:** POSTリクエスト後に自身にリダイレクトさせる「Post/Redirect/Get (PRG)」パターンを用いることで、ブラウザの再読み込みによるフォームの二重送信を防ぎました。

---

## 🔧 今後の改善点 (Future Improvements)

* [ ] **非同期処理の導入:** JavaScript (Fetch API) を用いて、ページリロードなしでタスク操作を完結させる。
* [ ] **データベースへの移行:** データ管理の堅牢性を高めるため、SQLiteやMySQLへの移行。
* [ ] **オブジェクト指向化:** コードの保守性・再利用性を高めるため、ToDo機能をクラスベースで再設計する。
