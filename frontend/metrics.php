<?php
require __DIR__ . '/vendor/autoload.php';

use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Prometheus\RenderTextFormat;

$registry = new CollectorRegistry(new InMemory());
$counter = $registry->getOrRegisterCounter(
    'app',
    'http_requests_total',
    'Number of HTTP requests',
    ['method', 'endpoint']
);

$counter->inc(['GET', '/metrics']);

$renderer = new RenderTextFormat();
$result = $renderer->render($registry->getMetricFamilySamples());

header('Content-Type: ' . RenderTextFormat::MIME_TYPE);
echo $result;
