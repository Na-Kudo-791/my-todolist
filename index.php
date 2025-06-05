<?php
// --- PHP ロジック部分 ---

$dataFile = 'todos.json';

/**
 * ToDoリストをファイルから読み込む関数
 * @return array
 */
function getTodos() {
    global $dataFile;
    if (!file_exists($dataFile)) {
        return [];
    }
    $json = file_get_contents($dataFile);
    return json_decode($json, true);
}

/**
 * ToDoリストをファイルに保存する関数
 * @param array $todos
 */
function saveTodos($todos) {
    global $dataFile;
    // ピン止め > ID(作成日時)の降順でソート
    usort($todos, function($a, $b) {
        if ($a['pinned'] != $b['pinned']) {
            return $b['pinned'] - $a['pinned'];
        }
        return $b['id'] - $a['id'];
    });
    $json = json_encode($todos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($dataFile, $json);
}

// POSTリクエストの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $todos = getTodos();
    $action = $_POST['action'] ?? '';

    switch ($action) {
        // --- リストの追加 ---
        case 'add':
            if (!empty($_POST['task'])) {
                $newTodo = [
                    'id'        => time(), // ユニークなIDとしてタイムスタンプを使用
                    'task'      => $_POST['task'],
                    'color'     => $_POST['color'] ?? 'white',
                    'completed' => false,
                    'pinned'    => false,
                ];
                $todos[] = $newTodo;
            }
            break;
        
        // --- リストの削除 ---
        case 'delete':
            $id = $_POST['id'];
            $todos = array_filter($todos, fn($todo) => $todo['id'] != $id);
            break;
            
        // --- リストの編集 ---
        case 'update':
            $id = $_POST['id'];
            $updatedTask = $_POST['task'];
            foreach ($todos as &$todo) {
                if ($todo['id'] == $id) {
                    $todo['task'] = $updatedTask;
                    break;
                }
            }
            break;
            
        // --- リストの完了/未完了 ---
        case 'toggle_complete':
            $id = $_POST['id'];
            foreach ($todos as &$todo) {
                if ($todo['id'] == $id) {
                    $todo['completed'] = !$todo['completed'];
                    break;
                }
            }
            break;

        // --- リストのピン止め/解除 ---
        case 'toggle_pin':
            $id = $_POST['id'];
            foreach ($todos as &$todo) {
                if ($todo['id'] == $id) {
                    $todo['pinned'] = !$todo['pinned'];
                    break;
                }
            }
            break;
    }

    saveTodos($todos);
    // POST後のリダイレクト（二重送信防止）
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// GETリクエスト（通常表示）
$todos = getTodos();
$editId = $_GET['edit'] ?? null; // 編集対象のID

// 色の定義
$colors = [
    'white'  => '#FFFFFF',
    'red'    => '#FFD1D1',
    'blue'   => '#D1E3FF',
    'yellow' => '#FFFAD1',
    'green'  => '#D1FFD7',
];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Simple ToDo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>📝 ToDoリスト</h1>

    <div class="add-form">
        <form action="index.php" method="post">
            <input type="hidden" name="action" value="add">
            <input type="text" name="task" placeholder="新しいタスクを入力..." required>
            <div class="colors">
                <?php foreach ($colors as $name => $code): ?>
                <label class="color-option">
                    <input type="radio" name="color" value="<?= $name ?>" <?= $name === 'white' ? 'checked' : '' ?>>
                    <span class="color-swatch" style="background-color: <?= $code ?>;"></span>
                </label>
                <?php endforeach; ?>
            </div>
            <button type="submit">追加</button>
        </form>
    </div>

    <ul class="todo-list">
        <?php foreach ($todos as $todo): ?>
            <?php
                $itemClasses = 'todo-item color-' . $todo['color'];
                if ($todo['completed']) $itemClasses .= ' completed';
                if ($todo['pinned']) $itemClasses .= ' pinned';
            ?>
            <li class="<?= $itemClasses ?>">
                <div class="task-content">
                    <?php if ($editId == $todo['id']): // 編集モードの場合 ?>
                        <form action="index.php" method="post" class="edit-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                            <input type="text" name="task" value="<?= htmlspecialchars($todo['task'], ENT_QUOTES, 'UTF-8') ?>" autofocus>
                            <button type="submit">更新</button>
                        </form>
                    <?php else: // 通常表示の場合 ?>
                        <p class="task-text"><?= htmlspecialchars($todo['task'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="actions">
                    <form action="index.php" method="post">
                        <input type="hidden" name="action" value="toggle_complete">
                        <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                        <button type="submit" title="完了/未完了"><?= $todo['completed'] ? '✅' : '✔️' ?></button>
                    </form>
                    <form action="index.php" method="post">
                        <input type="hidden" name="action" value="toggle_pin">
                        <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                        <button type="submit" title="ピン止め/解除" class="pin-button <?= $todo['pinned'] ? 'pinned' : '' ?>">📌</button>
                    </form>
                    <form action="index.php" method="get">
                        <input type="hidden" name="edit" value="<?= $todo['id'] ?>">
                        <button type="submit" title="編集">✏️</button>
                    </form>
                    <form action="index.php" method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                        <button type="submit" title="削除" onclick="return confirm('本当に削除しますか？');">🗑️</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>