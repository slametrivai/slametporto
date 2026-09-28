<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            config([
                'seo.site_name' => setting('site_name', 'Slamet Rivai'),
                'seo.description.fallback' => setting('meta_description', 'Operations & Systems Leader with 9+ years experience optimizing CRM, automated pipelines, and enterprise systems.'),
                'seo.author.fallback' => setting('site_author', 'Slamet Rivai'),
                'seo.twitter.@username' => ltrim((string) setting('site_twitter', 'slametrivai'), '@'),
                'seo.title.homepage_title' => setting('site_title', 'Slamet Rivai — Operations & Systems Leader | Portfolio & Architecture'),
                'seo.title.suffix' => '',
            ]);
        } catch (\Throwable $e) {
            // Failsafe during migration or console execution
        }
    }
}
