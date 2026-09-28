# Claude guidance

This repository is a Laravel 12 portfolio application with an authenticated admin area. Follow the conventions in AGENTS.md before making changes.

## Quick rules
- Prefer Laravel conventions over custom abstractions.
- Keep public portfolio routes and admin routes distinct.
- For CRUD work, update the route, controller, model, and view together when needed.
- Validate with the smallest relevant Laravel test or command.
- Do not edit generated assets in public/build or dependencies in vendor unless the task explicitly requires it.

## Common commands
- composer test
- php artisan test
- npm run build
- composer run dev

## Project focus
- Public portfolio: routes/web.php, app/Http/Controllers/{HomeController,ProjectPublicController,...}
- Admin CMS: app/Http/Controllers/Admin/
- Data models and SEO: app/Models/
- Templates: resources/views/
- Styling: resources/css/ and Tailwind config

Use AGENTS.md as the primary repo guidance and README.md for broader background when needed.
