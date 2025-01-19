<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    'chart.js' => [
        'version' => '4.4.7',
    ],
    'pdfjs-dist/build/pdf.min.mjs' => [
        'version' => '4.10.38',
    ],
    'pdfjs-dist/build/pdf.worker.min.mjs' => [
        'version' => '4.10.38',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.12',
    ],
    'stimulus-use' => [
        'version' => '0.52.3',
    ],
    '@kurkle/color' => [
        'version' => '0.3.4',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'axios' => [
        'version' => '1.7.9',
    ],
    'preact' => [
        'version' => '10.25.4',
    ],
    'preact/compat' => [
        'version' => '10.25.4',
    ],
    'preact/hooks' => [
        'version' => '10.25.4',
    ],
    '@fullcalendar/core' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/core/index.js' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/core/internal.js' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/core/preact.js' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/daygrid' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/daygrid/internal.js' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/timegrid' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/timegrid/internal.js' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/list' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/list/internal.js' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/interaction' => [
        'version' => '6.1.15',
    ],
    '@fullcalendar/google-calendar' => [
        'version' => '6.1.15',
    ],
];
