<?php
/**
 * Runner de testes minimalista — zero dependencias, no espirito do projeto
 * (sem Composer, sem PHPUnit).
 *
 * Uso:
 *   docker run --rm -v "$PWD:/app" -w /app php:8.2-cli php tests/run.php
 *
 * Faz duas coisas:
 *   1. LINT — php -l em todo arquivo .php do projeto.
 *   2. TESTES — carrega tests/cases/*.php e executa o que foi registrado via test().
 *
 * Sai com codigo 1 se qualquer lint ou teste falhar (serve para CI).
 */
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('TESTS_PATH', __DIR__);

final class TestRegistry
{
    /** @var array<int, array{0: string, 1: callable}> */
    public static array $tests = [];
    public static string $currentFile = '';
}

final class AssertionFailed extends Exception
{
}

function test(string $name, callable $fn): void
{
    TestRegistry::$tests[] = [$name, $fn];
}

function assertTrue($condition, string $message = ''): void
{
    if ($condition !== true) {
        throw new AssertionFailed($message !== '' ? $message : 'esperava true, veio ' . var_export($condition, true));
    }
}

function assertFalse($condition, string $message = ''): void
{
    if ($condition !== false) {
        throw new AssertionFailed($message !== '' ? $message : 'esperava false, veio ' . var_export($condition, true));
    }
}

function assertSame($expected, $actual, string $message = ''): void
{
    if ($expected !== $actual) {
        throw new AssertionFailed(
            ($message !== '' ? $message . ' — ' : '') .
            'esperava ' . var_export($expected, true) . ', veio ' . var_export($actual, true)
        );
    }
}

function assertStringContains(string $needle, string $haystack, string $message = ''): void
{
    if (strpos($haystack, $needle) === false) {
        throw new AssertionFailed(
            ($message !== '' ? $message . ' — ' : '') . "esperava encontrar '{$needle}' em '{$haystack}'"
        );
    }
}

// ---------------------------------------------------------------- LINT

/**
 * @return array{0: int, 1: array<int, string>} [arquivosVerificados, erros]
 */
function runLint(): array
{
    $skipDirs = ['/.git/', '/vendor/', '/node_modules/'];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(BASE_PATH, FilesystemIterator::SKIP_DOTS)
    );

    $checked = 0;
    $errors = [];

    foreach ($iterator as $file) {
        /** @var SplFileInfo $file */
        if ($file->getExtension() !== 'php') {
            continue;
        }
        $path = str_replace('\\', '/', $file->getPathname());
        foreach ($skipDirs as $skip) {
            if (strpos($path, $skip) !== false) {
                continue 2;
            }
        }

        $checked++;
        $output = [];
        $exitCode = 0;
        exec('php -l ' . escapeshellarg($file->getPathname()) . ' 2>&1', $output, $exitCode);
        if ($exitCode !== 0) {
            $errors[] = implode("\n", $output);
        }
    }

    return [$checked, $errors];
}

// ---------------------------------------------------------------- EXECUCAO

echo "\n== LINT ==\n";
[$checked, $lintErrors] = runLint();

if ($lintErrors === []) {
    echo "  OK — {$checked} arquivos PHP compilam\n";
} else {
    foreach ($lintErrors as $error) {
        echo "  FALHA: {$error}\n";
    }
    echo "  " . count($lintErrors) . " de {$checked} arquivos com erro de sintaxe\n";
}

echo "\n== TESTES ==\n";

$caseFiles = glob(TESTS_PATH . '/cases/*.php') ?: [];
sort($caseFiles);

foreach ($caseFiles as $caseFile) {
    TestRegistry::$currentFile = basename($caseFile);
    require_once $caseFile;
}

$passed = 0;
$failed = 0;

foreach (TestRegistry::$tests as [$name, $fn]) {
    try {
        $fn();
        $passed++;
        echo "  \u{2713} {$name}\n";
    } catch (AssertionFailed $e) {
        $failed++;
        echo "  \u{2717} {$name}\n      {$e->getMessage()}\n";
    } catch (Throwable $e) {
        $failed++;
        echo "  \u{2717} {$name}\n      ERRO INESPERADO: " . get_class($e) . ': ' . $e->getMessage() . "\n";
    }
}

echo "\n";
echo "Resultado: {$passed} passou(ram), {$failed} falhou(ram), " . count($lintErrors) . " erro(s) de lint\n\n";

exit(($failed > 0 || $lintErrors !== []) ? 1 : 0);
