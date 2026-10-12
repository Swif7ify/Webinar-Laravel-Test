# 📐 Project Rules & Coding Standards

This file is the authoritative rule set for all code generated in this project.
All rules below are **strict** and must be followed in every implementation, regardless of task size.

---

## 🗂️ 1. File Architecture

All files **must** be placed in their designated directories as defined in `FILE_ARCHITECTURE.md`. Never invent new top-level folders or deviate from the established structure.

### Backend (`app/`)

| Layer | Directory | Rule |
|---|---|---|
| Controllers | `app/Http/Controllers/` | Thin only. No business logic. Delegate immediately to a Service. Return JSON responses. |
| Services | `app/Http/Services/` | All business logic lives here. One service per domain. |
| Repositories | `app/Http/Repositories/` | All database queries (Eloquent / Query Builder) live here. Services must not contain raw queries. |
| Form Requests | `app/Http/Requests/` | Every controller action that accepts user input **must** use a dedicated Form Request class for validation. |
| Middleware | `app/Http/Middleware/` | Cross-cutting concerns only (auth, role, logging). |
| Models | `app/Models/` | Eloquent models only. No query logic beyond scopes/relationships. |

**Request flow is immutable:**
```
Routes → Middleware → Controller → Service → Repository → Model
```
Skipping layers or short-circuiting this flow is not allowed.

### Frontend (`resources/`)

| Type | Directory | Rule |
|---|---|---|
| Blade layouts | `resources/views/layouts/` | Shell templates only (`app.blade.php`, `guest.blade.php`). |
| Blade pages | `resources/views/pages/` | Full page views. Must `@extends` a layout. |
| Blade UI components | `resources/views/components/ui/` | Reusable atomic UI elements (buttons, cards, badges, modals, alerts). |
| Blade form components | `resources/views/components/forms/` | Reusable form controls (inputs, selects, textareas, checkboxes). |
| Blade nav components | `resources/views/components/navigation/` | Navbar, sidebar, footer. |
| JS component initialisers | `resources/js/components/` | Barrel-exported via `index.js`. |
| JS hooks / composables | `resources/js/hooks/` | Barrel-exported via `index.js`. One hook per file. |
| JS constants | `resources/js/constants/` | Barrel-exported via `index.js`. One domain per file. |
| JS utilities | `resources/js/utils/` | Pure helper functions. No side effects. |
| Global CSS | `resources/css/app.css` | Design tokens and global styles only. |
| Component CSS | `resources/css/components/` | One CSS file per component, imported via `_index.css`. |

---

## 🧹 2. Code Cleanliness

- **No dead code.** Do not leave unused variables, imports, functions, or commented-out blocks.
- **No `console.log` or `dd()` / `dump()` left in production code.** Remove all debug statements before finishing.
- **Single Responsibility.** Every class, function, and component does exactly one thing.
- **Consistent naming conventions:**
  - PHP: `PascalCase` for classes, `camelCase` for methods and variables, `snake_case` for database columns and array keys.
  - JavaScript: `camelCase` for variables and functions, `PascalCase` for class names, `SCREAMING_SNAKE_CASE` for constants.
  - Blade files: `kebab-case` for file names (e.g., `user-card.blade.php`).
- **Short methods.** If a function exceeds ~30 lines, extract sub-logic into named helpers.
- **No magic numbers or magic strings.** All literal values with semantic meaning must be extracted to constants.

---

## 💬 3. Comment Policy

Comments in code are **strictly prohibited** with **one exception**:

✅ **Allowed** — Frontend Blade section markers that identify major layout blocks:
```blade
{{-- ========== HEADER ========== --}}
{{-- ========== TABLE ========== --}}
{{-- ========== PAGINATION ========== --}}
{{-- ========== MODALS ========== --}}
```

❌ **Not allowed:**
- Explanatory comments describing *what* code does (`// loops through users`)
- Commented-out code blocks
- TODO / FIXME / HACK comments left in committed code
- PHPDoc blocks on private methods or trivial getters
- Inline `/* */` style notes inside logic

Code must be self-documenting through clear naming. If a comment is needed to explain logic, rewrite the logic instead.

---

## 🧩 4. UI Components

Every reusable UI element **must** be a Blade component located in `resources/views/components/`.
Do not inline recurring UI patterns directly into page views.

### Required components (must exist before use):

| Component | Path | Slots / Props |
|---|---|---|
| Button | `components/ui/button.blade.php` | `variant` (primary/secondary/danger/ghost), `type`, `id` |
| Card | `components/ui/card.blade.php` | `title` (optional), default slot for body |
| Badge | `components/ui/badge.blade.php` | `variant` (success/warning/danger/info/neutral), `label` |
| Modal | `components/ui/modal.blade.php` | `id`, `title`, default slot, `footer` slot |
| Alert | `components/ui/alert.blade.php` | `type` (success/error/warning/info), `message` |
| Empty State | `components/ui/empty-state.blade.php` | `message`, optional `icon` |
| Spinner / Loader | `components/ui/spinner.blade.php` | `size` (sm/md/lg) |
| Input | `components/forms/input.blade.php` | `name`, `label`, `type`, `required`, `error` |
| Select | `components/forms/select.blade.php` | `name`, `label`, `options`, `required`, `error` |
| Textarea | `components/forms/textarea.blade.php` | `name`, `label`, `rows`, `required`, `error` |

- Components must accept props via `@props([])` and must **not** contain business logic.
- Styling must come exclusively from the global CSS design system or the component's own CSS file in `resources/css/components/`.
- Variant-based styling must use CSS classes, never inline `style=""` attributes.

