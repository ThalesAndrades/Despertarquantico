<?php

class MarketingCRM
{
    public static function track(string $event, string $email, array $attributes = [], array $properties = []): void
    {
        $email = strtolower(trim($email));
        if ($email === '') {
            return;
        }

        try {
            $leadId = self::upsertLead($email, $attributes, $properties, $event);
            if ($leadId) {
                self::insertEvent($leadId, $event, $properties);
                self::applyScoring($leadId, $event);
                self::applyAutoTags($leadId, $event, $properties);
            }
        } catch (Throwable $e) {
            error_log('MarketingCRM: ' . $e->getMessage());
        }
    }

    private static function upsertLead(string $email, array $attributes, array $properties, string $event): ?int
    {
        require_once BASE_PATH . '/src/Database.php';

        if (!self::tableExists('crm_leads')) {
            return null;
        }

        $name = trim((string) ($attributes['name'] ?? $properties['name'] ?? ''));
        $whatsapp = trim((string) ($attributes['whatsapp'] ?? $properties['whatsapp'] ?? ''));
        $source = trim((string) ($properties['source'] ?? $attributes['source'] ?? ''));
        $pain = trim((string) ($attributes['pain_primary'] ?? $properties['pain_primary'] ?? ''));
        $archetype = trim((string) ($attributes['social_archetype'] ?? $properties['social_archetype'] ?? ''));
        $stage = trim((string) ($attributes['stage'] ?? $properties['stage'] ?? ''));

        $utmSource = trim((string) ($properties['utm_source'] ?? ''));
        $utmMedium = trim((string) ($properties['utm_medium'] ?? ''));
        $utmCampaign = trim((string) ($properties['utm_campaign'] ?? ''));
        $utmContent = trim((string) ($properties['utm_content'] ?? ''));
        $utmTerm = trim((string) ($properties['utm_term'] ?? ''));

        $existing = Database::fetch("SELECT id FROM crm_leads WHERE email = ?", [$email]);
        if ($existing) {
            $leadId = (int) $existing['id'];
            Database::query(
                "UPDATE crm_leads
                 SET name = IF(?, ?, name),
                     whatsapp = IF(?, ?, whatsapp),
                     source = IF(?, ?, source),
                     pain_primary = IF(?, ?, pain_primary),
                     social_archetype = IF(?, ?, social_archetype),
                     stage = IF(?, ?, stage),
                     utm_source = IF(?, ?, utm_source),
                     utm_medium = IF(?, ?, utm_medium),
                     utm_campaign = IF(?, ?, utm_campaign),
                     utm_content = IF(?, ?, utm_content),
                     utm_term = IF(?, ?, utm_term),
                     last_event = ?,
                     last_event_at = NOW(),
                     updated_at = NOW()
                 WHERE id = ?",
                [
                    $name !== '' ? 1 : 0, $name,
                    $whatsapp !== '' ? 1 : 0, $whatsapp,
                    $source !== '' ? 1 : 0, $source,
                    $pain !== '' ? 1 : 0, $pain,
                    $archetype !== '' ? 1 : 0, $archetype,
                    $stage !== '' ? 1 : 0, $stage,
                    $utmSource !== '' ? 1 : 0, $utmSource,
                    $utmMedium !== '' ? 1 : 0, $utmMedium,
                    $utmCampaign !== '' ? 1 : 0, $utmCampaign,
                    $utmContent !== '' ? 1 : 0, $utmContent,
                    $utmTerm !== '' ? 1 : 0, $utmTerm,
                    $event,
                    $leadId,
                ]
            );
            return $leadId;
        }

        Database::query(
            "INSERT INTO crm_leads
             (email, name, whatsapp, source, pain_primary, social_archetype, stage, utm_source, utm_medium, utm_campaign, utm_content, utm_term, score, last_event, last_event_at, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, NOW(), NOW(), NOW())",
            [
                $email,
                $name,
                $whatsapp,
                $source,
                $pain,
                $archetype,
                $stage,
                $utmSource,
                $utmMedium,
                $utmCampaign,
                $utmContent,
                $utmTerm,
                $event,
            ]
        );
        return (int) Database::getInstance()->lastInsertId();
    }

