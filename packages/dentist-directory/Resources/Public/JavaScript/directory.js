/**
 * Dentist Directory — Front-end JavaScript
 *
 * Features:
 *  - Lazy-load OpenStreetMap tiles for dentist detail pages
 *  - Auto-submit filter form on select change
 */
(function () {
    'use strict';

    /* ── Map initialisation ─────────────────────────────────────────────────── */
    function initMaps() {
        var mapEls = document.querySelectorAll('.dd-map[data-lat][data-lng]');

        if (!mapEls.length) { return; }

        // Lazy-load Leaflet from a CDN only when a map is present
        function loadLeaflet(cb) {
            if (window.L) { cb(); return; }

            var css = document.createElement('link');
            css.rel  = 'stylesheet';
            css.href = 'https://unpkg.com/leaflet@1.9/dist/leaflet.css';
            document.head.appendChild(css);

            var js   = document.createElement('script');
            js.src   = 'https://unpkg.com/leaflet@1.9/dist/leaflet.js';
            js.onload = cb;
            document.head.appendChild(js);
        }

        loadLeaflet(function () {
            mapEls.forEach(function (el) {
                var lat   = parseFloat(el.dataset.lat);
                var lng   = parseFloat(el.dataset.lng);
                var label = el.dataset.label || '';

                if (isNaN(lat) || isNaN(lng)) { return; }

                el.innerHTML = '';   // clear placeholder text
                var map = window.L.map(el).setView([lat, lng], 15);

                window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19
                }).addTo(map);

                window.L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(label)
                    .openPopup();
            });
        });
    }

    /* ── Filter form auto-submit ────────────────────────────────────────────── */
    function initFilters() {
        var selects = document.querySelectorAll('.dd-filters__form select');

        selects.forEach(function (sel) {
            sel.addEventListener('change', function () {
                sel.closest('form').submit();
            });
        });
    }

    /* ── Boot ───────────────────────────────────────────────────────────────── */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initMaps();
            initFilters();
        });
    } else {
        initMaps();
        initFilters();
    }
}());
