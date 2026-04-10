<?php
/**
 * EventDispatcher — lifecycle event facade.
 *
 * Controllers call EventDispatcher::dispatch('order.paid', [...])
 * at insertion points throughout the app. The facade forwards the
 * event to Sequenzy (the current destination) via a lazy singleton.
 *
 * Payload contract:
 *   [
 *     'email' => string,
 *     'attributes' => array (optional — user-level traits),
 *     'properties' => array (optional — event-level data),
 *   ]
 *
 * Failures never propagate — missing email is silently dropped and
 * transport errors are logged via Sequenzy's own error_log() calls.
 * The app continues even if the automation stack is unreachable.
 */
class EventDispatcher
{
    private static ?SequenzyClient $sequenzy = null;

    public static function dispatch(string $event, array $payload): void
    {
        $email = trim((string) ($payload['email'] ?? ''));
        if ($email === '') {
            return;
        }

        $attributes = is_array($payload['attributes'] ?? null) ? $payload['attributes'] : [];
        $properties = is_array($payload['properties'] ?? null) ? $payload['properties'] : [];

        try {
            self::sequenzy()->send($event, $email, $attributes, $properties);
        } catch (Throwable $e) {
            error_log(sprintf(
                'EventDispatcher: dispatch of %s failed: %s',
                $event,
                $e->getMessage()
            ));
        }
    }

    private static function sequenzy(): SequenzyClient
    {
        if (self::$sequenzy === null) {
            require_once BASE_PATH . '/src/Sequenzy.php';
            self::$sequenzy = new SequenzyClient();
        }
        return self::$sequenzy;
    }
}
