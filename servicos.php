<?php
// GET api/servicos.php        -> lista todos os serviços do banco
// GET api/servicos.php?id=2   -> um serviço
require __DIR__ . '/config.php';
try {
    $pdo = conectar();
    if (isset($_GET['id'])) {
        $st = $pdo->prepare('SELECT * FROM servicos WHERE id = ? AND ativo = 1');
        $st->execute([(int)$_GET['id']]);
        $s = $st->fetch();
        $s ? responder($s) : erro('Serviço não encontrado.', 404);
    }
    responder($pdo->query('SELECT * FROM servicos WHERE ativo = 1 ORDER BY id')->fetchAll());
} catch (Throwable $e) { tratar_excecao($e); }
