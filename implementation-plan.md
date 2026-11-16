# Implementation Plan — Abraham Montes Website (Laravel Rebuild)

This document is the execution blueprint for rebuilding the current PHP site as a Laravel monolith, per `claude.md`. It resolves the architectural decisions and ambiguities up front so that implementation can proceed phase by phase without re-litigating design choices mid-build. Each phase ends with a pause point for review, per the client's explicit instruction.

Companion files: `tasks.md` (checklist, updated as work completes) and `prompts.md` (running log of prompts, in order).

---

## 1. Snapshot of the Current Site (source of truth for content/design parity)

- Raw PHP with a hand-rolled template includer (`IncluirPlantilla()` in `includes/app.php`), templates in `includes/templates/*.php`: `header`, `about`, `res` (research), `papers` (publications), `news`, `blog`, `carousel` (quotes), `contacto`, `footer`.
- Styling: SCSS compiled via Gulp (`src/scss/base`, `src/scss/layout`), one `_*.scss` partial per section — the new project should preserve this separation-by-component discipline.
- JS: vanilla, plus `rellax.min.js` (parallax) and `modernizr.js`.
- DB access: raw `mysqli` via `includes/config/database.php`, credentials from `.env` (host/user/pass/db = `abrahammontes`).
- Dependencies: `intervention/image` (v2), `phpmailer/phpmailer`, `components/jquery`, `vlucas/phpdotenv`.
- Assets: existing photography/icons in `Imagenes/` and `src/img/` (jpg/png originals + generated `.webp`), reusable as seed content per the client's instruction to reuse current images.
- No FontAwesome files present anywhere in the repo today, despite `claude.md` referencing `view-theme-base/assets/fonts` as their location — this path does not exist yet and must be created fresh (see §3.3).

This confirms the rebuild is a genuine re-architecture, not a lift-and-shift: content/imagery carries over, code does not.

---

## 2. Stack & Tooling

| Concern | Decision |
|---|---|
| Framework | Laravel (latest stable LTS-track release at project init — verify current major via `composer create-project laravel/laravel` rather than assuming a version number here; target PHP 8.2+) |
| DB | MySQL, reuse local schema name `abrahammontes`, rebuilt via Laravel migrations (see §8 on data migration) |
| Frontend | Blade templates, native CSS3 (SCSS source compiled by Vite — Laravel's standard bundler; this is a build step, not a JS framework, so it doesn't conflict with "no frameworks"), native vanilla JS (ES modules) |
| Auth | Laravel's built-in session auth + a custom guard, no Breeze/Jetstream/Fortify scaffolding (their bundled UI doesn't fit the custom design requirement and would need to be gutted anyway) |
| Image handling | `intervention/image` ^3.0 (old project pinned v2; v3 is a clean rewrite, fine to adopt fresh since no legacy code depends on the old API) |
| HTML sanitization | `mews/purifier` — required because the Jodit WYSIWYG stores raw HTML; must be purified before save/render to prevent stored XSS |
| Multilingual content | `spatie/laravel-translatable` (JSON columns per locale) for DB content; Laravel's native `lang/en`, `lang/es` files for static UI chrome (see §3.4) |
| Mail | Laravel's native `Mail` facade / SMTP driver via `.env` — replaces `phpmailer/phpmailer` (Laravel's mail layer is the framework-idiomatic choice and removes a redundant dependency) |
| jQuery | Dropped — coding preferences mandate vanilla JS only |
| Citation lookups | Crossref REST API (free, no key, keyed by DOI) via Laravel's `Http` facade — see §3.9 for why this replaces literal Google Scholar scraping |
| Human verification | Cloudflare Turnstile (replaced Google reCAPTCHA v2 in the Nov 2026 redesign), verified server-side via `Http::post()` to Turnstile's `siteverify` endpoint — no extra package needed |
| Testing | Pest PHP for feature tests (auth, CRUD, route protection) |
| Code style | Laravel Pint |

