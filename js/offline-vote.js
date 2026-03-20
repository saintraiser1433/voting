// Offline voting helper shared by ballot.php and department_ballot.php
(function () {
    var STORAGE_KEY = 'pending_vote';
    var pingIntervalMs = 10000;
    var syncUrl = (window.__syncUrl || '').replace(/\/+$/, ''); // trim trailing slash

    function ensureSyncOverlay() {
        var existing = document.getElementById('sync-overlay-offline');
        if (existing) return existing;

        var overlay = document.createElement('div');
        overlay.id = 'sync-overlay-offline';
        overlay.style.position = 'fixed';
        overlay.style.left = '0';
        overlay.style.top = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.background = 'rgba(0,0,0,0.45)';
        overlay.style.zIndex = '999999';
        overlay.style.display = 'none';

        overlay.innerHTML =
            '<div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);' +
            'background:#fff;border-radius:8px;padding:18px 22px;' +
            'box-shadow:0 10px 30px rgba(0,0,0,0.25);text-align:center;min-width:260px;">' +
            '<div style="font-weight:700;margin-bottom:10px;">Syncing offline vote...</div>' +
            '<div style="display:flex;justify-content:center;">' +
            '<div class="spinner" style="width:22px;height:22px;border:3px solid #ccc;border-top-color:#28a745;border-radius:50%;animation:spin 0.8s linear infinite;"></div>' +
            '</div>' +
            '</div>' +
            '<style>@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}</style>';

        document.body.appendChild(overlay);
        return overlay;
    }

    function showSyncLoading() {
        var overlay = ensureSyncOverlay();
        overlay.style.display = 'block';
    }

    function hideSyncLoading() {
        var overlay = ensureSyncOverlay();
        overlay.style.display = 'none';
    }

    function hasPending() {
        try {
            return !!localStorage.getItem(STORAGE_KEY);
        } catch (e) {
            return false;
        }
    }

    function savePending(payload) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(payload));
        } catch (e) {
            console.error('Failed to save pending vote', e);
        }
    }

    function clearPending() {
        try {
            localStorage.removeItem(STORAGE_KEY);
        } catch (e) { }
    }

    function showOfflineBanner() {
        var el = document.getElementById('offline-banner');
        if (el) el.style.display = 'block';
    }

    function hideOfflineBanner() {
        var el = document.getElementById('offline-banner');
        if (el) el.style.display = 'none';
    }

    function getPendingPayload() {
        var raw = null;
        try {
            raw = localStorage.getItem(STORAGE_KEY);
        } catch (e) {
            return null;
        }
        if (!raw) return null;
        try {
            return JSON.parse(raw);
        } catch (e) {
            return null;
        }
    }

    function showSyncPrompt() {
        if (typeof swal === 'function' && hasPending()) {
            var pendingPayload = getPendingPayload();
            var currentMode = window.__mode || 'general';
            if (!pendingPayload || pendingPayload.mode !== currentMode) {
                return; // don't prompt for the wrong mode
            }
            swal({
                title: 'Offline vote stored',
                text: 'You have an unsynced vote. Do you want to sync it now?',
                icon: 'info',
                buttons: {
                    cancel: 'Later',
                    confirm: {
                        text: 'Sync Now',
                        value: true
                    }
                }
            }).then(function (ok) {
                if (ok) {
                    window.syncVote && window.syncVote();
                }
            });
        }
    }

    function collectVotesFromForm(form) {
        var formData = new FormData(form);
        var votes = {};

        formData.forEach(function (value, key) {
            if (key === 'voters1') {
                return;
            }
            if (!votes[key]) {
                votes[key] = value;
            } else if (Array.isArray(votes[key])) {
                votes[key].push(value);
            } else {
                votes[key] = [votes[key], value];
            }
        });

        return votes;
    }

    window.syncVote = function () {
        if (!hasPending()) return;
        if (!syncUrl) {
            swal && swal({ title: 'Sync URL not configured', icon: 'error', button: 'OK' });
            return;
        }
        if (!navigator.onLine) {
            swal && swal({ title: 'Still offline', text: 'Please connect to the internet first.', icon: 'warning', button: 'OK' });
            return;
        }

        var payload = getPendingPayload();
        if (!payload) {
            clearPending();
            return;
        }

        // Enforce mode safety (prevents syncing wrong pending vote)
        var currentMode = window.__mode || 'general';
        if (payload.mode !== currentMode) {
            swal && swal({ title: 'Wrong mode', text: 'You can only sync the offline vote for this page mode.', icon: 'warning', button: 'OK' });
            return;
        }

        // Loading overlay while network sync is running
        showSyncLoading();

        fetch(syncUrl + '/ajax/sync_offline_vote.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(function (res) { return res.json(); })
            .then(function (resp) {
                hideSyncLoading();
                if (resp.status === 'ok') {
                    clearPending();
                    swal && swal({ title: 'Vote synced successfully', icon: 'success', button: 'OK' });
                } else if (resp.status === 'already_voted') {
                    clearPending();
                    swal && swal({ title: 'You have already voted.', icon: 'error', button: 'OK' });
                } else {
                    var msg = resp.message || 'Failed to sync vote.';
                    swal && swal({ title: msg, icon: 'error', button: 'OK' });
                }
            })
            .catch(function () {
                hideSyncLoading();
                swal && swal({ title: 'Sync failed', text: 'Please try again later.', icon: 'error', button: 'OK' });
            });
    };

    function startPingLoop() {
        if (!syncUrl) return;
        setInterval(function () {
            if (!hasPending()) return;
            if (!navigator.onLine) return;

            fetch(syncUrl + '/ajax/check_ping.php?_=' + Date.now())
                .then(function (res) {
                    if (res.ok) {
                        showSyncPrompt();
                    }
                })
                .catch(function () { });
        }, pingIntervalMs);
    }

    function init() {
        if (typeof window.__offlineInitialized !== 'undefined') return;
        window.__offlineInitialized = true;

        if (!navigator.onLine) {
            showOfflineBanner();
        }

        window.addEventListener('online', function () {
            hideOfflineBanner();
        });
        window.addEventListener('offline', function () {
            showOfflineBanner();
        });

        var form = document.getElementById('ballotForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (navigator.onLine) {
                    return;
                }

                e.preventDefault();

                var votes = collectVotesFromForm(form);
                var payload = {
                    mode: window.__mode || 'general',
                    v_id: window.__voterId || 0,
                    acad_id: window.__acadId || 0,
                    dept_id: window.__deptId || 0,
                    votes: votes
                };

                savePending(payload);
                swal && swal({
                    title: 'Offline',
                    text: 'You are offline. Your vote has been saved on this device. It will be synced when you go online.',
                    icon: 'info',
                    button: 'OK'
                });
            });
        }

        startPingLoop();
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        init();
    } else {
        document.addEventListener('DOMContentLoaded', init);
    }
})();

