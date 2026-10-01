<?php
declare(strict_types=1);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'; $file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) return false;
$routes = ['/' => 'index.php', '/about' => 'about.php', '/services' => 'services.php', '/visa' => 'visa.php', '/visa/destinations' => 'destinations.php', '/contact' => 'contact.php', '/consultation' => 'consultation.php', '/search' => 'search.php', '/privacy' => 'privacy.php', '/sitemap.xml' => 'sitemap.php'];
if (isset($routes[rtrim($path, '/') ?: '/'])) { require __DIR__ . '/' . $routes[rtrim($path, '/') ?: '/']; return true; }
if (preg_match('#^/visa/destinations/([a-z0-9-]+)/?$#', $path, $match)) { $_GET['destination'] = $match[1]; require __DIR__ . '/destination.php'; return true; }
if (preg_match('#^/visa/([a-z0-9-]+)/?$#', $path, $match)) { $_GET['slug'] = $match[1]; require __DIR__ . '/service.php'; return true; }
http_response_code(404); require __DIR__ . '/404.php'; return true;