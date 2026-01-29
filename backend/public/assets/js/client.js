/**
 * Client module - JS for clients index, create, edit
 * 1st feature: live search on index (fetch API, update table without reload)
 */
(function() {
    'use strict';

    var DEBOUNCE_MS = 350;

    function escapeHtml(text) {
        if (text == null || text === '') return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function getInitials(client) {
        var fn = (client.first_name || '').trim();
        var ln = (client.last_name || '').trim();
        if (fn && ln) {
            return (fn.charAt(0) + ln.charAt(0)).toUpperCase();
        }
        var full = (client.full_name || '').trim();
        return full.length >= 2 ? full.substring(0, 2).toUpperCase() : (full.charAt(0) || '').toUpperCase();
    }

    function hasNotes(client) {
        var n = (client.notes || '').trim();
        return n.length > 0;
    }

    function buildRow(client, baseUrl) {
        var id = client.id;
        var fullName = escapeHtml(client.full_name || '');
        var email = escapeHtml(client.email || '');
        var phone = escapeHtml(client.phone || '') || '—';
        var initials = escapeHtml(getInitials(client));
        var notesHtml = hasNotes(client)
            ? '<span class="inline-flex items-center gap-2 text-sm text-green-700"><i class="bi bi-file-earmark-check text-green-600"></i>Has notes</span>'
            : '<span class="inline-flex items-center gap-2 text-sm text-gray-400"><i class="bi bi-file-earmark text-gray-400"></i>No notes</span>';
        var editUrl = baseUrl + '/clients/' + id + '/edit';
        var showUrl = baseUrl + '/clients/' + id;

        return '<tr class="border-b border-gray-100 hover:bg-gray-50/80 transition">' +
            '<td class="py-3 px-4">' +
            '<div class="flex items-center gap-3">' +
            '<div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-medium">' + initials + '</div>' +
            '<span class="text-sm font-medium text-gray-800">' + fullName + '</span>' +
            '</div></td>' +
            '<td class="py-3 px-4"><span class="inline-flex items-center gap-2 text-sm text-gray-600"><i class="bi bi-envelope text-gray-400"></i>' + email + '</span></td>' +
            '<td class="py-3 px-4"><span class="inline-flex items-center gap-2 text-sm text-gray-600"><i class="bi bi-telephone text-gray-400"></i>' + phone + '</span></td>' +
            '<td class="py-3 px-4">' + notesHtml + '</td>' +
            '<td class="py-3 px-4">' +
            '<div class="relative inline-block group">' +
            '<button type="button" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/20" aria-label="Actions" onclick="this.nextElementSibling.classList.toggle(\'hidden\')">' +
            '<i class="bi bi-three-dots-vertical text-lg"></i></button>' +
            '<div class="client-actions-menu hidden absolute right-0 top-full mt-1 py-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 z-10">' +
            '<a href="' + escapeHtml(editUrl) + '" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg"><i class="bi bi-pencil"></i>Edit</a>' +
            '<a href="' + escapeHtml(showUrl) + '" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-b-lg"><i class="bi bi-receipt"></i>Show Quote</a>' +
            '</div></div></td></tr>';
    }

    function emptyRow() {
        return '<tr><td colspan="5" class="py-12 text-center text-gray-500">' +
            '<i class="bi bi-inbox text-4xl block mb-2 text-gray-300"></i>No contacts found</td></tr>';
    }

    function renderTableBody(clients, baseUrl) {
        var tbody = document.getElementById('clientsTableBody');
        var countEl = document.getElementById('clientsCount');
        if (!tbody) return;

        if (!clients || clients.length === 0) {
            tbody.innerHTML = emptyRow();
        } else {
            tbody.innerHTML = clients.map(function(c) { return buildRow(c, baseUrl); }).join('');
        }

        if (countEl) {
            var n = clients ? clients.length : 0;
            countEl.textContent = n + (n === 1 ? ' contact registered' : ' contacts registered');
        }
    }

    function runSearch(apiUrl, baseUrl, term) {
        var url = apiUrl + (term ? '?search=' + encodeURIComponent(term) : '');
        fetch(url, { credentials: 'same-origin', method: 'GET' })
            .then(function(res) {
                if (!res.ok) {
                    throw new Error('API ' + res.status);
                }
                var contentType = res.headers.get('Content-Type') || '';
                if (contentType.indexOf('application/json') === -1) {
                    throw new Error('Response is not JSON');
                }
                return res.json();
            })
            .then(function(json) {
                var list = (json && json.data) ? json.data : [];
                renderTableBody(list, baseUrl);
            })
            .catch(function(err) {
                if (typeof console !== 'undefined' && console.error) {
                    console.error('Client search error:', err);
                }
                renderTableBody([], baseUrl);
            });
    }

    function initIndexSearch() {
        var wrap = document.getElementById('clientListWrap');
        var input = document.getElementById('clientSearchInput');
        if (!wrap || !input) return;

        var apiUrl = (wrap.getAttribute('data-api-url') || '').replace(/\/$/, '');
        var baseUrl = (wrap.getAttribute('data-base-url') || '').replace(/\/$/, '');
        if (!apiUrl) return;

        var timer = null;
        input.addEventListener('input', function() {
            clearTimeout(timer);
            var term = (input.value || '').trim();
            timer = setTimeout(function() {
                runSearch(apiUrl, baseUrl, term);
            }, DEBOUNCE_MS);
        });

        input.addEventListener('search', function() {
            clearTimeout(timer);
            var term = (input.value || '').trim();
            runSearch(apiUrl, baseUrl, term);
        });

        var form = document.getElementById('clientSearchForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                clearTimeout(timer);
                var term = (input.value || '').trim();
                runSearch(apiUrl, baseUrl, term);
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
        initIndexSearch();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
