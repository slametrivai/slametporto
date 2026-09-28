<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => 'Slamet Rivai',
            'site_tagline' => 'Operations & Systems Leader',
            'site_title' => 'Slamet Rivai | Operations & Systems Leader, Portfolio & Architecture',
            'meta_description' => 'Operations & Systems Leader with 9+ years experience architecting customer operations, CRM hubs, OCR document pipelines, and scalable enterprise workflows.',
            'meta_keywords' => 'Operations Leader, Systems Architecture, CRM Infrastructure, Zendesk, Salesforce, Python Automation, OCR Pipeline, Slamet Rivai',
            'site_author' => 'Slamet Rivai',
            'canonical_url' => 'https://slametrivai.host',
            'twitter_handle' => '@slametrivai',
            'site_logo' => null,
            'site_favicon' => null,
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
