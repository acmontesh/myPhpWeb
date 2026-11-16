# Tasks

Status legend: `[ ]` not started · `[~]` in progress · `[x]` done · `[!]` blocked/needs input

See `implementation-plan.md` for full detail behind each item. Update this file the moment a task's status changes — do not batch updates.

---

## Phase 0 — Foundation
- [x] Install Laravel (12.66.0), configure `.env` against local `abrahammontes` DB
- [x] Inspect existing `abrahammontes` DB content — found real data in `blogentries` (3 rows), `news` (3 rows), `papers` (4 rows, DOI-keyed); left untouched, to be ported when Phase 3/6 create their tables (see prompts.md note)
- [x] Base migrations: `admins`, `settings`, `sections`
- [x] Install/wire Vite, Pest, `intervention/image` v3, `spatie/laravel-translatable`, `mews/purifier`; dropped default Tailwind scaffold (conflicts with "CSS3 native" requirement), added `sass`
- [x] Self-host FontAwesome (7.3.1 free) under `public/themes/base/assets/fonts/fontawesome/`
- [x] Vendor Jodit 4.7.6 under `public/vendor/jodit/` (from the exact CDN URLs in claude.md)
- [x] `ThemeManager` service (`app/Services/ThemeManager.php`) + `base` theme as the Phase 0 placeholder
- [x] Base Blade layout (`themes/base`), semantic HTML5, REM sizing at 11px base, burnt-orange/white defaults
- [x] **Pause point:** app boots, connects to `abrahammontes`, serves styled shell — verified via `php artisan serve` + curl (home, login page, route-protection redirect all return correct status codes)

## Phase 1 — Admin Login
- [x] `AdminAuthController` (login/logout), custom `admin` guard backed by `admins` table
- [x] Login form at `/access-abraham` (email + phone + password, all required, "keep me logged in")
- [x] Throttling (`throttle:6,1`) on login POST; generic failure messaging (no field-specific leakage)
- [x] `AdminSeeder` reads `ADMIN_NAME`/`ADMIN_EMAIL`/`ADMIN_PHONE`/`ADMIN_PASSWORD` from `.env` — never hardcoded; skips gracefully with a warning if unset
- [x] **Pause point:** client proceeded to Phase 2

## Phase 2 — Dashboard Shell
- [x] `/panel-abraham` dashboard home with quick-access cards (New Blog Post, New Research Project, New Publication, New Quote)
- [x] Sidebar nav with grouped/dropdown sections (Content, Appearance, Settings — driven by `config/admin_nav.php`), active-state highlighting + auto-expand on current route
- [x] Topbar with notifications bell (empty state) and avatar/name dropdown (Edit profile stub + Log out)
- [x] Responsive off-canvas sidebar collapse (hamburger toggle + backdrop) below 900px
- [x] Every nav destination not yet built resolves to a themed "coming soon" stub (`admin.shared.coming-soon`, one reused component) naming its actual phase — no 404s/500s
- [x] **Pause point:** client proceeded to Phase 3

## Phase 3 — Blog
- [x] `blog_posts` migration (translatable `title`/`excerpt`/`body` JSON columns, per implementation-plan.md §3.4) + `BlogPost` model + `Admin\BlogController` full CRUD (`Route::resource`, slug-keyed)
- [x] `<x-wysiwyg-editor>` (Jodit) and `<x-image-upload>` (drag/drop + live preview) components, built for reuse in every later CRUD phase
- [x] Also built now for reuse: `<x-admin.empty-state>`, `<x-admin.confirm-modal>` (replaces `confirm()`, styled to match the dashboard, wired to every delete form via `data-confirm`), `<x-share-buttons>`
- [x] Bilingual EN/ES tabs in the form (English required, Spanish optional) — data layer supports both from day one per the plan, even though the public switcher itself isn't wired until Phase 13
- [x] `ImageUploadService`: resizes on upload, saves original + `.webp` variant (reused by every future image field)
- [x] HTML from Jodit purified server-side via `mews/purifier` before save (custom allow-list: headings, blockquote, code, tables, etc. added on top of the package default)
- [x] Slug auto-generated from the English title at create time, unique, stable afterward (not regenerated on edit, for SEO)
- [x] Draft/published status; `published_at` stamped once on first publish and displayed on the public post
- [x] Public `/blog` index (empty state when no posts) + `/blog/{slug}` detail, with a "Blog" link added to the base theme header for reachability
- [x] Social share buttons (X, LinkedIn, Facebook, WhatsApp) — vanilla JS `createElement`/`appendChild`, no `innerHTML`
- [x] **Pause point:** client proceeded to Phase 4

## Phase 4 — Quotes Carousel
- [x] `quotes` migration/model (`author_name` plain, `quote_text` translatable JSON, `author_image`, `display_order`, `active`) + `Admin\QuoteController` CRUD, reusing the Phase 3 components (EN/ES tabs, `<x-image-upload>`, `<x-admin.empty-state>`, `<x-admin.confirm-modal>`) — no new admin-side scaffolding needed
- [x] Full-viewport carousel (`themes/base/partials/quotes-carousel.blade.php`, included rather than an `<x-component>` so Phase 12 can drop it into the landing page or a standalone route the same way): author photo fading into a solid accent-color panel holding the quote, per claude.md's Phase 4 description
- [x] Autoplay (6s) with pause-on-hover, manual prev/next, dot jump-to-index, infinite loop — one vanilla-JS module (`resources/js/themes/base/carousel.js`), CSS-variable driven so Phase 8 themes can restyle without touching behavior
- [x] Graceful degradation: 0 quotes shows the empty-state component; 1 quote renders statically with nav/dots omitted entirely (carousel JS also no-ops on ≤1 slide as a second guard)
- [x] Public route `/quotes-i-like` + "Quotes I Like" link in the base theme header, same reachability pattern as Phase 3's `/blog`
- [x] **Pause point:** client proceeded to Phase 5

