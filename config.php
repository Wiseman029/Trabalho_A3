<?php
// Ajuste conforme o seu MySQL (padrão do XAMPP: root, sem senha).
const DB_HOST = 'localhost';
const DB_NAME = 'chapecode';
const DB_USER = 'root';
const DB_PASS = '';

// Acesso à listagem administrativa (admin.php). Troque a senha.
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'chapecode123';

function conectar(): PDO {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES => false]
    );
    return $pdo;
}

function responder($dados, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

function erro(string $msg, int $status = 400): void {
    responder(['ok' => false, 'erro' => $msg], $status);
}

function dados_requisicao(): array {
    $json = json_decode(file_get_contents('php://input'), true);
    return is_array($json) ? $json : $_POST;
}

function campo(array $d, string $k, int $max, bool $obrig = true): string {
    $v = trim((string)($d[$k] ?? ''));
    if ($obrig && $v === '') erro("O campo '$k' é obrigatório.");
    if (mb_strlen($v) > $max) erro("O campo '$k' excede $max caracteres.");
    return $v;
}

function exigir_admin(): void {
    $u = $_SERVER['PHP_AUTH_USER'] ?? null;
    $p = $_SERVER['PHP_AUTH_PW'] ?? null;
    if ($u !== ADMIN_USER || $p !== ADMIN_PASS) {
        header('WWW-Authenticate: Basic realm="ChapeCode Admin"');
        http_response_code(401);
        exit('Acesso restrito.');
    }
}

function tratar_excecao(Throwable $e): void {
    error_log($e->getMessage());
    erro('Erro no servidor. Verifique se o banco "chapecode" foi importado.', 500);
}
