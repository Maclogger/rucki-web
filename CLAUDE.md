# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Personal website / portfolio of Marek Rucki (rucki.sk) plus a small set of private tools (file gallery, "buffer" upload endpoint, GitHub contribution graph, QR-code quiz pages).

Stack: Laravel 13 (PHP 8.4) + Inertia 3 + Vue 3 (TypeScript, `<script setup>`) + Pinia 4 + Vite 8 + Tailwind 4 with daisyUI 5. MySQL via Laravel Sail. UI strings and many code comments are in **Slovak** — keep that language when touching user-facing text.

## Commands

Docker (Sail) is the intended dev environment; `docker-compose.yml` provides mysql, redis, mailpit, meilisearch, selenium, phpmyadmin (`:8082`).

```bash
./vendor/bin/sail up -d
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev   # Vite dev server on :5173
./vendor/bin/sail artisan migrate --seed                          # seeds Types, Constants, Users
./vendor/bin/sail artisan db:seed --class=QrCodeSeeder            # not part of DatabaseSeeder
./vendor/bin/sail npm run build                                   # vue-tsc (strict) + vite build
./vendor/bin/sail composer test                                   # config:clear + artisan test
./vendor/bin/sail pint                                            # formatting
```

Without Docker: `composer dev` runs `artisan serve`, `queue:listen`, `pail`, and `npm run dev` concurrently.

`npm run build` runs `vue-tsc` first — TypeScript errors fail the build. `tsconfig.json` is `strict`, alias `@/*` → `resources/js/*`.

### Tests

There is **no `tests/` directory** — `phpunit.xml` points at `tests/Unit` and `tests/Feature`, which must be created before `artisan test` does anything (PHPUnit 12). Single test once they exist: `./vendor/bin/sail artisan test --filter=SomeTest`.

## Ignore files: `.gitignore` vs `.ignore`

The repo has two similarly-named ignore files and only one of them is git's:

- **`.gitignore`** — git's. Contains no rule for `public/images/`, which is why all 389 files under it (including the 360 `public/images/lietadla/lietadlo_NNN.png` rotation frames) are tracked. That is intentional: they are source assets, nothing regenerates them, and `Pages/Lietadlo/GameCanvas.vue` fetches them at runtime from `/images/lietadla/`.
- **`.ignore`** — read by ripgrep/fd/editor search, **never by git**. It is a near-copy of `.gitignore` plus `public/build` and `public/images/lietadla/*`, so those paths stay out of search results while remaining ordinary git-tracked or git-ignored paths.

Do not "fix" tracked files under `public/images/` by pointing at the `.ignore` entry — they are unrelated mechanisms. And remember `.gitignore` only suppresses *untracked* paths: adding a rule never untracks what is already committed (`git rm --cached` is required for that).

## Compiled assets and the release path

`public/build/` is **gitignored and untracked** (`.gitignore:27`). Historically it was committed, because the production host had no build step — commit `adbfd7f` added generated assets for exactly that reason — but that is no longer the case in the working tree.

This has a silent consequence for `release.sh`: its step 4 guards on `git status --porcelain=v1 public/build`, which is now always empty for an ignored path, so the script prints "`public/build` je aktuálny" and **commits nothing**. It still refuses dirty working trees, rebases onto `origin/master`, runs the production build, and force-pushes with lease — but compiled assets no longer travel with the push. If the host does not build, they must reach it another way (FTP/rsync or a host-side build step).

## Architecture

### Page routing and layouts

`routes/web.php` mostly uses `Route::inertia('/path', 'Some/Page')` — pages live in `resources/js/Pages/**` and are resolved by name. Everything private sits inside the single `Route::middleware('auth')` group.

`resources/js/app.ts` assigns `AppLayout` to **every** page unless the page component sets its own `layout`. `AppLayout` calls `publicStore.refresh()` and `githubStore.refresh()` on mount, so those stores are populated on any page load. `resources/views/app.blade.php` eagerly `@vite`s the current page component alongside `app.ts`.

### Data flow: Inertia props are minimal, Pinia + axios does the rest

`HandleInertiaRequests::share()` exposes only `auth.user` (id, username). Nearly all data reaches the frontend through plain JSON endpoints consumed by `window.axios` (set up in `resources/js/bootstrap.ts`) inside Pinia stores:

| Store | Endpoint(s) |
| --- | --- |
| `publicStore` | `GET /fetch-public-store` (versions + all constants) |
| `githubStore` | `GET /refresh-github-chart-data`, `GET /fetch-github-chart-data/{year}` |
| `filesStore` | `GET /get-files`, `POST /get-latest-files`, delete endpoints |
| `bufferCodesStore` | `GET /fetch-buffer-codes` + CRUD posts |
| `userStore`, `toastsStore` | client-side only |

When adding data to a page, follow this pattern (controller method returning an array/JSON + a store action) rather than adding Inertia props.

### Errors surface as toasts

