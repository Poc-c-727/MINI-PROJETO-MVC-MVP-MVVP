<?php
// api.php - O PHP agora é apenas um Provedor de Dados (API)

require_once __DIR__ . '/src/Model/Task.php';

// Configuramos o cabeçalho para JSON
header('Content-Type: application/json');

// Conexão com SQLite
$pdo = new PDO('sqlite:' . __DIR__ . '/tasks.sqlite');

$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);

// Instância do Model
$model = new Task($pdo);

// Cria a tabela caso ela não exista
$pdo->exec("
CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    due_date TEXT NOT NULL,
    done INTEGER DEFAULT 0
)
");

$action = $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            // Retorna a lista de tarefas formatada em JSON
            echo json_encode($model->getAll());
            break;

        case 'create':
            // Recebe dados JSON do Front-end
            $data = json_decode(file_get_contents('php://input'), true);
            $model->save(
                $data['title'] ?? '',
                $data['description'] ?? '',
                $data['due_date'] ?? ''
            );
            echo json_encode(['status' => 'success']);
            break;

        case 'complete':
            $model->complete($_GET['id']);
            echo json_encode(['status' => 'success']);
            break;

        case 'delete':
            $model->delete($_GET['id']);
            echo json_encode(['status' => 'success']);
            break;
    }

} catch (Exception $e) {

    http_response_code(400);

    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
?>
