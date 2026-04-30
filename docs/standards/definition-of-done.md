# Definition of Done (DoD) Global

Este documento define o conjunto mínimo de critérios que toda feature deve satisfazer para ser considerada **pronta** (`Implemented`). Cada spec pode adicionar critérios específicos ao seu próprio DoD, mas nunca remover os critérios globais definidos aqui.

---

## Critérios Globais

### Especificação

- [ ] Existe uma spec em `docs/specs/` com status `Ready for Development` ou superior.
- [ ] Todos os casos de uso descritos na spec estão implementados.
- [ ] Todas as regras de negócio da spec estão implementadas e verificáveis.
- [ ] A seção "Fora de Escopo" da spec foi respeitada (nada além do combinado foi implementado).

---

### Código

- [ ] Todos os arquivos PHP têm `declare(strict_types=1)` no topo.
- [ ] Controllers, Services e Repositories seguem o princípio Single Action (`execute()`).
- [ ] Nenhuma query SQL em Controllers ou Services — apenas em Repositories.
- [ ] Código organizado em `app/Modules/{Modulo}/{Camada}/` conforme a spec.
- [ ] Rotas registradas no `routes.php` do módulo correspondente.
- [ ] Namespaces seguem o padrão `App\Modules\{Modulo}\{Camada}`.
- [ ] Nenhuma lógica duplicada que deveria ser extraída para um Service ou Helper.

---

### Testes

- [ ] Testes unitários escritos para todos os Services implementados.
- [ ] Testes unitários escritos para Repositories com lógica não trivial.
- [ ] Todos os testes passam (`./vendor/bin/phpunit` sem falhas).
- [ ] Nenhum teste ignorado (`@skip`, `markTestSkipped`) sem justificativa documentada.

---

### Qualidade

- [ ] PHPStan passa sem erros (`./vendor/bin/phpstan analyse`).
- [ ] Nenhum `var_dump`, `print_r` ou `dd` deixado no código.
- [ ] Nenhuma senha, token ou credencial hardcoded no código.
- [ ] Variáveis de ambiente sensíveis declaradas em `.env.example`.

---

### Documentação

- [ ] Se a feature adiciona ou altera tabelas: `docs/database/schema.md` e `docs/database/er-diagram.md` atualizados.
- [ ] Se a feature introduz novos termos de domínio: `docs/domain/glossary.md` atualizado.
- [ ] Se uma decisão arquitetural relevante foi tomada: ADR criado em `docs/architecture/decisions/`.
- [ ] Status da spec atualizado para `Implemented` em `docs/specs/index.md` e `docs/specs/CHANGELOG.md`.

---

### Revisão

- [ ] Código revisado por ao menos um outro desenvolvedor (quando aplicável).
- [ ] Nenhum comentário de revisão aberto sem resolução.

---

## Processo de Verificação

Antes de marcar uma spec como `Implemented`:

1. Execute `./vendor/bin/phpunit` — todos os testes devem passar.
2. Execute `./vendor/bin/phpstan analyse` — sem erros de nível configurado.
3. Percorra o checklist desta página item por item.
4. Percorra o checklist de Critérios de Aceite da spec correspondente.
5. Atualize o status em `docs/specs/index.md` e `docs/specs/CHANGELOG.md`.

---

## Referências

- `docs/specs/sdd-process.md` — Processo SDD completo
- `docs/standards/coding-conventions.md` — Regras de código
- `docs/standards/testing-strategy.md` — Estratégia de testes
- `phpunit.xml` — Configuração do runner de testes
- `phpstan.neon` — Configuração do analisador estático
