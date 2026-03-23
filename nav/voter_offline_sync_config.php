<?php
/**
 * Voter-side "App config" for offline sync: ngrok/public base URL.
 * Saved only in browser localStorage (key voter_offline_sync_url).
 * If empty, js/offline-vote.js uses admin app_settings ngrok_sync_url (window.__syncUrl).
 */
if (!isset($conn)) {
    return;
}
$voterSyncHint = '';
$resSync = $conn->query("SHOW TABLES LIKE 'app_settings'");
if ($resSync && $resSync->num_rows > 0) {
    $cfgRes = $conn->query("SELECT setting_value FROM app_settings WHERE setting_key='ngrok_sync_url' LIMIT 1");
    if ($cfgRes && $cfgRes->num_rows > 0) {
        $row = $cfgRes->fetch_assoc();
        $voterSyncHint = isset($row['setting_value']) ? trim($row['setting_value']) : '';
    }
}
?>
<div class="card mb-3 border-info" id="voter-offline-sync-config-card">
    <div class="card-block py-3">
        <h6 class="mb-2"><i class="fa fa-cog text-info"></i> Offline sync URL (this device)</h6>
        <p class="text-muted small mb-2 mb-md-3">
            Same as <strong>Admin → App Config</strong>: the public address (e.g. ngrok) where offline votes are sent.
            What you save here is stored <strong>only in this browser</strong>. Leave empty to use the admin default below.
        </p>
        <div class="form-row align-items-end">
            <div class="col-md-9 mb-2 mb-md-0">
                <label class="small text-muted mb-1" for="voterOfflineSyncUrl">Sync target (base URL of voting app)</label>
                <input type="url" class="form-control form-control-sm" id="voterOfflineSyncUrl"
                    placeholder="https://xxxx.ngrok-free.app/voting"
                    autocomplete="off">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary btn-sm btn-block mb-1" id="voterOfflineSyncSave">
                    <i class="fa fa-save"></i> Save
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm btn-block" id="voterOfflineSyncClear">
                    Clear (use admin default)
                </button>
            </div>
        </div>
        <?php if ($voterSyncHint !== '') { ?>
            <p class="small text-muted mb-0 mt-2">
                <strong>Admin default:</strong> <code><?php echo htmlspecialchars($voterSyncHint); ?></code>
            </p>
        <?php } ?>
    </div>
</div>
<script>
(function () {
    var LS = 'voter_offline_sync_url';
    var def = <?php echo json_encode($voterSyncHint); ?>;
    function $(id) { return document.getElementById(id); }
    var inp = $('voterOfflineSyncUrl');
    var btnSave = $('voterOfflineSyncSave');
    var btnClear = $('voterOfflineSyncClear');
    if (!inp || !btnSave || !btnClear) return;
    try {
        var v = localStorage.getItem(LS);
        if (v) {
            inp.value = v;
        } else if (def) {
            inp.placeholder = def;
        }
    } catch (e) { }
    btnSave.addEventListener('click', function () {
        var url = (inp.value || '').trim().replace(/\/+$/, '');
        try {
            if (url) {
                localStorage.setItem(LS, url);
            } else {
                localStorage.removeItem(LS);
            }
        } catch (e) { }
        if (typeof swal === 'function') {
            swal({
                title: url ? 'Sync URL saved on this device' : 'Using admin default',
                icon: 'success',
                button: 'OK'
            });
        }
    });
    btnClear.addEventListener('click', function () {
        inp.value = '';
        try { localStorage.removeItem(LS); } catch (e) { }
        if (typeof swal === 'function') {
            swal({ title: 'Cleared. Admin default will be used when you sync.', icon: 'info', button: 'OK' });
        }
    });
})();

/**
 * Show "Sync Offline Vote" + this card only on local dev / LAN (localhost, loopback, private IPs).
 * Hidden on public hostnames (production, ngrok URL in the address bar, etc.).
 */
(function () {
    function isLocalhostOrPrivateIpHost() {
        var raw = (location.hostname || '').toLowerCase();
        var h = raw.replace(/^\[|\]$/g, '');
        if (h === 'localhost' || h === '::1') {
            return true;
        }
        if (!/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/.test(h)) {
            return false;
        }
        var p = h.split('.').map(function (x) { return parseInt(x, 10); });
        if (p.some(function (n) { return isNaN(n) || n > 255; })) {
            return false;
        }
        if (p[0] === 127) {
            return true;
        }
        if (p[0] === 10) {
            return true;
        }
        if (p[0] === 172 && p[1] >= 16 && p[1] <= 31) {
            return true;
        }
        if (p[0] === 192 && p[1] === 168) {
            return true;
        }
        if (p[0] === 169 && p[1] === 254) {
            return true;
        }
        return false;
    }
    function applyVoterOfflineSyncVisibility() {
        if (isLocalhostOrPrivateIpHost()) {
            return;
        }
        var wrap = document.getElementById('offline-sync-container');
        if (wrap) {
            wrap.style.display = 'none';
        }
        var card = document.getElementById('voter-offline-sync-config-card');
        if (card) {
            card.style.display = 'none';
        }
        var dbCard = document.getElementById('voter-db-sync-card');
        if (dbCard) {
            dbCard.style.display = 'none';
        }
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyVoterOfflineSyncVisibility);
    } else {
        applyVoterOfflineSyncVisibility();
    }
})();
</script>
