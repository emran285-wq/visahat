<?php
declare(strict_types=1);

function load_environment(string $file): void {
	if (!is_readable($file)) return;

	foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
		$line = trim($line);
		if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
		[$name, $value] = explode('=', $line, 2);
		$name = trim($name);
		$value = trim($value);
		if ($name === '' || getenv($name) !== false) continue;
		$value = trim($value, "\"'");
		putenv("{$name}={$value}");
		$_ENV[$name] = $value;
	}
}

function env(string $name, string $default = ''): string {
	$value = getenv($name);
	return $value === false || $value === '' ? $default : $value;
}

$externalEnvironment = getenv('VISAHAT_ENV_FILE') ?: dirname(__DIR__) . '/.env';
load_environment($externalEnvironment);
load_environment(__DIR__ . '/.env');

const SITE_NAME = 'Visa Hat';
const SITE_TAGLINE = 'Visa & Immigration Consultants';
define('SITE_URL', rtrim(env('SITE_URL', 'http://127.0.0.1:8080'), '/'));
define('FORM_MODE', env('FORM_MODE', 'demo'));
define('DB_HOST', env('DB_HOST'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASSWORD'));
define('MAIL_FROM', env('MAIL_FROM'));
define('MAIL_TO', env('MAIL_TO'));

function e(mixed $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function url(string $path = ''): string { return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/'); }
function service_url(string $slug): string { return '/visa/' . rawurlencode($slug); }