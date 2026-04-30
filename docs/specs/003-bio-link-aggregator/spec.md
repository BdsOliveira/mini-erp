# [SPEC-003] Página Bio — Agregador de Links

- **Status:** `Draft`
- **Módulo:** `app/Modules/Bio/`
- **Criado em:** 2026-04-29
- **Autor:** Bruno Oliveira

---

## 1. Contexto e Problema

O sistema não possui uma página pública de apresentação pessoal/institucional com links centralizados. Administradores precisam direcionar visitantes a múltiplos destinos externos (redes sociais, portfólio, loja, WhatsApp, etc.) através de uma única URL compartilhável — o modelo popularizado por ferramentas como Linktree.

A solução deve ser nativa ao sistema: o admin gerencia os links pelo painel administrativo já existente, e os visitantes acessam uma página pública em `/bio` que exibe todos os links ativos em formato de lista vertical clicável.

---

## 2. Objetivo

Permitir que o administrador cadastre, ordene, ative/desative e remova links de uma página pública em `/bio`, onde qualquer visitante pode clicar e ser redirecionado ao destino externo.

---

## 3. Casos de Uso

### UC-01: Visitante acessa a página `/bio`

- **Ator:** Visitante (não autenticado)
- **Pré-condições:** Pelo menos um link ativo existe no sistema.
- **Fluxo Principal:**
  1. Visitante acessa `/bio`.
  2. Sistema busca todos os links com `status = 'active'`, ordenados por `ordem ASC`.
  3. Sistema renderiza a página com título, subtítulo (opcional) e a lista de links.
  4. Visitante clica em um link.
  5. Sistema redireciona para a URL de destino via `<a href="..." target="_blank" rel="noopener noreferrer">`.
- **Fluxo Alternativo — Nenhum link ativo:**
  - A1: Página renderiza normalmente com uma mensagem "Nenhum link disponível no momento."
- **Pós-condições:** Visitante visualizou os links ou foi redirecionado ao destino externo.

---

### UC-02: Admin lista os links existentes

- **Ator:** Administrador autenticado
- **Pré-condições:** Admin está autenticado (sessão com `role = 'admin'`).
- **Fluxo Principal:**
  1. Admin acessa `/admin/bio`.
  2. Sistema busca todos os links (ativos e inativos), ordenados por `ordem ASC`.
  3. Sistema renderiza a tabela com: título, URL, status (ativo/inativo), ordem e ações (editar, excluir).
- **Pós-condições:** Admin visualiza o estado atual de todos os links.

---

### UC-03: Admin cria um novo link

- **Ator:** Administrador autenticado
- **Pré-condições:** Admin está autenticado.
- **Fluxo Principal:**
  1. Admin acessa `/admin/bio/novo`.
  2. Preenche o formulário: título (obrigatório), URL (obrigatório), subtítulo (opcional), status (ativo/inativo), ordem.
  3. Submete o formulário via `POST /admin/bio`.
  4. Sistema valida os campos.
  5. Sistema persiste o novo link no banco.
  6. Sistema redireciona para `/admin/bio` com mensagem flash de sucesso.
- **Fluxo Alternativo — Validação falha:**
  - A1: Sistema renderiza o formulário novamente com os erros por campo.
- **Pós-condições:** Novo link persistido. Aparece na lista do admin e, se ativo, na página `/bio`.

---

### UC-04: Admin edita um link existente

- **Ator:** Administrador autenticado
- **Pré-condições:** Link com o `id` informado existe.
- **Fluxo Principal:**
  1. Admin acessa `/admin/bio/{id}/editar`.
  2. Sistema carrega os dados atuais do link no formulário.
  3. Admin altera os campos desejados e submete via `POST /admin/bio/{id}`.
  4. Sistema valida e persiste as alterações.
  5. Sistema redireciona para `/admin/bio` com mensagem flash de sucesso.
- **Fluxo Alternativo — Link não encontrado:**
  - A1: Sistema retorna 404.
- **Fluxo Alternativo — Validação falha:**
  - A2: Formulário re-renderizado com erros.
- **Pós-condições:** Dados do link atualizados.

---

### UC-05: Admin remove um link

- **Ator:** Administrador autenticado
- **Pré-condições:** Link com o `id` informado existe.
- **Fluxo Principal:**
  1. Admin clica em "Excluir" na listagem `/admin/bio`.
  2. Sistema submete `POST /admin/bio/{id}/excluir`.
  3. Sistema remove o registro do banco.
  4. Sistema redireciona para `/admin/bio` com mensagem flash de sucesso.
- **Fluxo Alternativo — Link não encontrado:**
  - A1: Sistema redireciona para `/admin/bio` com mensagem flash de erro.
- **Pós-condições:** Link removido do banco e da página `/bio`.

---

### UC-06: Admin reordena os links