## Phase 5 — Research Projects
- [x] `research_projects` migration/model (translatable `title`/`summary`/`body`, `image`, `display_order`, `active`) + `Admin\ResearchController` CRUD — same shape as blog/quotes, all shared components reused again (EN/ES tabs, Jodit on the full-text body, plain textarea on the short summary, image upload, empty state, confirm modal)
- [x] Public `/research` listing (empty state when none): image, title, always-visible summary, and a native `<details>` "read more" revealing the full purified body
- [x] Read-more is animated (not the browser's instant native toggle) via a small vanilla-JS height-transition enhancement (`resources/js/themes/base/accordion.js`) that intercepts the `<summary>` click with `preventDefault()` — degrades to a plain (unanimated but fully functional) native `<details>` if JS fails to load
- [x] "Research" link added to the base theme header
- [x] **Pause point:** client proceeded to Phase 6

## Phase 6 — Publications
- [x] **Citation source confirmed with client before building:** Crossref API by DOI (recommended option), not literal Google Scholar scraping — resolves plan §9.1
- [x] `publications` migration/model (`doi` unique, `type` journal/conference, translatable `title`/`abstract` with Jodit on the abstract per §3.7, `image`, `pdf_path`, `video_url`, `citation_count`, `citation_source`, `citation_synced_at`, `display_order`, `active`) + `Admin\PublicationController` full CRUD
- [x] `CitationLookupService`: queries `api.crossref.org/works/{doi}` (free, keyless). Auto-fetched on save when the admin leaves citation count blank; a manually-entered count is flagged `citation_source = manual` and never silently overwritten; a "Refresh from Crossref" button re-syncs on demand
- [x] DOI input accepts a bare DOI or a full `doi.org/...` URL, normalized before saving/lookup
- [x] Reused `<x-image-upload>` for the image and built one new reusable component, `<x-file-upload>`, for the PDF (native file input + link to the current file, no preview needed)
- [x] Public `/publications` listing (empty state when none): image, type badge, title, plain-text abstract snippet, DOI link, citation count, PDF download button and "Watch highlights" video button — both PDF and video are entirely optional per publication
- [x] "Publications" link added to the base theme header
- [x] **Found and fixed a local-environment bug, not a code bug:** this machine's PHP had no CA certificate bundle configured, so any outbound HTTPS call (including to Crossref) failed with an SSL error that `CitationLookupService` was silently swallowing via its graceful-degradation path. Downloaded the standard `cacert.pem` and pointed `curl.cainfo`/`openssl.cafile` at it in `php.ini`; verified the fix by calling the real Crossref API for a DOI from the old `papers` table and getting back a real citation count (9)
- [ ] **Pause point — awaiting client validation:** log in, add a publication with a real DOI (e.g. one from the old `papers` table — see prompts.md), leave citation count blank and confirm it auto-fills from Crossref on save, try the "Refresh from Crossref" button, upload a PDF and set a video URL, confirm both buttons appear correctly at `/publications`

- [x] **Pause point:** client proceeded to Phase 7

## Phase 7 — Public Info Configuration + News
- [x] claude.md was updated (by the client, between phases) to explicitly assign "Manage news" a CRUD with two images to this phase — resolves the gap flagged back in Phase 3's notes
- [x] General settings admin screen (`/panel-abraham/settings/general`) wired to the `settings` table: site name, tagline, contact notification email, default language — the four example fields the plan named in §7. Site name/tagline now drive the public `<title>`/meta description/header/footer via `Setting::siteName()`/`siteTagline()` instead of the hardcoded `config('app.name')`
- [x] `news` migration/model (translatable `title`/`body` with Jodit on the body, `image1`, `image2` — exactly two, matching claude.md's literal wording and the old site's schema — `published_at` date, `active`) + `Admin\NewsController` CRUD, all shared components reused again
- [x] Public `/news` listing (empty state when none): headline, publish date, both images side by side when present, full body — "News" link added to the base theme header
- [x] **Resolved a real schema collision, not a design choice:** the legacy `news` table (old raw-PHP site) already existed in the shared `abrahammontes` database with an incompatible structure, so the new migration failed outright (`Table 'news' already exists`) rather than silently conflicting. Its content is Lorem-ipsum placeholder text like the old `blogentries` table was in Phase 3, so — consistent with that earlier decision — it wasn't ported. Renamed it to `news_legacy` (preserved, not dropped) rather than deleting it, then re-ran the migration cleanly.
- [x] **Pause point:** client proceeded to Phase 8

## Phase 8 — Public Theme Engine
- [x] **Landing/multipage confirmed with client before building:** independent site-wide setting (recommended option), not a property baked into each theme — resolves plan §9.2. (Actual landing-mode assembly is still Phase 12's job; this phase only needed the architecture not to conflict with that later.)
- [x] **Architecture decision, not duplication:** kept content section views (home/blog/research/publications/quotes/news) shared across all 5 themes rather than duplicating each one 5x — only the *layout* (header/footer/typography/shape language) differs per theme. `ThemeManager::layout()` resolves `themes.{active}.layouts.app`; a `View::composer` on `themes.base.*` injects `$activeLayout` automatically so every content view just does `@extends($activeLayout)`. This is what makes "full pass through all public sections" per theme automatic rather than 40 template files to maintain.
- [x] Extracted the old `themes/base/app.scss` into a shared `_reset.scss` partial (tokens, reset, `.container`, `.empty-state`, `.pagination`, etc.) plus the existing component partials (blog/carousel/research/publications/news/share-buttons) — every theme's stylesheet `@use`s these, so component structure is centralized and only re-themed via CSS custom properties (colors, fonts, `--radius-card` for shape language), not rewritten
- [x] 5 themes built, each with its own layout + stylesheet: **Aurora** (default — burnt orange/white, matches claude.md's default color spec), **Slate** (dark charcoal-blue, mono headings, blue accent — technical), **Ivory** (cream, serif, deep green — editorial/academic), **Nova** (white, bold sans, magenta, pill shapes — creative), **Graphite** (near-black, full monospace, neon lime — terminal-styled, dark mode)
- [x] Admin theme picker (`/panel-abraham/themes`): grid of 5 cards with color swatches and descriptions, "Preview" opens a modal with a live iframe (a real auth-protected preview route renders the actual homepage under that theme without touching the active setting), "Activate" writes `settings.active_theme` and takes effect immediately — no rebuild/cache to clear
- [x] Default `active_theme` migrated from the Phase 0 placeholder value (`base`) to `aurora`, both in the seeder and the already-seeded row in the dev DB; `ThemeManager::active()` also falls back safely to `aurora` if it ever sees an unrecognized value
- [x] **Pause point:** client proceeded to Phases 9–11 (validated Phase 4–8 features along the way — real quotes/publications/news content and images already present in the DB when this phase's verification ran)

## Phase 9 — Image & Icon Management
- [x] `theme_image_slots` migration/model (`theme_slug`+`slot_key` unique) — 2 slots defined (`hero_background`, `about_photo`), seeded for all 5 themes with real existing project images (`build/img/header.webp`, `build/img/photo.webp` — same photo the old site used in its about-me header) as the "test images" per spec, copied into `storage/app/public/theme-slots/`
- [x] Admin "Images" screen (`/panel-abraham/images`) scoped to the currently active theme's slots — per-slot upload or "reset to default", reusing the existing upload pipeline (resize + webp)
- [x] **Upgraded the Phase 0 homepage placeholder into a real hero section** so the slots have something to visibly control right now rather than sitting unused until Phase 12: background image, profile photo, site name, tagline — kept deliberately minimal (not the full About Me page, which is still Phase 12's job)
- [ ] **Pause point — awaiting client validation:** visit `/panel-abraham/images`, replace the hero background and/or about photo, confirm the homepage reflects it immediately; try "reset to default"

## Phase 10 — Dashboard Appearance + Section Toggles
- [x] Dashboard light/dark + 8-swatch accent picker (`/panel-abraham/appearance/colors`): radio toggle for mode, a modal with 8 preset swatches (blue is the default, matching the existing dashboard accent) — persisted on the `admins` row (columns already existed from Phase 1), survives logout, applied via a `data-color-mode` attribute + one inline `<style>` block for the arbitrary accent hex
- [x] `sections` enable/disable + reorder screen (`/panel-abraham/settings/sections`) — the 7 sections seeded back in Phase 0
- [x] **Made this genuinely functional, not just a settings screen with no effect:** the shared `nav-links` partial (used by all 5 themes) now reads live from the `sections` table instead of 5 hardcoded links — disabling a section removes it from navigation on every theme at once. Sections without a public page yet (About Me, Contact Me — both Phase 12) are simply skipped, not errored
- [ ] **Pause point — awaiting client validation:** toggle dark/light and each of the 8 accent colors, log out and back in and confirm it stuck; disable a section (e.g. Blog) and confirm its nav link disappears on the public site, then re-enable it

## Phase 11 — Phrases, Social, Notifications, Profile
- [x] `public_phrases` migration/model/CRUD (`/panel-abraham/settings/phrases`) — seeded with the 10 empty-state title/message pairs actually used across blog/research/publications/news/quotes, so editing one immediately changes live public copy (not an inert CRUD screen with nothing wired to it)
- [x] `social_links` migration/model/CRUD (`/panel-abraham/settings/social`) — seeded with the 4 real profile links already on the old site (Google Scholar, LinkedIn, ResearchGate, GitHub), rendered in a new shared footer partial reused by all 5 themes
- [x] Email & Notifications screen (`/panel-abraham/settings/notifications`) — moved `contact_notify_email` here from General Settings (its more correct home now that this screen exists) and added the on/off toggle the plan calls for
- [x] Real profile edit page (`/panel-abraham/profile`, reached via the header avatar/name dropdown built in Phase 2): name, email, phone, avatar upload, optional password change (requires current password) — this is where the `admins` row from Phase 1 finally gets a UI
- [x] "View Public Site" button added to the bottom of the sidebar, opens the live site in a new tab
- [ ] **Pause point — awaiting client validation:** edit a phrase and confirm the public page changes; add/edit a social link and confirm it shows in the footer; toggle email notifications; update your profile (including a password change) and confirm you can log in with the new password

## Phase 12 — Public Site Assembly + About Me
- [x] `about_content` migration/model (translatable `headline`/`bio` with Jodit on the bio, singleton row) + `Admin\AboutController` edit screen (`/panel-abraham/settings/about`) — the photo shown alongside it reuses the `about_photo` slot already managed in Phase 9's Images screen, not a separate upload
- [x] Public `/about-me` page — empty state if the admin hasn't filled it in yet, JSON-LD `Person` schema once it has
- [x] Contact form built out fully (not just stubbed for Phase 13's reCAPTCHA to land on later): `contact_messages` migration/model, `Public\ContactController@show/@store` at `/contact-me`, honeypot field + `throttle:5,1` per plan §3.8's "protected from Phase 1 onward" baseline, confirmation message shown in-page on success, optional email notification to the address configured in Phase 11's Notifications screen (skipped silently, not erroring, if that's left blank)
- [x] News detail pages: added a unique `slug` to `news` (existing row backfilled automatically by the migration), `/news/{slug}` route + full-story view; the `/news` index and every other place news appears now shows headline + date + one picture + a short excerpt with "Read more", per claude.md's explicit Phase 12 wording — this changes the multipage news index too, not just the landing-mode teaser, since showing the full body inline everywhere contradicted the same instruction
- [x] **`site_mode` (landing/multipage) implemented end-to-end, reusing partials, no content duplication:** extracted each section's rendering (blog/research/publications/news/about/contact) into `themes/base/partials/sections/*.blade.php`, included both by the standalone route views (unchanged URLs, each keeps its own `<h1>`) and by a new `landing.blade.php` that assembles every *enabled* section in its configured order on `/` with anchor ids, each wrapped in its own `<h2>`. Quotes carousel is included full-bleed (no wrapper) to preserve its "occupies the entire page" Phase 4 design. Individual detail pages (a blog post, a news item) always keep their own URL in both modes — only the section *index* moves onto the homepage in landing mode
- [x] Nav links are mode-aware: multipage mode links to each section's dedicated URL (unchanged); landing mode links to `/#section-key` and a small vanilla-JS module (`resources/js/themes/base/landing.js`) intercepts the click for a smooth scroll and highlights the active link via `IntersectionObserver` as the visitor scrolls — both progressively enhance plain anchor links, so navigation still works with JS disabled
- [x] Replaced the Phase 9 homepage placeholder ("more of the site is on its way") with real content for multipage mode: a quick-links grid to every enabled section
- [x] Added `site_mode` to General Settings as a radio choice; defaults to multipage (existing behavior) until the client opts into landing
- [x] SEO pass: shared Open Graph/Twitter/canonical meta partial included in all 5 theme layouts, JSON-LD `Person` on About and `Article` on each blog post, dynamic `/sitemap.xml` (every static page + every published post/news item) and `/robots.txt` (replacing the static file so it can reference the sitemap with the correct environment URL)
- [ ] **Pause point — awaiting client validation:** fill in About Me content and confirm the public page and JSON-LD show it; submit the contact form and confirm the on-page confirmation message and (if a notification email is configured) that it arrives; open a news item's "Read more" link; switch `site_mode` to landing in General Settings, confirm the homepage assembles all enabled sections with working anchor nav and smooth scroll, then switch back to multipage and confirm the dedicated section pages are unaffected

## Phase 13 — Language Selector + reCAPTCHA
- [x] **reCAPTCHA styling ceiling confirmed with client before building (plan §9.3):** light/dark matching (recommended option) — resolves the last plan-flagged open question
- [x] `SetLocale` middleware (also sets Carbon's locale for date formatting) wraps every public route in `web.php`; `GET /lang/{locale}` stores the choice in session and redirects back to the page the visitor was on. Falls back to General Settings' `default_locale` for first-time visitors, same as before this phase — nothing changes for a visitor who never touches the switcher
- [x] EN/ES switcher added to all 5 theme headers (`themes/base/partials/language-switcher.blade.php`), current language marked with `aria-current`
- [x] **Every public string actually flips, not just DB content:** DB-backed content (blog/research/publications/news/quotes/about/public phrases) already flipped automatically once the locale was actually being set, via `spatie/laravel-translatable`'s existing per-locale columns from Phases 3–11 — no code changes needed there. What Phase 13 added: `lang/en` and `lang/es` files (`nav.php` for section names — reused for every page's `<h1>` and landing `<h2>`, driven by section `key` rather than the admin-editable `label` field, and `site.php` for everything else: buttons, form labels, "Read more"/"Download PDF"/publication type badges, carousel aria-labels, share-button aria-labels, contact form validation and success messages) plus `pagination.php`/`es.json` for Laravel's own pagination widget (previously untranslated — a small pre-existing gap this phase happened to also close). Every hardcoded English string found across the public theme views was located via a full grep pass and routed through `__()`. Dates switched from `format()` to `translatedFormat()` so month names localize via Carbon's built-in Spanish data (e.g. "July 1, 2026" → "julio 1, 2026")
- [x] Admin panel itself intentionally stays English-only, per the plan's explicit "admin panel stays in the agent's working language" call-out — only the public site got a switcher
- [x] `RecaptchaVerifier` service (`Http::asForm()->post()` to Google's `siteverify` endpoint, no extra package, per plan §2) + a `Recaptcha` validation rule wired into `ContactMessageRequest`. Site/secret keys are admin-configurable (`/panel-abraham/settings/recaptcha`), not `.env` values, so they can be turned on/off from the dashboard without a deploy — consistent with how every other optional integration in this project works
- [x] **Graceful by design, not a bug:** if no keys are configured yet, the reCAPTCHA check is skipped entirely (verified true) rather than blocking the form — the honeypot + rate limit from Phase 12 already protect it, and a freshly-migrated site shouldn't have a broken contact form just because Google keys haven't been pasted in yet. Once keys are present, the widget renders (light/dark chosen automatically from the active theme's character via a new `ThemeManager::isDark()`) and a missing/invalid token is rejected server-side
- [x] reCAPTCHA script loads with `?hl={locale}` so the widget itself speaks the visitor's chosen language, on top of everything else — a small extra mile past the two literal requirements
- [ ] **Pause point — awaiting client validation:** switch languages and confirm nav, headings, buttons, dates, and an empty state all flip; get real reCAPTCHA v2 keys from the [Google admin console](https://www.google.com/recaptcha/admin), paste them into `/panel-abraham/settings/recaptcha`, confirm the checkbox appears on the contact form in the correct light/dark style and that submitting without completing it is rejected; confirm a real submission (with the checkbox completed) still reaches your configured notification email

---

## Design Revision 1 — client feedback in `laravel-app/fixes.md`
Post-Phase-13 design pass. All 9 items in "Fixes, part 1" implemented; nothing from Phases 1–13 was removed or disabled.

- [x] **Carousel height** — full page width kept, height changed from a hard `100vh` to `clamp(38rem, 58vh, 54rem)` (a taller clamp on mobile where the layout stacks), so it reads as a band instead of eating the whole viewport
- [x] **About Me subsections** — new `about_entries` table/model + full admin CRUD, managed from the existing About Me screen (`/panel-abraham/settings/about`) rather than a separate menu item, so all About content stays in one place. Three subsections (Education, Professional Experience, My Research Interests), each entry = an icon + title + optional detail line, EN/ES translatable, orderable. Icons picked from a **visual picker of 32 curated FontAwesome icons** (academic/engineering/AI themed) — no typing class names. Public side renders them as circular accent-tinted icon bullets in a responsive card grid, with a hover lift. **Seeded with the real content from the current site** (3 degrees, 3 experience items, 6 research interests) so it ships populated, not empty
- [x] **Contact form redesign** — centred card panel (max-width, auto margins) with an intro line, icons inside each field, focus rings, a two-column name/email row that stacks on mobile, a centred submit button with a send icon, and the social links repeated under a divider. Honeypot, rate limit, reCAPTCHA and the success message all still work unchanged
- [x] **Header/footer no longer white** — new `--color-nav-bg` / `--color-nav-text` / `--color-footer-bg` / `--color-footer-text` tokens, set to a dark bar in every theme (each theme picks its own tone, so they stay distinct). Footer extracted to a full-bleed band with an inner container
- [x] **Fonts** — replaced the system-font stacks with **self-hosted** Inter (body), Space Grotesk (headings), Lora (Ivory's serif headings) and JetBrains Mono (Slate/Graphite). Downloaded as latin + latin-ext woff2 only (~888 KB total) and vendored under `public/themes/base/assets/fonts/webfonts/`, matching the existing no-CDN-at-runtime approach already used for FontAwesome and Jodit. Headings now use a distinct family from body text and a new `--color-heading` token — **set to burnt orange in Aurora** (the client asked to evaluate this; it fits the default palette). Themes where an orange-equivalent heading would fight the palette (Slate, Nova, Graphite in light mode) use a high-contrast neutral instead, keeping the accent for rules and hovers
- [x] **Contact button + social links in the header** — CTA pill linking to the contact page (or the `#contact` anchor in landing mode, automatically), and the social icons from Phase 11's Social Networks screen. The CTA hides itself if the Contact section is disabled in Sections
- [x] **Menu redesign** — sticky at the top of the viewport on scroll with a shadow that fades in once scrolled, animated underline that wipes in on hover, and a visitor-facing light/dark toggle
- [x] **Visitor light/dark mode** — a toggle in the header, persisted in `localStorage` and applied before first paint by a small inline script so it never flashes on reload. Each of the 5 themes now ships a full counterpart palette (dark variants for the 3 light themes, light variants for the 2 dark ones), so the toggle works on every theme rather than only inverting one. Distinct from the Phase 10 dashboard dark mode, which remains admin-only
- [x] **Animated mobile hamburger** — three bars that rotate into an X (matching the current site's animation), collapsible panel with a height/opacity transition, `aria-expanded` kept in sync, and tapping any link closes it. All 5 themes share this one header markup, so it behaves identically everywhere
- [x] **Consolidated 5 duplicated theme layouts** into a shared `<head>` partial + shared header partial — the per-theme brand flourishes (Slate's chip icon, Graphite's `$` prompt) are passed in as variables. Reduces the risk of the themes drifting apart, and every future header change now lands in one file
- [ ] **Pause point — awaiting client validation:** check the new fonts/heading colour on each of the 5 themes; toggle light/dark as a visitor and confirm it sticks after a reload; resize to mobile and confirm the hamburger animates and the menu collapses; scroll and confirm the menu sticks; visit About Me and confirm the three new subsections look right, then add/edit/reorder an entry and try the icon picker from `/panel-abraham/settings/about`; confirm the contact form looks right and still sends

**One interpretation flagged, not blocking:** "the menu should stick to the bottom when the visitor scrolls down" was read as a standard sticky header — it stays pinned to the top of the viewport as you scroll down, rather than docking to the bottom edge of the screen. If you meant a bottom-docked bar, that's a small change to make.

---

## Design Revision 2 — client feedback in `laravel-app/fixes.md` ("Fixes, part 2")
All 3 items implemented; nothing from Phases 1–13 or Revision 1 was removed or disabled.

- [x] **Quotes carousel not auto-advancing — root cause found and fixed.** The advance/wrap logic was correct all along; the bug was that pause-on-hover was bound to the whole full-bleed band. Because the carousel spans the full page width, the pointer is resting over it almost all the time, so autoplay was being paused indefinitely and never visibly ran — which also meant the loop back to the first quote was never seen. Pause is now scoped to a `[data-carousel-controls]` wrapper around just the arrows and dots, so hovering the band no longer stops it while clicking through still does. Also added a `visibilitychange` handler so it restarts instead of coming back frozen after the tab is backgrounded (browsers throttle timers in hidden tabs)
- [x] **Looping confirmed working** — advancing past the last quote returns to the first (and prev from the first wraps to the last), via modulo arithmetic in `goTo()`
- [x] **Carousel title** — "Quotes That I Like" / "Citas que me Gustan", on an accent title strip above the slides with an underline rule. Shown on both the standalone `/quotes-i-like` page (as the page `<h1>`, which that page previously lacked entirely — an SEO improvement) and in landing mode (as an `<h2>` under the hero's `<h1>`), via a `$titleTag` parameter. Editable from Public Phrases (`quotes.title`) as well as translated
- [x] **Header background carousel** — new `hero_slides` table/model, with the 7 images from `laravel-app/carrousel-header/` imported through the existing image pipeline (resize to 2000px max + WebP sibling): **29 MB of source photos became 4.9 MB**, and the public page serves the WebP variants (the 27 MB PNG ends up a 387 KB WebP). Slides cross-fade automatically every 5.5s with a slow scale drift, **with no visitor-facing controls**, as asked. Respects `prefers-reduced-motion` for the drift
- [x] **No regression to Phase 9** — when no header slides are active, the hero falls back to the single `hero_background` theme image slot exactly as before. Verified both directions
- [x] **Header slides are panel-manageable** — new Appearance → Header Carousel screen (add/edit/reorder/activate/delete, reusing the existing image upload component), rather than hardcoding the folder contents. Consistent with Phase 9's "images that appear in the public part are managed from the dashboard"
- [x] Seeded the previously-unseeded phrase keys (`quotes.title`, `contact.intro`, `empty_state.about.*`) so they're discoverable and editable in Public Phrases instead of only existing as code fallbacks
- [ ] **Pause point — awaiting client validation:** open the homepage and confirm the header background cycles through the 7 photos on its own with no buttons; leave the quotes carousel alone (mouse anywhere) and confirm it advances every ~6s and loops from the last quote back to the first; confirm the "Quotes I Like" title appears; try Appearance → Header Carousel to add/disable/reorder a slide

---

## Design Revision 3 — client feedback in `laravel-app/fixes.md` ("Fixes, part 3")
All 3 items implemented; nothing from Phases 1–13 or Revisions 1–2 was removed or disabled.

- [x] **Hero name invisible (orange on orange) — fixed, and the same latent bug fixed across all themes.** Revision 1 introduced a global `--color-heading` for headings; the hero `<h1>` inherited it and so rendered burnt orange on the hero's burnt-orange overlay. Measured contrast of the reported bug: **1.50:1** (essentially invisible). The hero title and tagline now use a dedicated `--color-hero-text` token instead
- [x] **Same bug existed unreported in 3 other themes** — Ivory (dark green on dark green), Nova (dark purple on magenta) and Graphite (lime on lime) had the identical problem; all are fixed by the same change, so this wasn't patched for Aurora alone
- [x] **Avoided introducing a regression in Slate:** naively reusing `--color-accent-text` would have made Slate's hero title *near-black* (3.6:1) where it had previously been readable white. `--color-hero-text` defaults to the on-accent colour but Slate overrides it to white, because the hero overlay is darker than the raw accent
- [x] **Contrast verified numerically, not by eye** — a script computes the actual WCAG ratio of the hero title against both ends of the overlay gradient (replicating the `color-mix` maths) for all 5 themes in **both** colour schemes: 10/10 pass, worst case **4.05:1** against a 3:1 AA-large threshold, most well above 4.5:1
- [x] Added a soft text-shadow so the title stays legible over the brighter header photos, independent of theme
- [x] **"Quotes That I Like" → "Quotes I Like"** — updated in the English translation file, the seeder, *and* the already-seeded `quotes.title` row in the database (the DB value overrides the translation, so changing the lang file alone would have had no visible effect). Spanish "Citas que me Gustan" already read correctly. Confirmed no occurrence of the old string remains anywhere in source
- [x] **Headings centred site-wide** — `h1`–`h6` now centre by default, matching the current site's own convention. Two deliberate exceptions kept left-aligned: titles inside cards (blog/news/publication/research), whose surrounding text is left-aligned, and headings inside long-form article bodies (blog post, news item, research detail, About bio), where centred subheadings break the reading line. The About subsection underline rule was re-centred to match its heading
- [x] Admin panel unaffected — the dashboard has its own stylesheet and never imports the public theme reset, verified
- [ ] **Pause point — awaiting client validation:** confirm your name is clearly readable over the header photos (try a few themes and the light/dark toggle); confirm the carousel now reads "Quotes I Like"; confirm centred titles look right, and tell me if you also want card titles and in-article subheadings centred — I deliberately left those two left-aligned for readability

---

## Design Revision 4 — client feedback in `laravel-app/fixes.md` ("Fixes, part 4")
Both items implemented; nothing from Phases 1–13 or Revisions 1–3 was removed or disabled.

- [x] **50 MB PDF uploads — the real blocker was PHP, not Laravel.** The app's own rule was 20 MB, but the reported failure at 16 MB came from **`post_max_size = 8M`** in `C:\Program Files\Ampps\php82\php.ini`: PHP rejects an oversized POST body before Laravel ever sees it, so raising only the validation rule would have changed nothing. Fixed at both levels
- [x] `PublicationRequest` PDF rule raised 20 MB → **50 MB** (`max:51200`)
- [x] `php.ini`: `post_max_size` **8M → 64M** (headroom above the 50 MB file for the rest of the form) and `max_input_time` **60 → 300** so a large upload on a slow connection isn't cut off mid-transfer. `upload_max_filesize` was already 128M and needed no change. A backup was written to `php.ini.bak-preupload`
- [x] **⚠️ Requires a web-server restart to take effect.** Verified working under `php artisan serve`; if the site is served through AMPPS/Apache, restart it so Apache picks up the new `php.ini`
- [x] **Verified with a real 40 MB upload through the actual admin form**, not just by reading config: logged in over HTTP, posted a valid 40 MB PDF to `/panel-abraham/publications`, and confirmed the record saved with the file stored at full size on disk
- [x] **Both rejection paths now fail gracefully instead of showing a raw error page.** A file over 50 MB gets a normal field error, reworded from Laravel's default "must not be greater than 51200 kilobytes" to **"The PDF must not be larger than 50 MB."**. A file over the server's own 64 MB ceiling is caught by a new `PostTooLargeException` handler — that exception is thrown by global middleware that runs *before* the session starts, so a flashed error would silently vanish; it's passed back as a query flag and rendered as a dashboard banner naming the actual limit. Both paths tested with 55 MB and 70 MB files
- [x] **Header carousel jump fixed.** The zoom was a keyframe `animation` bound to `.is-active`; removing that class tears the animation off in a single frame, snapping `transform` back to `scale(1)` while the outgoing image was still visible mid-fade — exactly the abrupt end-of-transition jump reported. Rebuilt as a `transition` instead, which reverses smoothly rather than snapping. The transform duration (10s) is deliberately much longer than a slide's time on screen (5.5s) so the zoom never reaches its end and visibly stops, and `linear` easing avoids any deceleration; the outgoing image therefore barely moves during its fade. Fade lengthened slightly (1.4s → 1.6s) and `prefers-reduced-motion` support kept
- [ ] **Pause point — awaiting client validation:** restart AMPPS/Apache, then upload a large paper PDF (up to 50 MB) and confirm it saves; watch the header carousel through a couple of full cycles and confirm the transition is smooth end to end

---

## Design Revision 5 — client feedback in `laravel-app/fixes.md` ("Fixes, part 5")
Both items implemented; nothing from Phases 1–13 or Revisions 1–4 was removed or disabled.

### Awards & Distinctions (new section)
- [x] Title chosen: **"Awards & Distinctions"** / "Premios y Distinciones" — covers prizes, honours and named distinctions, which a narrower word like "Awards" alone would not
- [x] `awards` table + `Award` model: translatable name and short description (EN/ES), granting institution, date, optional image, display order, active toggle. `granted_by` is deliberately **not** translatable — institution names are proper nouns
- [x] Full admin CRUD at `/panel-abraham/awards` (Content → Awards), built on the same components as every other module — EN/ES tabs, image upload, empty state, delete confirmation, pagination
- [x] Public page at `/awards`, plus a section partial reused by landing mode, exactly like every other section. Cards show the name, granting institution, date and description, with the image when present and an accent trophy badge when not, so a row without a picture doesn't look broken
- [x] **Fully wired into every system that already exists for sections**, not bolted on: the `sections` table (so it can be enabled/disabled and reordered), public navigation, landing-mode assembly, the homepage quick-links grid, the sitemap, EN/ES translations, and a panel-editable empty-state phrase
- [x] Section placement handled carefully: inserted with the same `display_order` as Publications so it slots in directly **after** it, rather than renumbering rows the engineer may already have reordered by hand. Still freely reorderable from the Sections screen
- [x] Dates render localised (e.g. "March 2025" / "marzo 2025")

### Code blocks in blog posts
- [x] Editor: added a **Code block** toolbar button to the WYSIWYG and a "Code block" entry in its paragraph/format dropdown, so code can be inserted fresh or an existing paragraph converted
- [x] Sanitiser: `pre`/`code` now permit a `class`, so a language hint like `language-python` survives. Verified end to end that indentation, newlines, the language class and inline `<code>` all survive purification unchanged
- [x] Styling: code blocks are clearly distinguished from prose — monospace, tinted panel, accent rule down the left edge, horizontal scroll for long lines, and separate pill styling for inline `code`. The panel colour is derived from each theme's own palette, so it works across all 5 themes and in both light/dark without per-theme overrides
- [x] Added a hover **copy-to-clipboard** button on code blocks, built as progressive enhancement — it isn't rendered at all when the Clipboard API is unavailable
- [x] Applies everywhere rich text is rendered (blog posts, news items, research detail, About bio), not just blog

### Bug found and fixed along the way (not reported, would have hit the client)
- [x] Changing the sanitiser's allowed-tags list surfaced a latent failure: HTMLPurifier caches its generated HTML definition to disk and refuses to write when `is_writable()` returns false. On this project's OneDrive-synced `storage/` that check reports false **even though writing actually succeeds**, so saving any blog post threw a 500. It only appeared now because the previous definition had been cached back in Phase 3 and never needed rewriting — any future change to the allowed-tags list would have hit it too. Fixed by disabling the definition cache, which is free here: purification runs only when the engineer saves content in the dashboard, never on public page render
- [ ] **Pause point — awaiting client validation:** add a couple of real awards from `/panel-abraham/awards` and confirm they look right on `/awards` (and in the nav/landing page); write a blog post using the new **Code block** button and confirm the snippet is clearly distinguished from the surrounding text and that the copy button works

---

## Design Revision 6 — client feedback in `laravel-app/fixes.md` ("Fixes, part 6")
Both items implemented; nothing from Phases 1–13 or Revisions 1–5 was removed or disabled.

- [x] **Social links moved out of the menu and into the header hero**, below the name and tagline, as larger circular icon buttons that lift and invert on hover. Removed from the nav bar entirely (including its mobile collapsed panel) and the now-dead `.site-header__social` CSS deleted rather than left orphaned
- [x] Footer social links left untouched — they're a separate, deliberate placement and still the only social links on interior pages, since the hero only renders on the homepage
- [x] Header nav is now cleaner: brand + links + dark/light toggle + language switcher + Contact button
- [x] **"Read more" button on blog cards** — outlined pill in the theme's accent colour, filling in on hover with a shadow and an arrow that slides right. Uses the existing translated string, so it reads "Leer más" in Spanish
- [x] Button **shape follows each theme's own shape language** (`--radius-card`) rather than forcing one style everywhere: fully rounded in Nova, sharp-edged in Graphite, softly rounded in Aurora/Ivory/Slate. Nova's existing pill override was extended to cover it
- [x] Buttons **line up across cards** whose excerpts differ in length (`margin-top: auto` on a flexed card body), so a grid of posts doesn't look ragged
- [x] Blog cards also gained the same subtle hover lift already used by the awards cards, for consistency
- [ ] **Pause point — awaiting client validation:** check the homepage header shows your social icons under the name/tagline and that the menu no longer does; check `/blog` shows the new "Read more" button and that it lines up neatly once you have several posts of differing lengths

---

## Design Revision 7 — client feedback in `laravel-app/fixes.md` ("Fixes, part 7")
Implemented; nothing from Phases 1–13 or Revisions 1–6 was removed or disabled.

- [x] **Menu links centred on large screens.** The nav was pushed to the right edge alongside the header actions (`margin-left: auto`); it now sits centred between the brand and the actions. Scoped to a `min-width` query so the collapsed mobile menu keeps its left-aligned full-width rows, which the client explicitly said was correct there
- [x] **Found and fixed a real overflow the centring would have made obvious.** With 8 section links — one of them long ("Awards & Distinctions", added in Revision 5) — the horizontal bar needs roughly **1350px** to lay out, against a **1320px** container. It was overflowing even on a wide desktop, and badly between 900px and ~1250px where the desktop layout was still active but far too narrow. This was pre-existing since Revision 5, not caused by this change, but it sits squarely in what the client asked me to look at
- [x] Fixed in two parts: **tightened the desktop nav** (slightly smaller font, link padding and gaps) which brings the requirement down to ~1257px with ~60px of headroom; and **raised the hamburger breakpoint from 900px → 1279px**, so screens too narrow for a horizontal bar get the already-built collapsible menu instead of a spilling one
- [x] Added `flex-wrap` on the desktop nav as a safety net — if a theme's font renders wider than measured, links wrap onto a second centred row rather than overflowing
- [x] **Ivory needed its own adjustment:** its uppercase, letter-spaced nav runs materially wider than the other themes and would still have overflowed. Dialled its nav letter-spacing and size back slightly (small caps still suit the editorial look) so all 8 links fit on one centred row
- [ ] **Pause point — awaiting client validation:** check the menu is centred on your normal screen; narrow the browser and confirm it switches cleanly to the hamburger (now at ~1280px rather than 900px) instead of the links crowding or spilling; confirm the mobile menu still reads left-aligned as before

---

## Design Revision 8 — client feedback in `laravel-app/fixes.md` ("Fixes, part 8")
Implemented; nothing from Phases 1–13 or Revisions 1–7 was removed or disabled.

- [x] **Header carousel now fills the full page height**, in both multipage and landing mode (it's the same shared partial, so both got it from one change). Sized as `100vh` *minus the sticky header* rather than a flat `100vh` — the header sits in normal flow above the hero, so a plain `100vh` would have pushed the bottom of the hero (and the new scroll cue) below the fold
- [x] Also emits a `100svh` variant, so mobile browsers don't jump as the URL bar hides/shows; `vh` remains the fallback for older engines
- [x] Removed the now-redundant 640px height override, which duplicated the base rule exactly after the change
- [x] **Animated scroll cue added** below the name, tagline and social icons: a circular button with a bouncing chevron and an expanding halo ring, pinned to the bottom of the hero
- [x] Built as a **real anchor** (`#site-content`) rather than a JS handler, so it works with JavaScript disabled and picks up the site's existing global smooth-scroll behaviour natively. `scroll-margin-top` keeps the sticky header from covering the landing spot
- [x] The `#site-content` target was added in **both** views — the content container in multipage mode, and immediately before the first enabled section in landing mode — so the cue lands correctly in either
- [x] Colours use the existing `--color-hero-text` token, so contrast stays correct on every theme and in both light/dark schemes (the token added in Revision 3 for exactly this reason)
- [x] Animations wrapped in `prefers-reduced-motion: no-preference`; label translated (EN/ES) and exposed via `aria-label`
- [ ] **Pause point — awaiting client validation:** confirm the header fills the screen on your monitor and on a phone; confirm the arrow animates and clicking it scrolls smoothly to the content below, in both multipage and landing mode

### Design Revision 9 (fixes.md — "Fixes, part 9")
- [x] **Header overlay opacity is now editable from the admin panel**, at Appearance → Header Carousel. A range slider (0–100%) with a live numeric readout and a live sample tile that stacks the image and the accent wash exactly as the real hero does, so the value is chosen visually instead of saved-and-checked repeatedly
- [x] Stored as a `Setting` (`hero_overlay_alpha`), applied to the public hero through a `--hero-overlay-alpha` custom property, with the previous `0.86` kept as the CSS fallback — so the site renders identically to before if the setting is ever missing
- [x] Value is clamped to 0–1 when read and validated as an integer 0–100 on save, so neither a bad form post nor a hand-edited database row can produce broken CSS
- [x] The control sits above the slide grid and shows even when there are **no** slides, since the overlay also tints the single fallback hero image
- [x] Route registered on its own path (`/panel-abraham/hero-overlay`) rather than under `/hero-slides/`, so it cannot be shadowed by the resource's `PUT /hero-slides/{slide}` — verified against the route list
- [x] **Hero photo enlarged** 12rem → 15.5rem, with a slightly thicker ring
- [x] **Social buttons enlarged** 4.2rem → 5.4rem with a larger glyph and wider spacing
- [x] **Scroll arrow enlarged** 5rem → 6.4rem with a larger chevron, staying centred and keeping its bounce and halo animations
- [x] All three step down proportionally on phones, so the larger sizes don't crowd small screens
- [ ] **Pause point — awaiting client validation:** confirm the opacity slider gives you the look you want (try a low value to reveal more of the photo), and that the enlarged photo, social buttons and arrow feel right on both desktop and phone

---

## Redesign — November 2026 (prompt of 2026-11-15)
Implemented; nothing from Phases 1–13 or Revisions 1–9 was removed or disabled. Database backed up first to `laravel-app/storage/app/backups/abrahammontes-before-redesign.sql`.

**1. Spanish translations**
- [x] 75 English-only fields translated and saved as Spanish: 12 About entries, 4 awards (title + description), 10 news items (title + full body, HTML structure preserved), 8 publications (title + abstract), 16 public phrases. Terminology matches the existing Spanish entries ("pega de tubería", "recortes", "derrumbes", "limpieza del hueco")
- [x] Every translatable record now has Spanish (verified by script: 0 missing)
- [x] Tagline is now bilingual (new "Tagline (Español)" field in General settings, pre-filled)
- [x] Page titles were hard-coded English — now translated; "citation(s)" also translated

**2. Redesign (all 5 themes, same sections/components)**
- [x] Full-screen hero: parallax photo carousel, animated gradient mesh, engineering grid, cursor spotlight, split-letter name reveal, typed roles, CTA buttons, orbiting portrait with conic ring, mouse-wheel scroll cue
- [x] Animated statistics strip (publications, citations, awards, news — computed from content) and research-interests marquee ribbon
- [x] Section headings with index numbers, letter reveal, editable lead line and a giant outlined title drifting sideways on scroll
- [x] News bento grid with featured lead story; blog/news/award cards with 3D tilt + glare and hover lift; research zig-zag with in-frame image parallax; publications with Journal/Conference filter chips; About timeline cards; quotes carousel with Ken Burns, blur-in text and autoplay progress pills; two-column contact with animated gradient border; footer call-to-action
- [x] Global: glass header (transparent over hero, hides on scroll down), scroll-progress bar, back-to-top with progress ring, custom cursor, magnetic buttons, ambient background, once-per-session intro, page transitions (View Transitions)
- [x] Respects `prefers-reduced-motion`; content stays visible if JavaScript fails (CSS safety net)
- [x] Checked in all 5 themes, light + dark, desktop + mobile, EN + ES, landing + multipage; no console errors
- [x] Nav fits on one row at 1280px (CTA collapses to its icon at 1280–1559px; Graphite tightened)

**3. Cloudflare Turnstile**
- [x] reCAPTCHA removed; Turnstile verified server-side; widget follows the visitor's light/dark mode and language, full width
- [x] Tested end-to-end with Cloudflare's official test keys (pass accepted, fail rejected with translated message); test keys removed afterwards
- [x] `contact_messages.recaptcha_verified` renamed to `captcha_verified`; old reCAPTCHA keys deleted (not valid for Turnstile)

**4. Admin panel (only what was needed)**
- [x] Appearance → **Motion & Effects**: animations on/off, custom cursor, intro, stats strip, marquee, parallax intensity
- [x] Settings → **Cloudflare Turnstile** (replaces the reCAPTCHA page; requires both keys or neither)
- [x] General → Spanish tagline
- [x] New editable phrases (EN + ES) in Public Phrases: `hero.greeting`, `hero.roles` (separate with `|`), `footer.cta_title`, `footer.cta_text`, `section_lead.*`

**Fixes found along the way**
- [x] Theme preview modal (Appearance → Themes) had been erroring since the header carousel was added — now renders
- [x] `/about-me` left an output buffer open when no tagline was set — fixed

**Tests:** new `tests/Feature/RedesignTest.php` and `AdminScreensTest.php` (all admin screens + 5 theme previews); stock `ExampleTest` now migrates. 13/13 passing.

- [ ] **Pause point — awaiting client validation:** add your Turnstile keys (Settings → Cloudflare Turnstile); browse the site on desktop and phone; tune Motion & Effects; review the Spanish translations

---

## Confirmations Needed From Client (blocking, see plan §9)
- [x] Citation count source: Crossref-by-DOI substitute for Google Scholar — **confirmed** at Phase 6 start, implemented as described
- [x] Landing/multipage interpretation of Phase 8's theme wording — **confirmed** at Phase 8 start: independent site-wide setting, implemented as described
- [x] reCAPTCHA styling ceiling (light/dark only, not full brand color) — **confirmed** at Phase 13 start; superseded Nov 2026 by Cloudflare Turnstile (same light/dark matching)
- [!] Initial admin login credentials — no longer needs to reach the agent at all: set `ADMIN_NAME`/`ADMIN_EMAIL`/`ADMIN_PHONE`/`ADMIN_PASSWORD` directly in `laravel-app/.env` (gitignored, local-only) and run `php artisan db:seed --class=AdminSeeder`
- [x] "Manage news" phase gap — **resolved**: claude.md was updated to assign it to Phase 7, implemented there
- Old `blogentries` table (3 rows) was **not** auto-ported into the new `blog_posts` table during Phase 3 — its `content` field is Lorem-ipsum placeholder text, not real copy, so porting it would just create fake "published" posts to clean up later. Headlines are real project titles if useful as a writing prompt, but real article bodies need to be written fresh through the new CRUD. Old table left untouched, not blocking.
- Old `news` table (3 rows, same Lorem-ipsum situation) was renamed to `news_legacy` during Phase 7 to resolve a table-name collision with the new schema — preserved, not dropped, not ported, not blocking.
