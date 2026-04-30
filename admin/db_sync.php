<?php
include '../connection.php';
require_once __DIR__ . '/../includes/db_sync_lib.php';

if (!isset($_SESSION['at'])) {
    header('Location:logout.php');
    exit;
}

db_sync_ensure_app_settings($conn);

$remoteUrl = db_sync_get_setting($conn, 'db_sync_remote_url', '');
if ($remoteUrl === '') {
    $remoteUrl = db_sync_get_setting($conn, 'ngrok_sync_url', '');
}
$apiKeySet = db_sync_get_setting($conn, 'db_sync_api_key', '') !== '';
$allowVoter = db_sync_get_setting($conn, 'allow_voter_db_pull', '0') === '1';
$insecureSsl = db_sync_get_setting($conn, 'db_sync_insecure_ssl', '0') === '1';
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'nav/header.php'; ?>
<body>
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring"><div class="frame"></div></div>
            </div>
        </div>
    </div>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php include 'nav/topbar.php'; ?>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include 'nav/sidebar.php'; ?>
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-header">
                                        <div class="row align-items-end">
                                            <div class="col-lg-12">
                                                <div class="page-header-title">
                                                    <div class="d-inline">
                                                        <h4>Database sync (ngrok → localhost)</h4>
                                                        <span>Pull a copy of the voting database from your public URL into this machine.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="alert alert-warning">
                                                    <strong>Warning:</strong> Pulling will <strong>delete and replace</strong> matching tables on this database (except the <code>admin</code> table is never imported).
                                                    Uploaded images are not copied—only database rows. Use the same API key on <strong>both</strong> servers (remote must allow export).
                                                    If Pull fails with an SSL certificate error on Windows/WAMP, enable <strong>Skip SSL verification</strong> below or set <code>curl.cainfo</code> in <code>php.ini</code> to Mozilla’s CA bundle (<code>cacert.pem</code>).
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>1. Remote server (ngrok)</h5>
                                                        <span>On the machine exposed via ngrok, open this page and set the <strong>same</strong> sync API key, then save.</span>
                                                    </div>
                                                    <div class="card-block">
                                                        <form method="POST" action="ajax/save_db_sync_settings.php">
                                                            <div class="form-group">
                                                                <label>Remote base URL</label>
                                                                <input type="url" class="form-control" name="db_sync_remote_url"
                                                                    placeholder="https://xxxx.ngrok-free.app/voting"
                                                                    value="<?php echo htmlspecialchars($remoteUrl); ?>">
                                                                <small class="text-muted">Public URL of this app (same as offline sync URL). Used when you click Pull below.</small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Sync API key</label>
                                                                <input type="password" class="form-control" name="db_sync_api_key"
                                                                    placeholder="<?php echo $apiKeySet ? '•••••••• (leave blank to keep current)' : 'Choose a strong secret'; ?>"
                                                                    autocomplete="new-password">
                                                                <small class="text-muted">Must match <code>db_sync_api_key</code> stored in <code>app_settings</code> on the <strong>remote</strong> server (set it there via this same form).</small>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" class="form-check-input" name="db_sync_insecure_ssl" id="db_sync_insecure_ssl" value="1" <?php echo $insecureSsl ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="db_sync_insecure_ssl">Skip SSL certificate verification (development / ngrok)</label>
                                                            </div>
                                                            <small class="text-muted d-block mb-3">Turn on only when PHP reports “unable to get local issuer certificate.” Prefer fixing <code>php.ini</code> CA bundle for production.</small>
                                                            <div class="form-check mb-3">
                                                                <input type="checkbox" class="form-check-input" name="allow_voter_db_pull" id="allow_voter_db_pull" value="1" <?php echo $allowVoter ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="allow_voter_db_pull">Allow logged-in voters (localhost only) to run Pull from the voter home page</label>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save settings</button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="card mt-3">
                                                    <div class="card-header">
                                                        <h5>2. Pull from remote into this database</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <p class="text-muted small">Runs on the server. Requires PHP <code>curl</code>.</p>
                                                        <button type="button" class="btn btn-success" id="btnDbSyncPull">
                                                            <i class="fa fa-download"></i> Pull database now
                                                        </button>
                                                        <div id="dbSyncPullResult" class="mt-3 small"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'nav/script.php'; ?>
    <script>
        $(document).ready(function () {
            $('.theme-loader').fadeOut(200, function () { $(this).remove(); });
            $('#btnDbSyncPull').on('click', function () {
                var btn = $(this);
                btn.prop('disabled', true);
                $('#dbSyncPullResult').text('Pulling…');
                $.ajax({
                    url: 'ajax/db_sync_pull.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {}
                }).done(function (r) {
                    if (r.ok) {
                        $('#dbSyncPullResult').html('<span class="text-success">' + (r.message || 'Done') + '</span>');
                        if (typeof swal === 'function') {
                            swal({ title: r.message || 'Sync complete', icon: 'success', button: 'OK' });
                        }
                    } else {
                        $('#dbSyncPullResult').html('<span class="text-danger">' + (r.message || 'Failed') + '</span>');
                        if (typeof swal === 'function') {
                            swal({ title: r.message || 'Failed', icon: 'error', button: 'OK' });
                        }
                    }
                }).fail(function () {
                    $('#dbSyncPullResult').html('<span class="text-danger">Request failed</span>');
                }).always(function () {
                    btn.prop('disabled', false);
                });
            });
        });
    </script>
    <?php
    if (isset($_SESSION['response']) && $_SESSION['response'] !== '') {
        $m = $_SESSION['response'];
        $t = $_SESSION['type'] ?? 'success';
        unset($_SESSION['response'], $_SESSION['type']);
        echo '<script>$(function(){ swal({ title: ' . json_encode($m) . ', icon: ' . json_encode($t) . ', button: "OK" }); });</script>';
    }
    ?>
</body>
</html>