- **Ator:** Administrador autenticado
- **Pré-condições:** Existem dois ou mais links cadastrados.
- **Fluxo Principal:**
  1. Admin altera o valor do campo `ordem` de um ou mais links via formulário de edição (UC-04).
  2. Sistema persiste o novo valor de `ordem`.
  3. A página `/bio` passa a exibir os links na nova ordem.
- **Notas:** Reordenação drag-and-drop está **fora de escopo** nesta spec. Ordem é gerenciada via campo numérico inteiro.
- **Pós-condições:** Links exibidos na ordem atualizada.

---

### UC-07: Admin ativa ou desativa um link

- **Ator:** Administrador autenticado
- **Pré-condições:** Link existe.
- **Fluxo Principal:**
  1. Admin edita um link e altera o campo `status` para `'inactive'` ou `'active'` (UC-04).
  2. Sistema persiste a alteração.
  3. Links com `status = 'inactive'` não aparecem na página pública `/bio`.
- **Pós-condições:** Visibilidade do link na página pública alterada imediatamente.

---

## 4. Regras de Negócio

| ID    | Regra |
|-------|-------|
| RN-01 | O campo `titulo` é obrigatório e tem máximo de 100 caracteres. |
| RN-02 | O campo `url` é obrigatório, deve ser uma URL válida (aceita http e https), e tem máximo de 2048 caracteres. |
| RN-03 | O campo `subtitulo` é opcional e tem máximo de 150 caracteres. |
| RN-04 | O campo `ordem` é um inteiro positivo (mínimo 1). Se não informado, assume o valor `MAX(ordem) + 1` dos links existentes, ou `1` se não houver nenhum. |
| RN-05 | O campo `status` aceita apenas os valores `'active'` ou `'inactive'`. Default: `'active'`. |
| RN-06 | A página `/bio` exibe apenas links com `status = 'active'`, ordenados por `ordem ASC`. |
| RN-07 | A página `/bio` é pública — não requer autenticação. |
| RN-08 | O painel administrativo em `/admin/bio` requer autenticação com `role = 'admin'` (via `AuthGuard::checkAdmin()`). |
| RN-09 | Links abrem sempre em nova aba (`target="_blank"`) com `rel="noopener noreferrer"`. |
| RN-10 | A URL submetida deve ser sanitizada com `filter_var($url, FILTER_SANITIZE_URL)` antes da validação com `FILTER_VALIDATE_URL`. |
| RN-11 | Não há limite de quantidade de links por enquanto. |

---

## 5. Contratos de Interface

### 5.1 Rotas HTTP

| Método | Path | Controller |
|--------|------|------------|
| `GET`  | `/bio` | `Bio\ShowBioController` |
| `GET`  | `/admin/bio` | `Bio\Admin\ListBioLinksController` |
| `GET`  | `/admin/bio/novo` | `Bio\Admin\ShowCreateBioLinkController` |
| `POST` | `/admin/bio` | `Bio\Admin\CreateBioLinkController` |
| `GET`  | `/admin/bio/{id}/editar` | `Bio\Admin\ShowEditBioLinkController` |
| `POST` | `/admin/bio/{id}` | `Bio\Admin\UpdateBioLinkController` |
| `POST` | `/admin/bio/{id}/excluir` | `Bio\Admin\DeleteBioLinkController` |

### 5.2 DTOs

**`CreateBioLinkDTO`:**

```php
readonly class CreateBioLinkDTO {
    public string $titulo;
    public string $url;
    public ?string $subtitulo;
    public int $ordem;
    public string $status; // 'active' | 'inactive'
}
```

**`UpdateBioLinkDTO`:**

```php
readonly class UpdateBioLinkDTO {
    public int $id;
    public string $titulo;
    public string $url;
    public ?string $subtitulo;
    public int $ordem;
    public string $status;
}
```

### 5.3 Respostas

| Ação | Resposta |
|------|----------|
| `GET /bio` | View renderizada `bio/index.php` com lista de links ativos |
| `GET /admin/bio` | View `bio/admin/index.php` com todos os links |
| `GET /admin/bio/novo` | View `bio/admin/form.php` (formulário vazio) |
| `POST /admin/bio` (sucesso) | `redirect('/admin/bio')` + flash de sucesso |
| `POST /admin/bio` (erro) | Re-render do formulário com erros |
| `GET /admin/bio/{id}/editar` | View `bio/admin/form.php` (formulário preenchido) |
| `POST /admin/bio/{id}` (sucesso) | `redirect('/admin/bio')` + flash de sucesso |
| `POST /admin/bio/{id}` (erro) | Re-render do formulário com erros |
| `POST /admin/bio/{id}/excluir` (sucesso) | `redirect('/admin/bio')` + flash de sucesso |
| `POST /admin/bio/{id}/excluir` (não encontrado) | `redirect('/admin/bio')` + flash de erro |

---

## 6. Esquema de Banco de Dados

### Nova tabela `bio_links`

