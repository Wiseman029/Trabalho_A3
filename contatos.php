<?php
// POST -> grava uma mensagem de contato | GET -> lista contatos (somente admin)
require __DIR__ . '/config.php';
try {
    $pdo = conectar();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        exigir_admin();
        responder($pdo->query('SELECT * FROM contatos ORDER BY id DESC')->fetchAll());
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') erro('Método não permitido.', 405);

    $d     = dados_requisicao();
    $nome  = campo($d, 'nome', 120);
    $email = campo($d, 'email', 160);
    $msg   = campo($d, 'mensagem', 2000);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) erro('E-mail inválido.');

    $st = $pdo->prepare('INSERT INTO contatos (nome,email,mensagem) VALUES (?,?,?)');
    $st->execute([$nome,$email,$msg]);

    responder(['ok' => true, 'id' => (int)$pdo->lastInsertId(),
               'mensagem' => 'Mensagem enviada com sucesso! Responderemos em breve.'], 201);
} catch (Throwable $e) { tratar_excecao($e); }
