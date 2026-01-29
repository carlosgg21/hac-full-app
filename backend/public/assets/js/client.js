/**
 * Client module - JS for clients index, create, edit
 * Filter by Name column in HTML (no API, no URL). Search ignores accents.
 */
(function() {
    'use strict';

    function stripAccents(str) {
        if (typeof str !== 'string') return '';
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function runFilter() {
        var input = document.getElementById('clientSearchInput');
        var tbody = document.getElementById('clientsTableBody');
        var countEl = document.getElementById('clientsCount');
        var emptyRow = document.getElementById('clientListEmptyRow');
        if (!input || !tbody) return;

        var term = (input.value || '').trim();
        var termNorm = term === '' ? '' : stripAccents(term).toLowerCase();
        var rows = tbody.querySelectorAll('tr[data-client-name]');
        var visibleCount = 0;

        for (var i = 0; i < rows.length; i++) {
            var name = (rows[i].getAttribute('data-client-name') || '');
            var nameNorm = stripAccents(name).toLowerCase();
            var show = term === '' || nameNorm.indexOf(termNorm) !== -1;
            rows[i].style.display = show ? '' : 'none';
            if (show) visibleCount++;
        }

        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 ? '' : 'none';
        }

        if (countEl) {
            countEl.textContent = visibleCount === 0
                ? 'No contacts found'
                : visibleCount + (visibleCount === 1 ? ' contact registered' : ' contacts registered');
        }
    }

    function initIndexFilter() {
        var input = document.getElementById('clientSearchInput');
        var form = document.getElementById('clientSearchForm');
        if (!input) return;

        input.addEventListener('input', runFilter);
        input.addEventListener('search', runFilter);

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                runFilter();
            });
        }
    }

    function initActionsMenu() {
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative.inline-block.group')) {
                document.querySelectorAll('.client-actions-menu').forEach(function(menu) {
                    menu.classList.add('hidden');
                });
            }
        });
    }

    function init() {
        initActionsMenu();
        initIndexFilter();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
