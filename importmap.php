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
        'version' => '8.0.13',
    ],
    '@kurkle/color' => [
        'version' => '0.4.0',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    '@toyokumo/fos-router' => [
        'version' => '1.0.5',
    ],
    'axios' => [
        'version' => '1.11.0',
    ],
    'bootstrap' => [
        'version' => '5.3.7',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.7',
        'type' => 'css',
    ],
    'chart.js' => [
        'version' => '4.5.0',
    ],
    'fullcalendar' => [
        'version' => '5.11.5',
    ],
    'pdfjs-dist/build/pdf.min.mjs' => [
        'version' => '5.4.54',
    ],
    'pdfjs-dist/build/pdf.worker.min.mjs' => [
        'version' => '5.4.54',
    ],
    'preact' => [
        'version' => '10.27.1',
    ],
    'preact/compat' => [
        'version' => '10.27.1',
    ],
    'preact/hooks' => [
        'version' => '10.27.1',
    ],
    'stimulus-use' => [
        'version' => '0.52.3',
    ],
    'tslib' => [
        'version' => '2.8.1',
    ],
];