`app.ts` registers `router.on('error')` and pushes every entry of `event.detail.errors` into `toastsStore`. That is why controllers return `back()->withErrors(['message' => '...'])` on failure — the message becomes a toast automatically for Inertia form submissions. Axios-driven store actions must display their own toast in a `.catch`.

### Constants table = site content

`constants` is a key/value store (`key` is the primary key, `type_name` FK to `types`, inferred by `Type::getTypeFromValue()`). It holds the site's editable content and tuning values — name, role, phone, mail, location, GitHub nickname and year range, `bufferCodeLength`, `galleryPollingIntervalSeconds`, `galleryPollingPageSize`. Read server-side with `Constant::findByKey($key)`, client-side with `usePublicStore().getConstant(key)`. Prefer adding a constant (and a line in `ConstantSeeder`, which **truncates** the table) over hardcoding content in Vue. Personal info being DB-driven and reactive is deliberate.

### Files and thumbnails

Uploads go to the private `local` disk (`storage/app/private/files`, thumbnails in `.../thumbnails`), never to `public/`. Stored names are `md5(...)` + extension; the original name lives in the DB. Bytes are streamed back only through auth'd controller routes `photos.show` / `photos.thumbnail` (`FilesController::show` / `showThumbnail`), which verify the row belongs to the current user or the buffer account. Thumbnails are generated synchronously with raw GD in `App\Http\Utilities\ImageHelper`, including EXIF-orientation correction for phone photos; `showThumbnail` falls back to the full file when a thumbnail is missing. `FilesController::createFile` deletes the stored file if the DB insert fails, to avoid orphans.

The gallery polls for new uploads with `POST /get-latest-files` using **`after_id`**, not a timestamp — timestamp-based polling used to skip files whose thumbnail generation outlasted the poll interval. Keep the ID-based approach.

### Buffer (unauthenticated upload by code)

`/buffer` and `/buffer/{code}` are public. `BufferController::uploadFiles` validates the code against `buffer_codes` (`enabled`, increments `number_of_usages`), then delegates to `FilesController::uploadFiles`. Since no user is logged in, `FilesController::getAuthor()` falls back to the user named by `BUFFER_CODE_ACCOUNT_USERNAME`. Codes are managed at `/buffer-codes` (auth only).

### Auth

Single-user, **username**-based (no email, no registration). `UserSeeder` creates accounts from `.env`: `WEB_USERNAME`/`WEB_PASSWORD` and `BUFFER_CODE_ACCOUNT_USERNAME`/`BUFFER_CODE_ACCOUNT_PASSWORD`. Login is rate-limited in `LoginRequest`; the contact form uses the `contact` limiter defined in `AppServiceProvider` (10/hour/IP).

### GitHub contribution graph

`GithubRecordController::__invoke` (`POST /fetch-github-contributions`, auth only) pulls from `https://github-contributions-api.jogruber.de/v4/{nickname}` via Guzzle, then in one transaction deletes all `git_hub_records`, reinserts days with count > 0, and updates the `githubLastUpdate` constant. `GithubController` aggregates per-year totals for the frontend. Refresh is manual — triggered from the `/github-secured` page, not scheduled.

## Gotchas

- `env()` is called at runtime in `FilesController` (`BUFFER_CODE_ACCOUNT_USERNAME`). Running `php artisan config:cache` would make those calls return `null` and break uploads/serving.
- `app/Console/Commands/CreateQrCode.php` is an empty placeholder; QR-code rows come from `QrCodeSeeder`. Public quiz pages are served at `/qr/{uuid}`.
- `composer.lock` / `package-lock.json` are **gitignored** (as is `public/build/` — see above), so dependency resolution happens per-machine. `composer.json` therefore pins `config.platform` to PHP 8.4 + `ext-gd`/`ext-exif` (the Docker/production runtime) so a newer local PHP CLI can't resolve packages that break in the container.
- **TypeScript is held at 6.x on purpose**: `vue-tsc` (3.3.9, latest) still needs the JS `lib/tsc` entrypoint that TypeScript 7 — the native Go port — no longer exports. `npm run build` dies with `ERR_PACKAGE_PATH_NOT_EXPORTED` if TS 7 is installed. Revisit when a TS 7-compatible vue-tsc ships.
- Inertia 3 renamed the root-template attribute: `resources/views/app.blade.php` uses `<title data-inertia>`, not `<title inertia>`. Inertia 3 also no longer bundles axios — the explicit `axios` dependency and `resources/js/bootstrap.ts` are what provide `window.axios`.
- `intervention/image-laravel` is installed but unused (thumbnails go through raw GD in `ImageHelper`); it can be dropped if nothing starts using the `Image` facade.
- `_ide_helper.php` and `.phpstorm.meta.php` are generated by `barryvdh/laravel-ide-helper`; don't edit them.
- Slovak pluralization helper: `resources/js/utils/sklonovac.ts` (`vysklonuj(count, singular, 2-4 form, 5+ form)`).
