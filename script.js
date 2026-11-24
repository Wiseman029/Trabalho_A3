// URL base da API. Vazio = mesmo domínio do site (Flask serve tudo).
// Se o front ficar em outro domínio, defina antes: <script>window.API_BASE="https://seu-backend"</script>
const API = (window.API_BASE || "");  // vazio = mesma pasta do site

const brl = v => Number(v).toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
const esc = s => String(s ?? "").replace(/[&<>"']/g, c => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));

async function api(url, opts) {
  const r = await fetch(API + url, opts);
  let corpo = {};
  try { corpo = await r.json(); } catch (_) {}
  if (!r.ok) throw new Error(corpo.erro || "Erro ao comunicar com o servidor.");
  return corpo;
}

function aviso(form, tipo, texto) {
  let box = form.querySelector(".form-feedback");
  if (!box) {
    box = document.createElement("div");
    box.className = "form-feedback mt-3";
    box.setAttribute("role", "alert");
    (form.querySelector(".modal-body") || form).appendChild(box);
  }
  box.className = `form-feedback mt-3 alert alert-${tipo}`;
  box.textContent = texto;
}

async function enviar(form, url, extra) {
  const botao = form.querySelector("button:not([type=button])");
  const dados = Object.assign(Object.fromEntries(new FormData(form)), extra || {});
  botao.disabled = true;
  try {
    const r = await api(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(dados)
    });
    aviso(form, "success", r.mensagem);
    form.reset();
  } catch (err) {
    aviso(form, "danger", err.message);
  } finally {
    botao.disabled = false;
  }
}

// ORÇAMENTO -> POST /api/orcamentos
function orcamento(e, servicoId) {
  e.preventDefault();
  enviar(e.target, "api/orcamentos.php", { servico_id: servicoId });
}

// CONTATO -> POST /api/contatos
function contato(e) {
  e.preventDefault();
  enviar(e.target, "api/contatos.php");
}

// SERVIÇOS (home) <- GET /api/servicos
async function carregarCatalogo(el) {
  try {
    const lista = await api("api/servicos.php");
    el.innerHTML = lista.map(s => `
      <div class="col-md-6 col-lg-4"><div class="card cardx">
        <img src="${esc(s.imagem)}" class="service-img" alt="${esc(s.nome)}">
        <div class="card-body d-flex flex-column">
          ${s.badge ? `<span class="badge text-bg-light align-self-start mb-2">${esc(s.badge)}</span>` : ""}
          <h3 class="h5">${esc(s.nome)}</h3>
          <p class="text-secondary">${esc(s.descricao)}</p>
          <div class="mt-auto">
            <div class="price mb-3">A partir de ${brl(s.preco)}</div>
            <a href="${esc(s.pagina)}" class="btn btn-primary w-100">Solicitar orçamento</a>
          </div>
        </div></div></div>`).join("") ||
      '<p class="text-center text-secondary">Nenhum serviço disponível.</p>';
  } catch (err) {
    el.innerHTML = `<div class="alert alert-danger">${esc(err.message)}</div>`;
  }
}

// SERVIÇOS (página de detalhe) <- GET /api/servicos/<id>
async function carregarDetalhe(tabela) {
  try {
    const s = await api("api/servicos.php?id=" + tabela.dataset.servicoId);
    tabela.querySelectorAll("[data-campo]").forEach(td => {
      const c = td.dataset.campo;
      td.textContent = c === "preco" ? brl(s.preco) : s[c];
    });
  } catch (_) { /* mantém o conteúdo estático como reserva */ }
}

document.addEventListener("DOMContentLoaded", () => {
  const cat = document.getElementById("lista-servicos");
  if (cat) carregarCatalogo(cat);
  const det = document.querySelector("table[data-servico-id]");
  if (det) carregarDetalhe(det);
});
