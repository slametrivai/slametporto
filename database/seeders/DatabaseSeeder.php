<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Category;
use App\Models\Certification;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin User
        User::firstOrCreate(
            ['email' => 'admin@slametrivai.com'],
            [
                'name' => 'Slamet Rivai',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 1.1 Default Site & SEO Settings
        $this->call(SettingSeeder::class);

        // 2. Careers
        $careers = [
            [
                'period' => '2024 - Present',
                'role' => 'Operations & Automation Architecture Consultant',
                'company' => 'Independent Consultant',
                'description' => 'Advising high-growth SMEs and mid-market enterprises on end-to-end operational modernization, WhatsApp Cloud API integrations, OCR document processing pipelines, and data-driven CRM systems.',
                'sort_order' => 1,
            ],
            [
                'period' => '2021 - 2024',
                'role' => 'Customer Operations & Systems Lead',
                'company' => 'PT Global Tiket Network (Tiket.com)',
                'description' => 'Spearheaded operational excellence across tiered customer operations. Optimized cross-channel escalation pipelines, automated ticketing dispatch, and developed real-time performance analytics dashboards.',
                'sort_order' => 2,
            ],
            [
                'period' => '2019 - 2021',
                'role' => 'Head of Operations & CRM Systems',
                'company' => 'PT Wisata Universal',
                'description' => 'Led end-to-end booking fulfillment operations and customer lifecycle systems. Reduced manual voucher issuance time by 75% through automated vendor API synchronization and reconciliation workflows.',
                'sort_order' => 3,
            ],
            [
                'period' => '2018 - 2019',
                'role' => 'Operations Systems Lead',
                'company' => 'PT Baitussalam Mandiri',
                'description' => 'Standardized administrative workflows, implemented digital patient intake verification, and connected third-party health insurance claim workflows directly with accounting.',
                'sort_order' => 4,
            ],
            [
                'period' => '2016 - 2018',
                'role' => 'Senior Operations & Automation Analyst',
                'company' => 'PT Cipta Optima',
                'description' => 'Engineered custom internal ticketing routing, automated recurring enterprise billing pipelines, and reduced monthly reporting generation time from 3 days to under 15 minutes.',
                'sort_order' => 5,
            ],
            [
                'period' => '2015 - 2016',
                'role' => 'Business Process & Systems Associate',
                'company' => 'PT Sumber Rezeki Exata',
                'description' => 'Automated supply chain data entry pipelines, developed spreadsheet-to-database automation scripts, and established audit validation protocols.',
                'sort_order' => 6,
            ],
        ];

        foreach ($careers as $c) {
            Career::firstOrCreate(
                ['company' => $c['company'], 'role' => $c['role']],
                $c
            );
        }

        // 3. Certifications
        $certifications = [
            [
                'title' => 'BNSP Data Management Specialist',
                'issuer' => 'Badan Nasional Sertifikasi Profesi (BNSP)',
                'score' => 'Certified Specialist',
                'valid_period' => '2025 – 2028',
                'credential_url' => 'https://bnsp.go.id',
            ],
            [
                'title' => 'Certified Contact Center Team Leader',
                'issuer' => 'Telexindo Bizmart Indonesia',
                'score' => 'Skor 90 / 100 (Distinction)',
                'valid_period' => 'Lifetime Credential',
                'credential_url' => 'https://telexindo.com',
            ],
            [
                'title' => 'EF SET English Certificate (C2 Proficient)',
                'issuer' => 'EF Standard English Test',
                'score' => 'Skor 76 / 100 (C2 Proficient)',
                'valid_period' => 'Verified 2024',
                'credential_url' => 'https://www.efset.org',
            ],
        ];

        foreach ($certifications as $cert) {
            Certification::firstOrCreate(
                ['title' => $cert['title'], 'issuer' => $cert['issuer']],
                $cert
            );
        }

        // 4. Clients
        $clients = [
            [
                'name' => 'PT Global Tiket Network',
                'industry' => 'Travel & Hospitality',
                'logo' => 'clients/tiket-network.svg',
                'website_url' => 'https://www.tiket.com',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'PT Wisata Universal',
                'industry' => 'Travel & Hospitality',
                'logo' => 'clients/wisata-universal.svg',
                'website_url' => 'https://wisatauniversal.com',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'PT Baitussalam Medika',
                'industry' => 'Healthcare',
                'logo' => 'clients/baitussalam.svg',
                'website_url' => 'https://baitussalam.co.id',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'PT Cipta Optima',
                'industry' => 'SMEs',
                'logo' => 'clients/cipta-optima.svg',
                'website_url' => 'https://ciptaoptima.co.id',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'PT Sumber Rezeki Exata',
                'industry' => 'Healthcare',
                'logo' => 'clients/exata-medika.svg',
                'website_url' => 'https://sumberrezeki.co.id',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Yayasan Nusantara Edu',
                'industry' => 'Education & NGO',
                'logo' => 'clients/nusantara-edu.svg',
                'website_url' => 'https://nusantaraedu.org',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($clients as $cl) {
            Client::firstOrCreate(['name' => $cl['name']], $cl);
        }

        // 5. Projects
        $projects = [
            [
                'title' => 'AI-Powered Document OCR & Enterprise ERP Pipeline',
                'slug' => 'ai-powered-document-ocr-enterprise-erp-pipeline',
                'category' => 'Automation & OCR',
                'role' => 'Lead Systems Architect & Ops Director',
                'challenge' => 'Manual processing of over 5,000 monthly invoices, vouchers, and settlement sheets generated severe operational latency of up to 48 hours, high human error rates (6.2%), and significant staff overtime costs during high-season spikes.',
                'solution' => 'Architected a resilient automated pipeline orchestrating computer vision OCR, custom regex tokenizers, confidence scoring algorithms, and a seamless REST webhook delivery into enterprise ERP and financial ledgers.',
                'impact_highlights' => [
                    ['metric' => '2 DAYS → ±30 MINS', 'label' => 'Processing Cycle'],
                    ['metric' => '99.4%', 'label' => 'Extraction Accuracy'],
                    ['metric' => '-78%', 'label' => 'Operational Cost'],
                ],
                'workflow_steps' => ['Customer Doc', 'OCR Engine', 'Structured Data', 'Ops System'],
                'cover_image' => 'projects/project-ocr.svg',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Omnichannel Contact Center & CRM Hub',
                'slug' => 'omnichannel-contact-center-crm-hub',
                'category' => 'CRM & Sales',
                'role' => 'Operations Lead & CRM Architect',
                'challenge' => 'Customer interactions were isolated across fragmented platforms (Email, WhatsApp, Phone, Web Portal), resulting in lost context, delayed responses, and frequent customer dissatisfaction.',
                'solution' => 'Engineered a unified CRM hub aggregating all inbound communication channels into a single operational interface with automatic customer 360 profiling, intelligent queue routing, and automated SLA breach alerts.',
                'impact_highlights' => [
                    ['metric' => '↑40%', 'label' => 'Productivity Gain'],
                    ['metric' => '4.8 / 5.0', 'label' => 'CSAT Score'],
                    ['metric' => '< 2 Mins', 'label' => 'First Response Time'],
                ],
                'workflow_steps' => ['Inbound Request', 'Intent Classifier', 'Omnichannel CRM', 'Resolution SLA'],
                'cover_image' => 'projects/project-crm.svg',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'High-Throughput WhatsApp Cloud API Notification Engine',
                'slug' => 'high-throughput-whatsapp-cloud-api-notification-engine',
                'category' => 'WhatsApp API',
                'role' => 'Systems Automation Lead',
                'challenge' => 'Legacy SMS alerts had poor deliverability (<82%), lacked real-time delivery state confirmations, and prevented two-way interactive confirmation for critical booking updates.',
                'solution' => 'Implemented an asynchronous Redis-backed WhatsApp Cloud API dispatcher handling dynamic templates, rate limit back-off, webhook status tracking, and automated fallback to email.',
                'impact_highlights' => [
                    ['metric' => '99.8%', 'label' => 'Delivery Rate'],
                    ['metric' => '3.2x', 'label' => 'Interactive Engagement'],
                    ['metric' => '50K+/Day', 'label' => 'Daily Message Volume'],
                ],
                'workflow_steps' => ['Order Event', 'Message Queue', 'WhatsApp API', 'Delivery Webhook'],
                'cover_image' => 'projects/project-wa.svg',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Dynamic Workforce Roster & Shift Allocation Matrix',
                'slug' => 'dynamic-workforce-roster-shift-allocation-matrix',
                'category' => 'HR Systems',
                'role' => 'Operations Systems Designer',
                'challenge' => 'Managing 24/7 rotating shifts for 120+ customer support personnel via static spreadsheets resulted in scheduling deadlocks, unfair shift distributions, and cumbersome payroll calculations.',
                'solution' => 'Built an automated scheduling matrix incorporating employee skill tiers, legal rest windows, holiday policies, and instant iCal/Google Calendar sync with one-click overtime export.',
                'impact_highlights' => [
                    ['metric' => '100%', 'label' => 'Conflict-Free Schedules'],
                    ['metric' => '15 Hrs/Wk', 'label' => 'Admin Time Saved'],
                    ['metric' => 'Instant', 'label' => 'Payroll Reconciliation'],
                ],
                'workflow_steps' => ['Staff Constraints', 'Rule Solver', 'Roster Generator', 'Agent Sync'],
                'cover_image' => 'projects/project-hr.svg',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'title' => 'Zero-Touch Customer Self-Service Portal',
                'slug' => 'zero-touch-customer-self-service-portal',
                'category' => 'Digital Experience',
                'role' => 'Process & Product Strategist',
                'challenge' => 'Over 60% of agent bandwidth was consumed by trivial tier-1 repetitive inquiries including receipt downloads, reschedule status checks, and profile adjustments.',
                'solution' => 'Constructed an intuitive self-service customer portal with biometric/OTP sign-in, automated PDF invoice generation, and real-time reservation amendment APIs.',
                'impact_highlights' => [
                    ['metric' => '-55%', 'label' => 'Tier-1 Ticket Volume'],
                    ['metric' => '24/7', 'label' => 'Instant Resolution'],
                    ['metric' => '92%', 'label' => 'Task Completion Rate'],
                ],
                'workflow_steps' => ['Customer Query', 'Identity Auth', 'Self-Service API', 'Instant Fulfillment'],
                'cover_image' => 'projects/project-digital.svg',
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'title' => 'Enterprise SLA Routing & Escalation Matrix',
                'slug' => 'enterprise-sla-routing-escalation-matrix',
                'category' => 'CRM & Sales',
                'role' => 'Operations Lead',
                'challenge' => 'Critical enterprise B2B partner issues were queued with standard inquiries, leading to delayed escalations and contractual SLA penalty risks.',
                'solution' => 'Designed a dynamic SLA priority scoring engine that evaluates enterprise tier, business impact, and ticket age to route incidents directly to senior on-duty engineers.',
                'impact_highlights' => [
                    ['metric' => '99.9%', 'label' => 'Enterprise SLA Met'],
                    ['metric' => '15 Mins', 'label' => 'Avg Escalation Time'],
                    ['metric' => 'Zero', 'label' => 'Overlooked Incidents'],
                ],
                'workflow_steps' => ['Incident Alert', 'Priority Matrix', 'Tiered Dispatch', 'Manager Alert'],
                'cover_image' => 'projects/project-ticketing.svg',
                'is_featured' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($projects as $p) {
            Project::firstOrCreate(['slug' => $p['slug']], $p);
        }

        // 6. Categories & Blog Posts
        $categoryAutomation = Category::firstOrCreate(
            ['slug' => 'workflow-automation'],
            [
                'name' => 'Workflow Automation',
                'type' => 'post',
                'description' => 'Arsitektur otomatisasi pipeline, integrasi OCR cerdas, dan orkestrasi alur kerja operasional enterprise.',
            ]
        );

        $categoryCrm = Category::firstOrCreate(
            ['slug' => 'systems-and-crm'],
            [
                'name' => 'Systems & CRM',
                'type' => 'post',
                'description' => 'Infrastruktur CRM modern, perutean antrean pelanggan, dan sinkronisasi omni-channel.',
            ]
        );

        $categoryLeadership = Category::firstOrCreate(
            ['slug' => 'operations-leadership'],
            [
                'name' => 'Operations Leadership',
                'type' => 'post',
                'description' => 'Strategi kepemimpinan operasional tim, manajemen SLA terukur, dan mitigasi bottleneck bisnis.',
            ]
        );

        $projectCategories = [
            [
                'name' => 'Automation & OCR',
                'slug' => 'automation-and-ocr',
                'type' => 'project',
                'description' => 'Studi kasus implementasi pipeline OCR otomatis dan integrasi ledger akuntansi ERP.',
            ],
            [
                'name' => 'CRM & Sales',
                'slug' => 'crm-and-sales',
                'type' => 'project',
                'description' => 'Studi kasus integrasi hub CRM omnichannel dan perutean eskalasi SLA 99.9%.',
            ],
            [
                'name' => 'WhatsApp API',
                'slug' => 'whatsapp-api',
                'type' => 'project',
                'description' => 'Studi kasus arsitektur mesin notifikasi WhatsApp Cloud API dengan throughput tinggi.',
            ],
            [
                'name' => 'HR Systems',
                'slug' => 'hr-systems',
                'type' => 'project',
                'description' => 'Studi kasus matriks alokasi jadwal shift dinamis dan rekonsiliasi payroll otomatis.',
            ],
            [
                'name' => 'Digital Experience',
                'slug' => 'digital-experience',
                'type' => 'project',
                'description' => 'Studi kasus portal swalayan pelanggan zero-touch dan automasi tiket tier-1.',
            ],
        ];

        foreach ($projectCategories as $pCat) {
            Category::firstOrCreate(['slug' => $pCat['slug']], $pCat);
        }

        $posts = [
            [
                'category_id' => $categoryAutomation->id,
                'title' => 'Architecting Resilient Document OCR Pipelines for High-Volume Operations',
                'slug' => 'architecting-resilient-document-ocr-pipelines-for-high-volume-operations',
                'excerpt' => 'A practical blueprint for engineering automated document parsers that convert messy vendor invoices into structured, ERP-ready financial entries with sub-minute latency.',
                'content' => <<<'MARKDOWN'
## The Bottleneck of Manual Invoicing

In high-growth companies handling thousands of partner transactions weekly, document validation remains one of the single biggest operational chokepoints. When human operators manually inspect scans, retype invoice line items, and match tax IDs against purchase orders, errors are inevitable.

At scale, a **6% manual error rate** translates into hundreds of accounting disputes, delayed partner settlements, and thousands of hours in reconciliation overhead.

```
+---------------+     +---------------+     +--------------------+     +---------------+
|  PDF / Scan   | --> | Preprocessing | --> | Heuristic OCR Engine| --> | ERP Connector |
| Ingestion API |     | (Binarize/Deskew)|  | & Field Extraction |     | (Reconciled)  |
+---------------+     +---------------+     +--------------------+     +---------------+
```

---

## 1. Image Normalization & Preprocessing

Before passing images into an OCR parser, raw documents must be standardized. Mobile phone uploads frequently suffer from uneven lighting, rotation skew, and compression artifacts.

Key preprocessing steps include:
* **Adaptive Thresholding (Otsu method):** Eliminates background gradients and enhances dark typography against paper textures.
* **Deskewing Algorithms:** Calculates text orientation angle using Hough line transform to align lines horizontally within +/- 0.5 degrees.
* **Resolution Upscaling:** Ensures text density meets a minimum threshold of 300 DPI for optical character clarity.

---

## 2. Structured Token Extraction with Regex & Anchors

Raw OCR output yields bounding boxes and plain strings. The true architectural challenge lies in extracting semantic key-value pairs:

```json
{
  "invoice_number": "INV-2026-8941",
  "tax_id": "01.234.567.8-901.000",
  "subtotal_amount": 14500000,
  "tax_amount": 1595000,
  "grand_total": 16095000,
  "line_items_count": 8
}
```

By defining relative spatial anchors (e.g., searching for numeric currency tokens within 200px to the right of the phrase *"Total Due"* or *"Subtotal"*), the parser maintains over **99.4% accuracy** across diverse vendor layouts.

---

## 3. Human-in-the-Loop Confidence Scoring

Total automation should never come at the expense of fiscal accuracy. Every extracted field carries a confidence score between 0.00 and 1.00.

* **Score >= 0.95:** Fully automated straight-through processing. The transaction posts directly to the accounting ledger.
* **Score < 0.95:** Automatically routed into an exception queue where an operations specialist inspects highlighted discrepancy zones with a single-click verification interface.

This dual-tier approach reduced end-to-end processing turnaround from **48 hours down to under 30 minutes** while completely eliminating reconciliation discrepancies.
MARKDOWN,
                'cover_image' => 'posts/post-ocr-pipeline.svg',
                'status' => 'published',
                'views_count' => 412,
                'published_at' => Carbon::now()->subDays(4),
            ],
            [
                'category_id' => $categoryCrm->id,
                'title' => 'Scaling WhatsApp Cloud API: Queue Throttling, Webhooks, and Error Recovery',
                'slug' => 'scaling-whatsapp-cloud-api-queue-throttling-webhooks-and-error-recovery',
                'excerpt' => 'Technical strategies to navigate Meta Cloud API rate limits, orchestrate asynchronous message queues, and guarantee zero dropped transactional notifications.',
                'content' => <<<'MARKDOWN'
## The Challenge of Enterprise Messaging

WhatsApp has become the predominant channel for transactional customer communications across Southeast Asia. However, transitioning from casual messaging to enterprise-grade automated dispatching (50,000+ messages daily) introduces rigorous engineering constraints.

Meta imposes strict tier limits, throughput restrictions (messages per second), and webhook acknowledgment timeouts. Failing to handle these gracefully leads to dropped messages and account reputation downgrades.

---

## 1. Asynchronous Queue Architecture with Redis

Synchronous API requests during customer checkout flows are an anti-pattern. If Meta's Graph API experiences latency spikes, your checkout flow hangs.

Instead, decouple message generation from delivery:

```
[Web / App Checkout] 
         │ (Enqueue Job)
         ▼
[Redis Priority Queue]
         │ (Worker Pool / Token Bucket)
         ▼
[WhatsApp Cloud API Gateway] ──► [Customer Phone]
         │ (Async Status Webhook)
         ▼
[Callback Handler & DB Status Sync]
```

### Rate Limiting with Token Bucket

Implement a Token Bucket algorithm within your background workers to guarantee your transmission rate remains strictly within Meta tier limits (e.g., 80 requests/second):

```php
// Dispatcher Rate Throttle Check
Redis::throttle('whatsapp_dispatch')
    ->allow(80)
    ->every(1)
    ->then(function () use ($message) {
        $this->client->sendTemplateMessage($message);
    }, function () {
        // Release back to queue with exponential backoff
        return $this->release(2);
    });
```

---

## 2. Webhook Verification and Idempotency

Meta webhooks broadcast status transitions: `sent`, `delivered`, `read`, and `failed`. High-volume webhook spikes can overwhelm database connections.

To ensure resilience:
1. **Immediate 200 OK Response:** Acknowledge receipt within 2 seconds before executing heavy logic.
2. **Idempotency Keys:** Cache incoming `message_id` with status in Redis for 60 seconds to ignore duplicate delivery callbacks.
3. **Dead Letter Queue (DLQ):** Messages failing due to invalid recipient numbers or rate limits are archived with explicit error codes for automated customer service retry workflows.

Through this architecture, message delivery rates consistently exceed **99.8%**, providing customers with near-instant order verifications.
MARKDOWN,
                'cover_image' => 'posts/post-whatsapp-crm.svg',
                'status' => 'published',
                'views_count' => 625,
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'category_id' => $categoryLeadership->id,
                'title' => 'Designing Enterprise SLA Routing Matrices for Zero Escalation Drift',
                'slug' => 'designing-enterprise-sla-routing-matrices-for-zero-escalation-drift',
                'excerpt' => 'Frameworks for structuring multi-tiered support queues, automating priority escalation triggers, and aligning operational KPIs with tangible commercial outcomes.',
                'content' => <<<'MARKDOWN'
## The Pitfalls of FIFO Queue Management

First-In, First-Out (FIFO) queue management is the default operational setup for most nascent support teams. While deceptively simple, FIFO fails catastrophically under load. A simple password reset can trap a VIP partner experiencing an urgent multi-million rupiah payment failure behind hundreds of routine inquiries.

To maintain world-class customer retention, operations leaders must transition to **Dynamic Weighted SLA Routing**.

---

## 1. Multi-Dimensional Priority Matrix

Rather than assigning priority arbitrarily, calculate dynamic urgency using three deterministic vectors:

1. **Customer Tier Weight ($W_c$):** Platinum Enterprise (3.0x), Standard Partner (1.5x), General Public (1.0x).
2. **Impact Severity ($W_i$):** Service Outage (4.0x), Financial Mismatch (2.5x), Informational Request (1.0x).
3. **Queue Wait Time ($T_w$):** Measured in fractional SLA exhaustion percentage.

$$\text{Priority Score} = (W_c \times 0.4) + (W_i \times 0.4) + (T_w \times 0.2)$$

Incoming tickets are sorted dynamically by Priority Score, guaranteeing that high-risk incidents surface to the top of available agent queues instantly.

---

## 2. Automated Tiered Escalation Rules

When a ticket approaches 70% of its contractually agreed SLA window without a recorded first-touch response:

* **T-30%:** Instant automated alert dispatched to the on-duty shift supervisor via Slack/Teams webhook.
* **T-15%:** Automatic reassignment to secondary senior pool with priority override.
* **T-0%:** Escalation logged as an operational incident for post-mortem SLA compliance audits.

Implementing this systematic framework enabled our teams to achieve **99.9% VIP SLA compliance** while reducing average critical escalation turnaround to **under 15 minutes**.
MARKDOWN,
                'cover_image' => 'posts/post-sla-ops.svg',
                'status' => 'published',
                'views_count' => 380,
                'published_at' => Carbon::now()->subDays(16),
            ],
        ];

        foreach ($posts as $p) {
            Post::firstOrCreate(['slug' => $p['slug']], $p);
        }

        // 7. Initial Inquiries
        $inquiries = [
            [
                'name' => 'Aditya Pratama',
                'email' => 'aditya.pratama@techcorp.id',
                'subject' => 'Consulting Inquiry: Automated WhatsApp Cloud API & CRM Integration',
                'message' => 'Halo Pak Slamet Rivai, kami melihat studi kasus implementasi WhatsApp API dan OCR pipeline Anda. Kami ingin mendiskusikan peluang konsultasi untuk merampingkan sistem operasional customer support di perusahaan kami.',
                'status' => 'new',
            ],
            [
                'name' => 'Maya Anggraini',
                'email' => 'maya.recruitment@globalfin.co.id',
                'subject' => 'Executive Opportunity: Head of Operations & Systems Architecture',
                'message' => 'Dear Pak Slamet, rekam jejak Anda di Tiket.com dan sertifikasi BNSP Data Management sangat mengesankan bagi dewan direksi kami. Apakah Anda terbuka untuk mendiskusikan posisi kepemimpinan operasional?',
                'status' => 'responded',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@nusantararetail.com',
                'subject' => 'Permintaan Kolaborasi: Optimasi Alur Kerja ERP',
                'message' => 'Selamat siang Pak Slamet, kami memerlukan arahan terkait integrasi OCR dokumen faktur vendor kami agar terhubung langsung dengan sistem ERP backend kami.',
                'status' => 'read',
            ],
        ];

        foreach ($inquiries as $inq) {
            Inquiry::firstOrCreate(['email' => $inq['email'], 'subject' => $inq['subject']], $inq);
        }
    }
}