```sql
CREATE TABLE IF NOT EXISTS bio_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    subtitulo VARCHAR(150) NULL DEFAULT NULL,
    url VARCHAR(2048) NOT NULL,
    ordem INT UNSIGNED NOT NULL DEFAULT 1,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status_ordem (status, ordem)
);
```

---

## 7. Estrutura de Arquivos

```
app/Modules/Bio/
├── Controllers/
│   ├── ShowBioController.php               # GET /bio (público)
│   └── Admin/
│       ├── ListBioLinksController.php      # GET /admin/bio
│       ├── ShowCreateBioLinkController.php # GET /admin/bio/novo
│       ├── CreateBioLinkController.php     # POST /admin/bio
│       ├── ShowEditBioLinkController.php   # GET /admin/bio/{id}/editar
│       ├── UpdateBioLinkController.php     # POST /admin/bio/{id}
│       └── DeleteBioLinkController.php     # POST /admin/bio/{id}/excluir
├── DTOs/
│   ├── CreateBioLinkDTO.php
│   └── UpdateBioLinkDTO.php
├── Models/
│   └── BioLink.php
├── Repositories/
│   ├── GetActiveBioLinksRepository.php    # Para /bio público
│   ├── GetAllBioLinksRepository.php       # Para /admin/bio
│   ├── FindBioLinkByIdRepository.php      # Para edição/exclusão
│   ├── GetMaxBioLinkOrdemRepository.php   # Para calcular próxima ordem
│   ├── CreateBioLinkRepository.php
│   ├── UpdateBioLinkRepository.php
│   └── DeleteBioLinkRepository.php
├── Services/
│   ├── CreateBioLinkService.php           # Valida + calcula ordem + persiste
│   └── UpdateBioLinkService.php           # Valida + persiste
└── routes.php

resources/views/bio/
├── index.php                              # Página pública /bio
└── admin/
    ├── index.php                          # Listagem admin
    └── form.php                           # Formulário create/edit (reutilizável)
```

---

## 8. Critérios de Aceite (DoD)

- [ ] Página `/bio` é acessível sem autenticação e exibe links ativos ordenados por `ordem ASC`.
- [ ] Página `/bio` exibe mensagem amigável quando não há links ativos.
- [ ] Links na página pública abrem em nova aba com `rel="noopener noreferrer"`.
- [ ] `/admin/bio` redireciona para `/admin/login` sem sessão admin válida.
- [ ] Admin consegue criar, editar, reordenar e excluir links.
- [ ] Campos obrigatórios (`titulo`, `url`) são validados; erros exibidos no formulário.
- [ ] URL inválida (sem protocolo, malformada) é rejeitada com mensagem de erro.
- [ ] `status = 'inactive'` oculta o link da página pública sem removê-lo do banco.
- [ ] Campo `ordem` assume `MAX(ordem) + 1` automaticamente quando não informado na criação.
- [ ] Nenhuma query SQL em Controllers ou Services.
- [ ] Todos os controllers seguem o padrão Single Action (`execute()`).
- [ ] `strict_types=1` declarado em todos os arquivos PHP.
- [ ] Rotas registradas em `app/Modules/Bio/routes.php`.
- [ ] Testes unitários cobrem `CreateBioLinkService` e `UpdateBioLinkService`.
- [ ] Tabela `bio_links` adicionada ao `database/schema.sql`.

---

## 9. Fora de Escopo

- Reordenação via drag-and-drop na interface admin — ordem gerenciada por campo numérico.
- Analytics de cliques (contagem de acessos por link).
- Upload de ícones/imagens por link.
- Múltiplas páginas bio (multi-tenant ou por perfil).
- Slug customizável — a rota é sempre `/bio`.
- Personalização de tema/cor por link ou da página.
- Expiração automática de links por data.

---

## 10. Notas e Decisões Técnicas

- **Módulo isolado `Bio/`:** Segue o padrão modular existente (`Store/`, `Catalog/`, etc.). Não acopla ao módulo de autenticação além de chamar `AuthGuard::checkAdmin()` nos controllers admin — dependência válida conforme a arquitetura.
- **Formulário único `form.php`:** Create e Edit compartilham a mesma view. A distinção é feita via presença do `$link` na variável passada ao template e na `action` do `<form>`.
- **Sem rota `DELETE`:** O router atual suporta apenas `GET` e `POST`. A exclusão é feita via `POST /admin/bio/{id}/excluir`, padrão já adotado no projeto.
- **`FILTER_VALIDATE_URL`:** Validação nativa do PHP é suficiente para garantir URLs bem formadas com protocolo `http`/`https`. Não será feita verificação de disponibilidade da URL (HTTP request externo).
- **View pública sem layout admin:** A página `/bio` usa um layout mínimo (ou nenhum layout compartilhado com o admin) para manter a identidade visual independente do painel administrativo. O layout exato será definido na implementação.
- **`INDEX idx_status_ordem`:** A query mais frequente é `WHERE status = 'active' ORDER BY ordem ASC`, justificando o índice composto.
