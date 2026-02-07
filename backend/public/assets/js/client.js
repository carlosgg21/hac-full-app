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

    function getApiBase() {
        var wrap = document.getElementById('clientListWrap');
        return (wrap && wrap.getAttribute('data-api-base')) ? wrap.getAttribute('data-api-base') : '/backend/api';
    }

    function initNotesModal() {
        var modal = document.getElementById('clientNotesModal');
        var textarea = document.getElementById('clientNotesTextarea');
        var saveBtn = document.getElementById('clientNotesSave');
        var clearBtn = document.getElementById('clientNotesClear');
        var cancelBtn = document.getElementById('clientNotesCancel');
        if (!modal || !textarea || !saveBtn || !clearBtn || !cancelBtn) return;

        var currentClientId = null;
        var currentRow = null;

        function closeModal() {
            currentClientId = null;
            currentRow = null;
            modal.close();
        }

        function openModal(clientId, notesText, row) {
            currentClientId = clientId;
            currentRow = row;
            textarea.value = notesText || '';
            modal.showModal();
        }

        function updateRowNotes(row, newNotes) {
            if (!row) return;
            var notesSpan = row.querySelector('.client-notes-text');
            var iconWrap = row.querySelector('.client-notes-cell i');
            if (notesSpan) notesSpan.textContent = newNotes || '';
            if (iconWrap) {
                iconWrap.className = (newNotes && newNotes.trim()) ? 'bi bi-file-earmark-check text-green-600 text-lg' : 'bi bi-file-earmark text-gray-400 text-lg';
            }
        }

        document.getElementById('clientListWrap').addEventListener('click', function(e) {
            var cell = e.target.closest('.client-notes-cell');
            if (!cell) return;
            e.preventDefault();
            var row = cell.closest('tr');
            var clientId = row ? row.getAttribute('data-client-id') : null;
            var notesSpan = row ? row.querySelector('.client-notes-text') : null;
            var notesText = notesSpan ? notesSpan.textContent : '';
            if (clientId) openModal(clientId, notesText, row);
        });

        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('cancel', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        function putNotes(id, notes, onSuccess) {
            var base = getApiBase();
            var xhr = new XMLHttpRequest();
            xhr.open('PUT', base + '/clients/' + id, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;
                if (xhr.status >= 200 && xhr.status < 300) {
                    if (onSuccess) onSuccess();
                } else {
                    if (window.appToast) appToast({ type: 'error', text: 'Failed to save notes. Please try again.' });
                }
            };
            xhr.onerror = function() { if (window.appToast) appToast({ type: 'error', text: 'Failed to save notes. Please try again.' }); };
            xhr.send(JSON.stringify({ notes: notes }));
        }

        saveBtn.addEventListener('click', function() {
            if (currentClientId == null || !currentRow) return;
            var value = (textarea.value || '').trim();
            putNotes(currentClientId, value, function() {
                updateRowNotes(currentRow, value);
                closeModal();
            });
        });

        clearBtn.addEventListener('click', function() {
            if (currentClientId == null || !currentRow) return;
            putNotes(currentClientId, '', function() {
                updateRowNotes(currentRow, '');
                closeModal();
            });
        });
    }

    function init() {
        initActionsMenu();
        initIndexFilter();
        initNotesModal();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
