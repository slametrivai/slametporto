# AGENTS.md

## Project overview
This repository is a Laravel 12 portfolio and CMS for a personal brand. The app mixes a public-facing portfolio site with an authenticated admin dashboard for managing projects, blog posts, categories, inquiries, settings, and SEO.

## Where to look first
- Routing: routes/web.php
- Admin controllers: app/Http/Controllers/Admin/
- Eloquent models: app/Models/
- Shared helpers: app/helpers.php
- Views: resources/views/
- Database schema: database/migrations/
- Frontend assets: resources/css/, resources/js/
- Test coverage: tests/
- Documentation: README.md

## Important conventions
- Prefer the existing Laravel structure over introducing new frameworks or patterns.
- Keep admin CRUD changes aligned across routes, controller, model, and Blade views.
- Use Eloquent models and database migrations for schema changes.
- Respect existing naming patterns: singular resource names in controllers and route names under admin.*.
- For uploaded files, use Laravel storage conventions and keep public assets in storage/app/public or the related public disk.
- For SEO and settings, prefer the existing helper functions and model-level SEO patterns instead of creating ad hoc configuration.
- Keep changes focused and minimal; do not modify generated vendor or public/build output unless explicitly requested.

## Common commands
- Install/update dependencies: composer install and npm install
- Start the app in development mode: composer run dev
- Build frontend assets: npm run build
- Run the test suite: composer test or php artisan test
- Run a single test file: php artisan test tests/Feature/...
- Reset the app locally: php artisan migrate:fresh --seed if the user explicitly requests a reset

## Typical workflow for feature work
1. Inspect the relevant route and controller first.
2. Match the pattern used by the nearest existing resource controller.
3. Update the model and migration only when the domain requires it.
4. Keep Blade templates consistent with the existing Tailwind/admin style.
5. Validate with the smallest relevant test or artisan command.

## Claude-specific notes
- This project is a Laravel app, not a Node-first app. Use PHP/Laravel patterns first.
- The public portfolio and admin area are intentionally separated by route groups; avoid mixing them without checking the current structure.
- When changing forms or submission logic, make sure the validation and redirect flows still match the existing admin pages.
- If a task touches SEO metadata, settings, or portfolio content, check the model and helper usage before inventing a new pattern.

## Useful references
- README.md
- routes/web.php
- app/helpers.php
- app/Models/Project.php
- app/Http/Controllers/Admin/ProjectController.php

Keep instructions brief, practical, and consistent with the project's existing Laravel conventions.
