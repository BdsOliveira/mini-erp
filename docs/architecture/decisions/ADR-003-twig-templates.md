# ADR-003: Twig como Engine de Templates

- **Status:** `Accepted`
- **Data:** 2026-04-29
- **Autores:** Bruno Oliveira

---

## Contexto

O projeto precisa de uma engine de templates para renderização de views server-side. A escolha impacta a experiência de desenvolvimento das views, a segurança (XSS), a performance e a manutenibilidade a longo prazo.

O framework customizado (`packages/framework/`) usa um trait `HasTemplate` para expor capacidades de rendering aos controllers. A engine escolhida precisa ser plugável nesse trait.

## Decisão

Adotamos **Twig** como engine de templates, integrado via `packages/framework/src/View/Traits/HasTemplate.php`.

A configuração usa `FilesystemLoader` com suporte a múltiplos caminhos de diretório (necessário para o sistema de templates dinâmico — SPEC-001):

```php
$loader = new FilesystemLoader([
    $root . '/resources/views/templates/' . $activeTemplate,
    $root . '/resources/views/templates/default',
]);
$this->twig = new Environment($loader);
```

Funções customizadas (ex: `asset()`) são registradas via `TwigFunction` no bootstrap do trait.

## Alternativas Consideradas

| Alternativa | Motivo da Rejeição |
|-------------|-------------------|
| PHP puro (`include`/`require`) | Sem escape automático de XSS; sem herança de templates |
| Blade (Laravel) | Acoplado ao ecossistema Laravel; não adequado para framework customizado |
| Smarty | Legado; comunidade menor; sintaxe mais verbosa que Twig |
| Latte (Nette) | Menos adotado; menor ecossistema de integrações |

## Consequências

### Positivas
- Escape automático de variáveis previne XSS por padrão.
- Herança de templates (`{% extends %}`, `{% block %}`) permite layouts reutilizáveis.
- `FilesystemLoader` com múltiplos caminhos habilita o sistema de fallback de templates (SPEC-001) nativamente.
- Funções customizadas extensíveis via `TwigFunction`.
- Separação clara entre lógica PHP e apresentação HTML.

### Negativas / Trade-offs
- Adiciona dependência Composer (`twig/twig`).
- Desenvolvedores precisam aprender sintaxe Twig (curva de aprendizado baixa).
- Templates Twig não são executáveis diretamente em PHP puro (sem a engine).

## Referências

- `packages/framework/src/View/Traits/HasTemplate.php`
- `docs/specs/001-multi-template/spec.md` — Sistema de múltiplos templates
- https://twig.symfony.com/doc/