---

## 🪝 5. Hooks & Composables (JavaScript)

- Every reusable piece of stateful or side-effectful JS logic is a **hook** living in `resources/js/hooks/`.
- File naming: `use<Domain>.js` (e.g., `useModal.js`, `useToast.js`, `useDebounce.js`).
- Hooks must be **barrel-exported** from `resources/js/hooks/index.js`.
- A hook must not be inlined inside a component file or a page `<script>` block.
- Hooks must be **pure and reusable** — no page-specific assumptions inside a hook.

```js
// ✅ Correct — dedicated file
// resources/js/hooks/useModal.js
export function useModal(modalId) { ... }

// resources/js/hooks/index.js
export { useModal } from './useModal';
```

---

## 📦 6. Constants

- Every hardcoded value with semantic meaning belongs in `resources/js/constants/`.
- File naming by domain: `roles.js`, `routes.js`, `statuses.js`, `pagination.js`, etc.
- All constants use `SCREAMING_SNAKE_CASE`.
- All constants are **barrel-exported** from `resources/js/constants/index.js`.
- On the PHP side, use `config()` values or dedicated Enum classes in `app/Enums/` — never hardcode strings in controllers or services.

```js
// ✅ Correct
// resources/js/constants/roles.js
export const ROLE_ADMIN     = 'admin';
export const ROLE_INSPECTOR = 'inspector';

// resources/js/constants/index.js
export * from './roles';
export * from './statuses';
```

---

## 🔒 7. Security & Input Sanitation

### PHP / Laravel (Backend)

- **Every controller action accepting user input must use a Form Request** (`app/Http/Requests/`). No direct `$request->input()` without prior validation.
- Use `$request->validated()` — never `$request->all()` — to retrieve payload in services.
- All database writes go through Eloquent or Query Builder with **bound parameters**. Raw SQL is never used with user-supplied values.
- File uploads must be validated for MIME type, size, and stored via `Storage::disk()` — never moved to `public/` directly.
- Passwords are always hashed with `bcrypt()` or `Hash::make()`. Never stored or logged in plain text.
- JWT tokens are validated by `AuthenticateJWT` middleware. No endpoint that requires authentication can be accessed without this middleware.
- Role checks are enforced by `RoleBasedAuthentication` middleware. Never perform role checks inline in a controller.
- CSRF protection is active on all web routes. The `csrf-token` meta tag is present in every layout.

### JavaScript / Blade (Frontend)

- Never render user-supplied data as raw HTML (`{!! !!}`). Always use escaped output (`{{ }}`) unless the value has been explicitly sanitised server-side.
- Form submissions that mutate data must include `@csrf` in every `<form>`.
- Sensitive values (tokens, keys) must never appear in Blade templates or JS source.
- User input that feeds into DOM manipulation (e.g., `innerHTML`) must be sanitised client-side with a proper sanitiser before insertion.

---

## 🎨 8. Styling & Design System

All visual styling must derive from the **global design system** defined in `resources/css/app.css` and its imported component files in `resources/css/components/`.

### Rules:

- **Never use inline `style=""` attributes.** All styles must be expressed as CSS classes.
- **Never hardcode colour hex/rgb values in component or page CSS.** Use CSS custom properties (`var(--color-*)`) defined in `app.css`.
- **Never hardcode font sizes or spacing values.** Use the spacing and typography scale tokens from `app.css`.
- The project font is **Poppins** (`poppins:300,400,700`). No other font may be introduced without updating the global font config.
- Component-specific CSS must live in `resources/css/components/<component-name>.css` and be imported via `resources/css/components/_index.css`.
- Responsive layouts must use the project's established breakpoint tokens. Do not introduce one-off media queries with arbitrary pixel values.
- **No gradients.** All backgrounds, buttons, accents, and decorative elements must use **solid colours** exclusively. Never use `linear-gradient()`, `radial-gradient()`, `conic-gradient()`, or any gradient Tailwind utilities. Every colour must reference a `var(--color-*)` token from `resources/css/app.css`.

### Typography hierarchy (must be respected):
| Role | Usage |
|---|---|
| `h1` | Page title — one per page |
| `h2` | Section heading |
| `h3` | Card / panel heading |
| `h4`–`h6` | Sub-section labels |
| `p` | Body text |
| `small` / `.text-sm` | Captions, hints, meta |

---

## 🔁 9. Routing

- All API routes are defined in `routes/api.php`. No route definitions in controllers or service providers.
- All web routes are defined in `routes/web.php`.
- Every API route group that requires authentication **must** be wrapped in the `auth.jwt` middleware.
- Every role-restricted route group **must** include the `role:<role>` middleware.
- Route names must follow `kebab-case` and be namespaced by domain (e.g., `admin.users.index`, `inspector.inspections.show`).

---

## ✅ 10. General Principles

- **Consistency over convenience.** Follow the established pattern even if a shortcut seems harmless.
- **No feature creep.** Implement exactly what is asked — do not add unrequested behaviour.
- **Fail loudly.** Validation errors, unauthorised access, and missing resources must return proper HTTP status codes (`422`, `401`, `403`, `404`) — never a silent `200` with an error in the body.
- **No direct `DB::` facade calls** outside of Repositories.
- **No business logic in Blade templates.** Views display data; they do not compute or transform it.
- When in doubt, refer to `FILE_ARCHITECTURE.md` as the ground truth for file placement.
