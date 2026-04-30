<?php
/**
 * Voter UI: pull DB from remote (ngrok) into local MySQL — only if admin enabled allow_voter_db_pull.
 * POSTs to ajax/db_sync_pull.php (localhost / private IP only).
 */
if (!isset($conn)) {
    return;
}
$voterPullEnabled = false;
$resSync = $conn->query("SHOW TABLES LIKE 'app_settings'");
if ($resSync && $resSync->num_rows > 0) {
    $r = $conn->query("SELECT setting_value FROM app_settings WHERE setting_key='allow_voter_db_pull' LIMIT 1");
    if ($r && $r->num_rows > 0) {
        $row = $r->fetch_assoc();
        $voterPullEnabled = trim((string)($row['setting_value'] ?? '')) === '1';
    }
}
$hintUrl = '';
$r2 = $conn->query("SELECT setting_value FROM app_settings WHERE setting_key='ngrok_sync_url' LIMIT 1");
if ($r2 && $r2->num_rows > 0) {
    $row2 = $r2->fetch_assoc();
    $hintUrl = trim((string)($row2['setting_value'] ?? ''));
}
if ($hintUrl === '') {
    $r3 = $conn->query("SELECT setting_value FROM app_settings WHERE setting_key='db_sync_remote_url' LIMIT 1");
    if ($r3 && $r3->num_rows > 0) {
        $row3 = $r3->fetch_assoc();
        $hintUrl = trim((string)($row3['setting_value'] ?? ''));
    }
}
?>
<?php if ($voterPullEnabled) { ?>

<script>
(function () {
    var urlEl = document.getElementById('voterDbSyncUrl');
    try {
        var ls = localStorage.getItem('voter_offline_sync_url');
        if (ls && urlEl && !urlEl.value) {
            urlEl.value = ls;
        }
    } catch (e) { }
    var btn = document.getElementById('voterDbSyncPull');
    if (!btn) return;
    btn.addEventListener('click', function () {
        var keyEl = document.getElementById('voterDbSyncKey');
        var msg = document.getElementById('voterDbSyncMsg');
        var url = (urlEl && urlEl.value || '').trim();
        var key = (keyEl && keyEl.value || '').trim();
        if (!url || !key) {
            if (msg) { msg.textContent = 'Enter remote URL and API key.'; }
            return;
        }
        if (!confirm('This will replace the database on this machine. Continue?')) return;
        btn.disabled = true;
        if (msg) msg.textContent = 'Pulling…';
        var body = 'remote_url=' + encodeURIComponent(url) + '&sync_key=' + encodeURIComponent(key);
        fetch('ajax/db_sync_pull.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body,
            credentials: 'same-origin'
        }).then(function (r) { return r.json(); }).then(function (data) {
            if (data && data.ok) {
                if (msg) msg.textContent = '';
                if (typeof swal === 'function') {
                    swal({ title: data.message || 'Done', icon: 'success', button: 'OK' }).then(function () { location.reload(); });
                } else {
                    alert(data.message || 'Done');
                    location.reload();
                }
            } else {
                if (msg) msg.textContent = (data && data.message) ? data.message : 'Failed';
                if (typeof swal === 'function') {
                    swal({ title: (data && data.message) || 'Failed', icon: 'error', button: 'OK' });
                }
            }
        }).catch(function () {
            if (msg) msg.textContent = 'Request failed';
        }).then(function () {
            btn.disabled = false;
        });
    });
})();
</script>
<?php } ?>
