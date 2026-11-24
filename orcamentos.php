<?php
// POST -> grava um orçamento | GET -> lista orçamentos (somente admin)
require __DIR__ . '/config.php';
try {
    $pdo = conectar();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        exigir_admin();
        responder($pdo->query(
            'SELECT o.*, s.nome AS servico FROM orcamentos o
             JOIN servicos s ON s.id = o.servico_id ORDER BY o.id DESC')->fetchAll());
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') erro('Método não permitido.', 405);

    $d        = dados_requisicao();
    $nome     = campo($d, 'nome', 120);
    $email    = campo($d, 'email', 160);
    $telefone = campo($d, 'telefone', 30);
    $endereco = campo($d, 'endereco', 200);
    $cidade   = campo($d, 'cidade', 80);
    $uf       = strtoupper(campo($d, 'uf', 2));
    $data     = campo($d, 'data', 10);
    $obs      = campo($d, 'observacoes', 1000, false);
    $sid      = (int)($d['servico_id'] ?? 0);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) erro('E-mail inválido.');
    if (!preg_match('/^[A-Z]{2}$/', $uf)) erro('UF deve ter 2 letras (ex.: SC).');
    $dt = DateTime::createFromFormat('Y-m-d', $data);
    if (!$dt || $dt->format('Y-m-d') !== $data) erro('Data inválida.');
    if ($data < date('Y-m-d')) erro('A data desejada não pode estar no passado.');

    $st = $pdo->prepare('SELECT 1 FROM servicos WHERE id = ? AND ativo = 1');
    $st->execute([$sid]);
    if (!$st->fetch()) erro('Serviço inexistente.', 404);

    $st = $pdo->prepare(
        'INSERT INTO orcamentos
         (servico_id,nome,email,telefone,endereco,cidade,uf,data_desejada,observacoes)
         VALUES (?,?,?,?,?,?,?,?,?)');
    $st->execute([$sid,$nome,$email,$telefone,$endereco,$cidade,$uf,$data,$obs ?: null]);

    responder(['ok' => true, 'id' => (int)$pdo->lastInsertId(),
               'mensagem' => 'Orçamento enviado com sucesso! Entraremos em contato.'], 201);
} catch (Throwable $e) { tratar_excecao($e); }
