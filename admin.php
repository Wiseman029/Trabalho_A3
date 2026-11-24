<?php require __DIR__.'/api/config.php'; exigir_admin(); ?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Administração | ChapeCode Academy</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css"></head><body>
<header class="page-header"><div class="container"><h1 class="display-6 fw-bold">Administração</h1>
<p class="lead text-secondary mb-0">Dados gravados no banco: orçamentos e mensagens de contato. <a href="index.html">Voltar ao site</a></p></div></header>
<main class="container py-5">
<h2 class="h4">Orçamentos <span id="n-orc" class="badge text-bg-secondary"></span></h2>
<div class="table-responsive mb-5"><table class="table table-striped table-sm align-middle">
<thead><tr><th>#</th><th>Data/hora</th><th>Serviço</th><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Endereço</th><th>Cidade/UF</th><th>Data desejada</th><th>Observações</th></tr></thead>
<tbody id="orc"></tbody></table></div>
<h2 class="h4">Contatos <span id="n-con" class="badge text-bg-secondary"></span></h2>
<div class="table-responsive"><table class="table table-striped table-sm align-middle">
<thead><tr><th>#</th><th>Data/hora</th><th>Nome</th><th>E-mail</th><th>Mensagem</th></tr></thead>
<tbody id="con"></tbody></table></div>
</main>
<script src="js/script.js"></script>
<script>
// O navegador já guarda a senha (Basic Auth) ao abrir /admin.html, então o fetch funciona direto.
function linhas(lista, campos, vazio) {
  return lista.length ? lista.map(r => "<tr>" + campos.map(c => `<td>${esc(typeof c === "function" ? c(r) : r[c])}</td>`).join("") + "</tr>").join("")
                      : `<tr><td colspan="12" class="text-center text-secondary">${vazio}</td></tr>`;
}
(async () => {
  try {
    const o = await api("api/orcamentos.php"), c = await api("api/contatos.php");
    document.getElementById("n-orc").textContent = o.length;
    document.getElementById("n-con").textContent = c.length;
    document.getElementById("orc").innerHTML = linhas(o, ["id","criado_em","servico","nome","email","telefone","endereco", r => r.cidade+"/"+r.uf,"data_desejada","observacoes"], "Nenhum orçamento ainda.");
    document.getElementById("con").innerHTML = linhas(c, ["id","criado_em","nome","email","mensagem"], "Nenhuma mensagem ainda.");
  } catch (e) { document.querySelector("main").insertAdjacentHTML("afterbegin", `<div class="alert alert-danger">${esc(e.message)}</div>`); }
})();
</script></body></html>
