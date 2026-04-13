<?php
/**
 * Simple .env loader (no Composer / no dependencies).
 *
 * Loads KEY=VALUE pairs from a dotenv file into an internal static store
 * that can be read via Env::get() or the env() helper. Supports:
 *   - Comments starting with #
 *   - Blank lines
 *   - Values wrapped in single or double quotes
 *   - Variables without quotes
 *
 * Values are NOT exported to $_ENV / getenv() because some shared hosts
 * disable putenv(). Reads are always done through this loader.
 */
class Env
{
    private static array $values = [];
    private static bool $loaded = false;

    public static function load(string $path): void
    {
        if (self::$loaded || !is_file($path) || !is_readable($path)) {
            self::$loaded = true;
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            self::$loaded = true;
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }

            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            if ($key === '') {
                continue;
            }

            // Strip matching surrounding quotes
            $len = strlen($value);
            if ($len >= 2) {
                $first = $value[0];
                $last = $value[$len - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            // Strip inline comments for unquoted values (e.g. KEY=value # comment)
            if (strpos($value, '#') !== false && !preg_match('/^".*"$|^\'.*\'$/', $value)) {
                $value = trim(preg_replace('/\s+#.*$/', '', $value));
            }

            self::$values[$key] = $value;
        }

        self::$loaded = true;
    }

    public static function get(string $key, $default = null)
    {
        return array_key_exists($key, self::$values) ? self::$values[$key] : $default;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, self::$values);
    }

    public static function all(): array
    {
        return self::$values;
    }

    public static function require(string $key): string
    {
        if (!array_key_exists($key, self::$values) || self::$values[$key] === '') {
            http_response_code(500);
            error_log("Missing required environment variable: {$key}");
            die('Configuração do servidor incompleta. Contate o suporte.');
        }
        return self::$values[$key];
    }
}
