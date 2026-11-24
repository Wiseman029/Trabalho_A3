# ChapeCode Academy — PHP + MySQL

## Instalação (XAMPP/WAMP)
1. Copie esta pasta para `C:\xampp\htdocs\chapecode`.
2. Inicie **Apache** e **MySQL** no painel do XAMPP.
3. No phpMyAdmin (http://localhost/phpmyadmin): aba **Importar** → selecione `banco/chapecode.sql` → **Executar**.
4. Se o seu MySQL tiver senha, ajuste `api/config.php`.
5. Acesse http://localhost/chapecode/

## Banco de dados
Script em `banco/chapecode.sql` (cria o banco `chapecode`, as tabelas `servicos`, `orcamentos`, `contatos` e carrega os 3 serviços).

## Funcionalidades
| Seção | Inserção | Listagem |
|---|---|---|
| Orçamento | `api/orcamentos.php` (POST, modal das páginas servico1–3) | `admin.php` |
| Contato | `api/contatos.php` (POST, contato.html) | `admin.php` |
| Serviços | — | `api/servicos.php` (catálogo da home e tabela das páginas de serviço) |

`admin.php` pede login (padrão `admin` / `chapecode123`, definido em `api/config.php`).
Consultas com PDO + prepared statements; validação no servidor; saída escapada no navegador.

## Observação
GitHub Pages não executa PHP. Para publicar online use uma hospedagem com PHP e MySQL.