---

## 3. Architecture Decisions

### 3.1 Authentication (Phase 1)

Login requires **email + phone number + password**, all three mandatory and all three must match the stored admin record — this is a knowledge-based triple-factor check, not SMS/OTP 2FA (an OTP flow would need an SMS gateway, which contradicts "not overly complex" and isn't mentioned anywhere else in the spec). Implementation:

- `admins` table (see §6) — realistically a single row, but modeled as a table, not a config value, so the profile-editing screen (Phase 11) has something real to edit.
- Custom `AdminAuthController` (not a Breeze scaffold): validates all three fields present, then `Auth::attempt(['email' => ..., 'phone' => ..., 'password' => ...])` against a custom guard `admin` backed by the `admins` table.
- Passwords hashed with bcrypt (Laravel default `Hash::make`).
- "Persist login" checkbox → Laravel's native remember-me cookie (`Auth::attempt($credentials, $remember)`).
- Login route throttled (`throttle:6,1`) to blunt brute force.
- Route: `GET/POST /access-abraham`. Logout: `POST /access-abraham/logout`.
- CSRF via Laravel's default `web` middleware group — no manual handling needed.

### 3.2 Route Protection

- All dashboard routes live under a single route group: prefix `panel-abraham`, middleware `['auth:admin']`. No dashboard route or action is ever defined outside this group.
- State-changing admin actions (POST/PUT/PATCH/DELETE) get CSRF protection for free via the `web` middleware group; no additional work needed beyond staying inside it.
- Guest-only routes (`/access-abraham`) get a `guest:admin` middleware so a logged-in admin hitting the login URL is redirected straight to the dashboard.

### 3.3 Two Separate Theming Systems (do not conflate)

`claude.md` describes two different things under "themes" — separating them now avoids a costly rework later:

1. **Public Site Theme Engine (Phase 8)** — 5 swappable full visual designs for the public-facing site (homepage, about, blog, etc.). Structure:
   - `resources/views/themes/base/` — shared layout skeleton, shared Blade partials (nav, footer scaffolding) that every theme extends.
   - `resources/views/themes/{theme-slug}/` — one folder per theme (5 total), each overriding/extending the base layout with its own look.
   - `public/themes/base/assets/fonts/` — **this is the `view-theme-base/assets/fonts` folder `claude.md` refers to.** It holds a self-hosted FontAwesome Free webfont package, shared by all 5 themes (download once, no CDN dependency, no external request at runtime).
   - `public/themes/{theme-slug}/assets/{css,js,img}/` — theme-specific compiled assets, each theme's SCSS/JS kept fully separate per the "do not mix CSS across components" coding rule.
   - A `ThemeManager` service resolves the active theme (from `settings.active_theme`) and exposes a Blade view-namespace prefix so controllers render `theme::section.about` instead of hardcoding a theme path.
   - Activating a theme in the dashboard is a single settings write + cache clear — takes effect immediately, no rebuild/deploy step.
   - Theme picker UI shows a live preview in a modal (iframe or screenshot) before activation, per spec.

2. **Dashboard Appearance Preferences (Phase 10)** — light/dark mode + one accent color (from 8 presets, one being blue) for the **admin panel only**, persisted on the `admins` row so it survives logout (not a cookie/session value). Implemented with a `data-theme` attribute on the dashboard `<html>` tag and CSS custom properties — completely separate CSS file tree from the public themes.

"Themes selectable in landing or multipage format" (Phase 8 bullet) is read as: the site-wide `site_mode` (landing vs. multipage, see §3.6) is an independent setting from theme choice, not a per-theme variant — every theme must support both rendering modes. Flagged in §9 for confirmation since the wording is ambiguous.

### 3.4 Internationalization

Two layers, kept distinct:

- **Static UI chrome** (button labels, nav labels, form field labels, validation messages): Laravel's native `lang/en/*.php` and `lang/es/*.php`, accessed via `__()`. This is where "if there's no data, show a fashionable empty state" copy lives too.
- **Admin-editable marketing copy** ("public phrases" — Phase 11, e.g. hero tagline, section intros): a dedicated `public_phrases` table with a translatable JSON `value` column (`{"en": "...", "es": "..."}`) so the admin can tweak wording without a deploy.
- **DB content** (blog posts, research, publications, news, quotes): translatable JSON columns via `spatie/laravel-translatable` on the relevant fields (title, body, excerpt, etc.) — one row per record, not one row per locale, avoiding join complexity.
- Active locale resolved by a `SetLocale` middleware reading a `locale` session/cookie value, defaulting to English. A visible language switcher (EN/ES) appears in both public header and — implicitly — is unaffected in the admin panel (admin panel stays in the agent's working language; only the public site needs the switcher per Phase 13).

### 3.5 Media & File Handling

- Laravel's `public` disk (`storage/app/public`, symlinked to `public/storage`).
- Uploads validated by mime/extension allowlist + max size at the FormRequest level.
- On upload, `intervention/image` generates a `.webp` variant alongside the original, mirroring the existing project's jpg+webp pairing convention — keeps the new site's performance characteristics at least as good as the old one.
- One reusable Blade component `<x-image-upload>` (drag/drop + preview) used everywhere an image field appears — satisfies the "prioritize component reuse" rule.

### 3.6 Settings & Feature Toggles

A single `settings` key-value table (not a scattered scaffold of one-off config tables) holds all sitewide toggles: `site_mode` (landing/multipage), `active_theme`, `default_locale`, `turnstile_site_key`, `turnstile_secret_key` (formerly reCAPTCHA keys), `contact_notify_email`, `notifications_email_enabled`. Rationale: these values change independently and rarely need relational integrity — a key-value table avoids a migration every time a new toggle is added later (an explicit "optimization" call-out, see §11).

Section visibility (Phase 10's "any section can be activated/deactivated") is modeled separately as a `sections` table (`key`, `label`, `enabled`, `display_order`) because it's iterated over to build both the landing page's section order and the multipage nav — a JSON blob would be awkward to query/order in Blade.

### 3.7 WYSIWYG Editor (Jodit)

- Jodit 4.7.6 is downloaded once from the CDN URLs given in `claude.md` and vendored into `public/vendor/jodit/jodit.min.{css,js}` — not npm-managed, not re-fetched from a CDN at runtime, per explicit instruction.
- One reusable Blade component `<x-wysiwyg-editor name="body" :value="$value" />` initializes Jodit on a hidden textarea; used for every "large text field": blog body, research description, publication abstract, news body, about-me bio. (Quote text is short and stays a plain textarea.)
- All Jodit output is passed through `mews/purifier` server-side before persisting, and again defensively before echoing (belt-and-braces XSS prevention on stored HTML).

### 3.8 Security Baseline (applies to every phase, not just Phase 1)

- Bcrypt password hashing, CSRF via Laravel defaults, Eloquent/query-builder parameterized queries (no raw SQL string interpolation), Blade's automatic `{{ }}` escaping everywhere except the one purified WYSIWYG output point.
- Contact form: honeypot hidden field + server-side throttle from Phase 1 onward (the general requirement "validate genuine human-requested contact" applies globally); reCAPTCHA v2 layered on top starting Phase 13 once it's explicitly introduced.
- File uploads: extension + mime allowlist, size caps, stored under `storage/app/public`, never executed as PHP.
- `.env` never committed; `admins.password` never serialized/logged.

### 3.9 Publications: Citation Counts (flag for confirmation)

`claude.md` asks for automated citation counts "from Google Scholar." Google Scholar has no official API; scraping it violates its Terms of Service and is fragile (frequent IP blocks/CAPTCHA walls) — not something to build into a production feature. **Recommended substitute:** Crossref's free public REST API, queried by the publication's DOI (already the natural primary key per spec), which returns a `is-referenced-by-count` field. This is legitimate, keyless, and stable. A `citation_source` flag (`auto`/`manual`) lets the admin override the number by hand for anything Crossref doesn't cover (e.g. some conference papers). This substitution is called out again in §9 as something to confirm with the client before Phase 6, since it changes the letter of the spec even though it satisfies the intent.

### 3.10 SEO Baseline (Phase 12/7 territory, called out separately since it's cross-cutting)

Semantic HTML5 landmarks, per-page `<title>`/meta description/Open Graph tags driven by the section's translatable content, slugs for blog/publications, `sitemap.xml` + `robots.txt` generation, JSON-LD `Person` schema on the About page and `Article` schema on blog posts. This isn't a separate phase — it's baked into Phase 12's public templates.

---

## 4. Proposed Laravel Project Structure

```
app/
  Http/
    Controllers/
      Public/          # PublicHomeController, BlogController, ResearchController, ...
      Admin/            # Admin\BlogController, Admin\SettingsController, ...
      Auth/AdminAuthController.php
    Middleware/SetLocale.php
    Requests/           # one FormRequest per create/update action
  Models/                # Admin, BlogPost, ResearchProject, Publication, NewsItem, Quote,
                          # SocialLink, PublicPhrase, Setting, Section, ContactMessage, ThemeImageSlot
  Services/
    ThemeManager.php
    CitationLookupService.php   # Crossref client
    RecaptchaVerifier.php
  View/Components/       # WysiwygEditor, ImageUpload, EmptyState, ConfirmModal, ShareButtons
database/
  migrations/
  seeders/                # DevSeeder ports sample content from Imagenes/ + src/img/
resources/
  views/
    themes/
      base/               # shared layout skeleton all themes extend
      theme-1 .. theme-5/
    admin/                 # dashboard views (its own layout, unrelated to public themes)
    components/
  lang/
    en/  es/
  js/
    admin/  public/  themes/{slug}/
  scss/
    admin/  themes/base/  themes/{slug}/
public/
  themes/base/assets/fonts/     # self-hosted FontAwesome
  themes/{slug}/assets/
  vendor/jodit/                 # vendored Jodit build
  storage -> ../storage/app/public (symlink)
routes/
  web.php        # public routes
  admin.php       # everything under panel-abraham, included from web.php
tests/
  Feature/
```

---

## 5. Data Model Overview

| Table | Key columns | Notes |
|---|---|---|
| `admins` | name, email(unique), phone(unique), password, avatar_path, ui_color_mode, ui_accent_color, remember_token | Phase 1/11 |
| `settings` | key(unique), value | Phase 7/8/10/13 toggles, see §3.6 |
| `sections` | key, label, enabled, display_order | Phase 10 |
| `social_links` | platform, url, icon_class, display_order, active | Phase 11 |
| `public_phrases` | key(unique), group, value(json: en/es) | Phase 11 |
| `blog_posts` | slug(unique), title(json), excerpt(json), body(json/html), featured_image, status, published_at | Phase 3 |
| `quotes` | author_name, quote_text(json), author_image, display_order, active | Phase 4 |
| `research_projects` | title(json), summary(json), body(json/html), image, display_order, active | Phase 5 |
| `publications` | doi(unique), type(journal/conference), title(json), abstract(json), image, pdf_path, video_url, citation_count, citation_source, citation_synced_at, display_order | Phase 6 |
| `news` | title(json), body(json/html), published_at, display_order | Phase 6/7 area |
| `news_images` | news_id, path, display_order | multiple images per news item, matching current `news_*_img1/img2` pattern |
| `theme_image_slots` | theme_slug, slot_key, label, current_image_path, default_image_path | Phase 9 — "slots in themes with test images" |
| `contact_messages` | name, email, phone, message, ip_address, user_agent, captcha_verified, status | Phase 1 (basic) → 13 (captcha; renamed from recaptcha_verified for Turnstile) |
| `notifications` | Laravel's built-in polymorphic table | Phase "view notifications" |
| `password_reset_tokens` | Laravel default | not explicitly requested, added as baseline good practice — flagged in §11 |

---

## 6. Route Map (high level; exact resource routes generated per-module in their phase)

**Public** (multipage mode; landing mode reuses the same section partials on one page with smooth-scroll anchors):
`/`, `/about-me`, `/news`, `/news/{slug}`, `/blog`, `/blog/{slug}`, `/research`, `/publications`, `/quotes-i-like`, `/contact-me`, `POST /contact-me`, `GET /lang/{locale}`

**Admin** (all under `panel-abraham`, `auth:admin`):
`GET/POST /access-abraham` (outside the panel prefix, guest-only), `POST /access-abraham/logout`,
`/panel-abraham` (dashboard home), resourceful CRUD at `/panel-abraham/blog`, `/research`, `/publications`, `/news`, `/quotes`,
`/panel-abraham/themes` (list, preview, activate), `/panel-abraham/images` (media + slots), `/panel-abraham/settings/{general|phrases|social|notifications|appearance}`, `/panel-abraham/profile`, `/panel-abraham/notifications`.

---

## 7. Phase-by-Phase Plan

Each phase below assumes all prior phases are complete and untouched (regression safety per general requirements). Each phase ends with a **pause point**: the agent stops, the client tests, then gives the go-ahead.

### Phase 0 — Foundation (precedes the client's numbered phases; required scaffolding)

- Install Laravel, configure `.env` against the existing local `abrahammontes` MySQL DB.
- Inspect the current DB's actual tables/content (the site may or may not already have real data in MySQL beyond the raw-PHP file-based content) and decide, per record type: rebuild fresh via new migrations (default assumption, since the old schema is unknown/likely minimal) vs. write a one-off seeder porting existing rows. **This inspection step must happen before writing migrations that would clobber real data.**
- Base migrations: `admins`, `settings`, `sections`.
- Install and wire: Vite, Pint, Pest, `intervention/image`, `spatie/laravel-translatable`, `mews/purifier`.
- Self-host FontAwesome under `public/themes/base/assets/fonts/`.
- Vendor Jodit under `public/vendor/jodit/`.
- `ThemeManager` service skeleton + one placeholder theme so routes render *something* (full 5-theme build happens in Phase 8; earlier phases render against this placeholder).
- Base Blade layout (`themes/base`) with semantic landmarks, FontAwesome linked, REM sizing with `html { font-size: 11px }`.
- **Pause point:** client confirms local Laravel app boots, connects to `abrahammontes`, and serves a blank styled shell.

### Phase 1 — Private Admin Panel: Login

- `admins` seeder for the initial admin record (name/email/phone/password supplied by client out-of-band, not committed to the repo).
- `AdminAuthController@showLogin`, `@login`, `@logout`; custom `admin` guard/provider.
- Login form at `/access-abraham`: email, phone, password, "keep me logged in" checkbox — all three fields required, inline validation errors, no `alert()`/`confirm()`.
- Throttling on the login route; generic error message on failure (no "email exists but phone wrong" leakage).
- **Acceptance checklist:** wrong credentials rejected with visible inline error; correct credentials log in; "remember me" persists across browser restart; logout clears session; direct navigation to any `/panel-abraham/*` URL while logged out redirects to login.
- **Pause point.**

### Phase 2 — Dashboard Shell

- `/panel-abraham` dashboard home with quick-access cards (e.g. "+ New Blog Post", "+ New Publication", "View Messages").
- Left sidebar nav, grouped/dropdown sections mirroring §5's modules: **Content** (Blog, Research, Publications, News, Quotes), **Appearance** (Themes, Images, Colors), **Settings** (General, Phrases, Social, Notifications), plus top-level Profile (via avatar) and Notifications bell.
- Responsive collapse to an off-canvas menu on small screens.
- Empty dashboard home state before any content exists ("kind/fashionable" per general requirement).
- **Pause point:** client navigates the full menu tree; every link resolves (may be a stub page) without a 404/500.

### Phase 3 — Blog CRUD + Jodit + Social Share

- `blog_posts` migration; `Admin\BlogController` resourceful CRUD; `<x-wysiwyg-editor>` on the body field; `<x-image-upload>` for featured image; slug auto-generated from title with uniqueness check; draft/publish status; publish date shown on public post.
- Public `/blog` index (empty state if no posts) and `/blog/{slug}` detail with native-JS share buttons (X/Twitter, LinkedIn, Facebook, WhatsApp — plain share-intent links, no SDKs, built with `createElement`/`appendChild`, never `innerHTML`).
- **Acceptance checklist:** create/edit/delete a post; image uploads and renders with webp variant; Jodit content renders sanitized on the public page; share buttons open correct pre-filled share URLs; deleting a post doesn't 404 the blog index.
- **Pause point.**

### Phase 4 — Quotes Carousel

- `quotes` CRUD (author name, quote text via translatable field, author photo).
- Public carousel: full-viewport, author photo on one side fading into a solid color panel holding the quote; autoplay with pause-on-hover, manual prev/next + jump-to-index (dots or thumbnails), infinite loop. Built as a single reusable vanilla-JS component (`themes/base` asset) so every public theme in Phase 8 can restyle it via CSS variables without rewriting behavior.
- **Acceptance checklist:** autoplay advances and loops; manual navigation to an arbitrary quote works; carousel with 0/1 quotes degrades gracefully (empty state / no broken nav arrows).
- **Pause point.**

### Phase 5 — Research Projects CRUD

- `research_projects` CRUD (title, body via Jodit, image).
- Public research listing with a collapsible "read more" dropdown per project (native `<details>`/JS toggle, animated).
- **Pause point** with the same acceptance shape as prior CRUD phases.

### Phase 6 — Publications CRUD

- `publications` CRUD: type (journal/conference) selector, optional image, optional PDF upload (downloadable on the public page if present), optional video URL (shows a "watch highlights" button if present), DOI field (unique), citation count.
- `CitationLookupService` calls Crossref by DOI on save (or via a manual "refresh" button); falls back to manual entry if Crossref has no record — see §3.9, **flagged for client confirmation before building**, since it substitutes the literal "Google Scholar" instruction.
- Public listing styled with inspiration from robertavaltorta.com/publications (image + type badge + abstract snippet + PDF/video/citation affordances).
- **Pause point.**

### Phase 7 — Public Info Configuration

- Admin "General Settings" screen wiring the `settings` table: site name/tagline, contact notify email, default locale, etc. — anything publicly displayed that isn't already covered by a dedicated CRUD.
- **Pause point:** client changes a setting and confirms it reflects on the public site without a deploy.

### Phase 8 — Public Theme Engine (5 themes)

- Build out `themes/base` + 5 concrete theme folders per §3.3, each with its own SCSS/JS bundle and a full pass through all public sections (about, news, research, publications, blog, quotes, contact).
- Theme picker admin screen: grid of 5 cards, "Preview" opens a modal showing the theme rendered, "Activate" commits it immediately via the `settings.active_theme` write.
- Confirm/resolve the landing-vs-multipage ambiguity noted in §3.3 with the client before/at this phase's kickoff.
- **Pause point:** client previews and activates each of the 5 themes in turn, confirms no section is broken under any of them.

### Phase 9 — Image & Icon Management

- `theme_image_slots` seeded per active theme with the existing project's images (`Imagenes/`, `src/img/`) as the default "test images" per spec.
- Admin media screen: replace any slot's image, upload new images into a general library reused across blog/research/publications/news pickers (component reuse — same `<x-image-upload>` throughout).
- **Pause point.**

### Phase 10 — Dashboard Appearance + Section Toggles

- Dashboard-only light/dark + 8-swatch accent color picker (§3.3 item 2), persisted on `admins`, survives logout.
- `sections` toggle screen: enable/disable each public section; disabling one removes it from both the multipage nav and the landing page's section list without breaking layout/ordering of the remaining sections.
- **Pause point:** toggle dark/light + color, log out/in, confirm it stuck; disable a section, confirm it disappears cleanly from the public site.

### Phase 11 — Phrases, Social Networks, Notifications, Profile

- `public_phrases` CRUD grouped by area (hero, empty states, footer, etc.).
- `social_links` CRUD (platform, URL, FontAwesome icon, order, active toggle) rendered publicly.
- "Email & Notifications" settings screen (which events trigger admin emails — currently just "new contact message"; toggle on/off, set recipient).
- Header avatar/name → profile edit (name, email, phone, password change, avatar) — this is where the `admins` row from Phase 1 finally gets a UI.
- Sidebar "View Public Site" button (opens the live public site in a new tab).
- **Pause point.**

### Phase 12 — Public Site Assembly (Landing vs Multipage) + Info About Me

- Build the About Me public page/section fully (bio via translatable Jodit content).
- Implement `site_mode` switch end-to-end: landing mode renders every enabled section's partial on `/` with smooth-scroll anchor nav (`scrollIntoView`, `IntersectionObserver` for active-link highlighting); multipage mode renders each section at its own dedicated URL from §6, using the *same* Blade partials (this reuse is the point — no content duplication between the two modes).
- SEO pass per §3.10 across all public pages.
- **Pause point:** toggle landing/multipage in settings and confirm both render identically in content, correctly in structure.

### Phase 13 — Language Selector + reCAPTCHA

- Public EN/ES switcher wired to `SetLocale` middleware; confirm every public string (static chrome + translatable DB content + public phrases) actually flips.
- reCAPTCHA v2 on the contact form, server-verified via `siteverify`; light/dark variant matched to the active dashboard-independent public theme's light/dark state (full custom-color re-skinning of Google's widget isn't possible — flagged in §9 as a hard external constraint, light/dark matching is the practical ceiling).
- **Pause point:** submit contact form in both languages, with and without completing the CAPTCHA, confirm correct accept/reject behavior and that a real submission reaches the configured notification email.

---

## 8. Data Migration Strategy (old → new)

The old site's persistence is largely file/template-driven, not a rich relational schema — the `abrahammontes` DB likely holds little beyond what `includes/config/database.php`'s consumers wrote directly (contact form submissions, if any). Plan:

1. At Phase 0, connect to the existing DB and enumerate actual tables/rows present.
2. If it's effectively empty of content worth keeping: proceed straight to fresh Laravel migrations against the same DB name — no data migration needed, only re-entry of content through the new CRUD screens as each phase lands (which the client will be doing anyway while testing each phase).
3. If real content exists (e.g. prior contact submissions): write a one-off Phase 0 seeder to port just those rows into the new schema before the old tables are dropped by migration.

## 9. Open Questions / Assumptions Requiring Confirmation

1. **Citation counts (§3.9):** proposing Crossref-by-DOI instead of literal Google Scholar scraping. Needs explicit sign-off before Phase 6.
2. **"Themes selectable in landing or multipage format" (Phase 8 wording):** interpreted as "every theme supports both site-wide modes," not "each theme locks in one mode." Confirm before Phase 8.
3. **reCAPTCHA custom theming (§3.10):** Google's widget only offers a light/dark toggle, not full brand-color re-skinning. Confirm the light/dark-matching approach is an acceptable reading of "fits within the selected theme."
4. **Initial admin credentials:** need the actual email/phone/password to seed (not something to invent or commit to the repo) — request separately, out of band, when Phase 1 starts.
5. **Multiple admins:** spec speaks of "the engineer" singular throughout. Plan assumes one admin account; if multiple admins/roles are ever needed, `spatie/laravel-permission` is the drop-in extension point (not built now — see §11).

## 10. Risks & Mitigations

| Risk | Mitigation |
|---|---|
| Crossref lacks a record for some publications | Manual override field always available; `citation_source` flag makes this visible in the UI |
| Large uploaded images (existing `Imagenes/` folder has multi-MB PNGs/JPGs) slow page loads | Intervention Image resize + webp generation on every upload, enforced not optional |
| 5-theme system causing CSS bleed between themes | Hard separation per theme folder (§3.3) + Pint/lint step + a Phase 8 acceptance pass that cycles through all 5 themes on every section |
| Jodit-authored HTML enabling stored XSS | `mews/purifier` on every save and render path, no exceptions |
| Google reCAPTCHA availability/keys not yet provisioned | Contact form ships with honeypot + throttle from Phase 1 so it's protected before Phase 13 lands |

## 11. Optimization Suggestions (flagged per client instruction)

- `settings` as key-value instead of one column/table per toggle — avoids a migration for every future setting (§3.6).
- `spatie/laravel-translatable` (JSON columns) instead of per-locale duplicate rows or separate translation tables — fewer joins, simpler queries, and the client only has 2 locales so JSON columns won't get unwieldy.
- Reusable Blade components (`x-wysiwyg-editor`, `x-image-upload`, `x-empty-state`, `x-confirm-modal`, `x-share-buttons`) built once in Phase 0–3 and reused across every subsequent CRUD module, directly satisfying the "prioritize component reuse" requirement and shortening every later phase.
- `password_reset_tokens` table added as baseline good practice even though not explicitly requested — cheap to include now, expensive to retrofit later, and it's a standard Laravel migration (zero extra design cost).
- Landing-mode and multipage-mode sharing the exact same Blade partials (Phase 12) rather than maintaining two parallel template sets — this is both a simplicity and a maintainability win, and it's the reason section partials are designed as standalone reusable components from Phase 3 onward rather than being built inline into a single monolithic homepage template.

---

## 12. How Later Sessions Should Use This Document

1. Read this file plus `tasks.md` at the start of every session before writing code.
2. Work strictly one phase at a time, in order; do not start a phase whose predecessor hasn't been marked complete and confirmed in `tasks.md`.
3. Check off `tasks.md` items as they're finished — don't batch updates.
4. Log every new prompt received, in order, into `prompts.md`.
5. Stop at each phase's pause point and wait for explicit go-ahead before continuing.

---

## Redesign (November 2026) — motion-led public design, Turnstile, Spanish content

- **Public design** rebuilt on the shared base (all 5 themes inherit it; each theme only adds tokens, including a new `--color-accent-2` gradient stop). Same sections/components as before.
- **Motion layer** (`resources/js/themes/base/motion.js`, `resources/scss/themes/base/partials/_motion.scss`): reveal-on-scroll, split-letter headings, multi-layer parallax, drifting ghost section titles, custom cursor, magnetic buttons, 3D tilt cards with glare, animated counters, typed hero roles, marquee ribbon, scroll progress, back-to-top ring, once-per-session intro, cross-document View Transitions. Everything is gated by `.motion-ready` (admin switch + `prefers-reduced-motion`), so the site stays fully static and visible without it.
- **Admin additions (minimal):** Appearance → Motion & Effects (6 settings), General → Spanish tagline, Settings → Cloudflare Turnstile (replaces the reCAPTCHA page), new editable phrases (hero greeting/roles, footer CTA, section leads) in Public Phrases.
- **Layouts:** each theme's `layouts/app.blade.php` now includes the shared `themes/base/partials/document.blade.php` shell, so the motion chrome lives in one place.
- **Optimization note:** `Setting::get()` runs one query per key; a request-scoped settings cache would noticeably reduce queries per public page and is a good next step.

