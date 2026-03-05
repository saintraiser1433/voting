// Offline voting helper shared by ballot.php and department_ballot.php
(function () {
    var STORAGE_KEY = 'pending_vote';
    var pingIntervalMs = 10000;
    var syncUrl = (window.__syncUrl || '').replace(/\/+$/, ''); // trim trailing slash

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

    function showSyncPrompt() {
        if (typeof swal === 'function' && hasPending()) {
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

        var raw = localStorage.getItem(STORAGE_KEY);
        var payload;
        try {
            payload = JSON.parse(raw);
        } catch (e) {
            clearPending();
            return;
        }

        fetch(syncUrl + '/ajax/sync_offline_vote.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(function (res) { return res.json(); })
            .then(function (resp) {
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