    private static function insertEvent(int $leadId, string $event, array $properties): void
    {
        if (!self::tableExists('crm_lead_events')) {
            return;
        }

        require_once BASE_PATH . '/src/Database.php';
        $payload = json_encode($properties, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        Database::query(
            "INSERT INTO crm_lead_events (lead_id, event_name, properties_json, created_at)
             VALUES (?, ?, ?, NOW())",
            [$leadId, $event, $payload]
        );
    }

    private static function applyScoring(int $leadId, string $event): void
    {
        if (!self::tableExists('crm_leads')) {
            return;
        }

        $delta = 0;
        switch ($event) {
            case 'lead.optin':
                $delta = 5;
                break;
            case 'lead.lead_magnet.ritual7days':
                $delta = 8;
                break;
            case 'lead.diagnostic_completed':
                $delta = 15;
                break;
            case 'checkout.started':
                $delta = 20;
                break;
            case 'order.paid':
                $delta = 50;
                break;
            case 'course.completed':
                $delta = 30;
                break;
            case 'high_ticket.application_submitted':
                $delta = 80;
                break;
        }

        if ($delta <= 0) {
            return;
        }

        require_once BASE_PATH . '/src/Database.php';
        Database::query("UPDATE crm_leads SET score = score + ? WHERE id = ?", [$delta, $leadId]);
    }

    private static function applyAutoTags(int $leadId, string $event, array $properties): void
    {
        if (!self::tableExists('crm_tags') || !self::tableExists('crm_lead_tags')) {
            return;
        }

        $tags = [];

        if ($event === 'lead.optin') {
            $tags[] = 'optin';
        }
        if (substr($event, 0, strlen('lead.optin.pain_')) === 'lead.optin.pain_') {
            $tags[] = 'pain:' . substr($event, strlen('lead.optin.'));
        }
        if (substr($event, 0, strlen('lead.diagnostic_completed.pain_')) === 'lead.diagnostic_completed.pain_') {
            $tags[] = 'pain:' . substr($event, strlen('lead.diagnostic_completed.'));
        }
        if ($event === 'checkout.started') {
            $tags[] = 'intent:checkout';
            if (!empty($properties['product_slug'])) {
                $tags[] = 'product:' . preg_replace('/[^a-z0-9_-]/i', '', (string) $properties['product_slug']);
            }
        }
        if ($event === 'order.paid') {
            $tags[] = 'customer';
            if (!empty($properties['product_slug'])) {
                $tags[] = 'product:' . preg_replace('/[^a-z0-9_-]/i', '', (string) $properties['product_slug']);
            }
        }
        if ($event === 'high_ticket.application_submitted') {
            $tags[] = 'intent:high-ticket';
        }

        foreach ($tags as $tag) {
            self::addTagToLead($leadId, $tag);
        }
    }

    private static function addTagToLead(int $leadId, string $tagSlug): void
    {
        $tagSlug = strtolower(trim($tagSlug));
        if ($tagSlug === '') {
            return;
        }

        require_once BASE_PATH . '/src/Database.php';

        $tag = Database::fetch("SELECT id FROM crm_tags WHERE slug = ?", [$tagSlug]);
        if (!$tag) {
            Database::query(
                "INSERT INTO crm_tags (slug, name, created_at) VALUES (?, ?, NOW())",
                [$tagSlug, $tagSlug]
            );
            $tagId = (int) Database::getInstance()->lastInsertId();
        } else {
            $tagId = (int) $tag['id'];
        }

        Database::query(
            "INSERT IGNORE INTO crm_lead_tags (lead_id, tag_id, created_at) VALUES (?, ?, NOW())",
            [$leadId, $tagId]
        );
    }

    private static function tableExists(string $table): bool
    {
        require_once BASE_PATH . '/src/Database.php';
        return (bool) Database::fetch(
            "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?",
            [$table]
        );
    }
}

