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
    '@fullcalendar/common' => [
        'version' => '5.11.5',
    ],
    '@fullcalendar/core' => [
        'version' => '5.11.5',
    ],
    '@fullcalendar/core/locales/ca' => [
        'version' => '5.11.5',
    ],
    '@fullcalendar/daygrid' => [
        'version' => '5.11.5',
    ],
    '@fullcalendar/google-calendar' => [
        'version' => '5.11.5',
    ],
    '@fullcalendar/interaction' => [
        'version' => '5.11.5',
    ],
    '@fullcalendar/list' => [
        'version' => '5.11.5',
    ],
    '@fullcalendar/timegrid' => [
        'version' => '5.11.5',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.12',
    ],
    '@kurkle/color' => [
        'version' => '0.3.4',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@toyokumo/fos-router' => [
        'version' => '1.0.5',
    ],
    'axios' => [
        'version' => '1.7.9',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'chart.js' => [
        'version' => '4.4.7',
    ],
    'fullcalendar' => [
        'version' => '5.11.5',
    ],
    'pdfjs-dist/build/pdf.min.mjs' => [
        'version' => '4.10.38',
    ],
    'pdfjs-dist/build/pdf.worker.min.mjs' => [
        'version' => '4.10.38',
    ],
    'preact' => [
        'version' => '10.12.1',
    ],
    'preact/compat' => [
        'version' => '10.12.1',
    ],
    'preact/hooks' => [
        'version' => '10.12.1',
    ],
    'stimulus-use' => [
        'version' => '0.52.3',
    ],
    'tslib' => [
        'version' => '2.8.1',
    ],
];
