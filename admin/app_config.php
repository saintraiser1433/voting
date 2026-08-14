<?php
include '../connection.php';

// Guard: follow the same admin session check used by other admin pages (e.g. database.php)
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
    exit;
}

$syncUrl = '';
// Ensure app_settings table exists so we can read/write config
$resSync = $conn->query("SHOW TABLES LIKE 'app_settings'");
if (!$resSync || $resSync->num_rows === 0) {
    $conn->query("CREATE TABLE IF NOT EXISTS `app_settings` (
        `setting_key`   VARCHAR(100) NOT NULL PRIMARY KEY,
        `setting_value` TEXT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

$cfgRes = $conn->query("SELECT setting_value FROM app_settings WHERE setting_key='ngrok_sync_url' LIMIT 1");
if ($cfgRes && $cfgRes->num_rows > 0) {
    $cfgRow = $cfgRes->fetch_assoc();
    $syncUrl = trim($cfgRow['setting_value'] ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>

<body>
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring">
                    <div class="frame"></div>
                </div>
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
                                                        <h4>Application Configuration</h4>
                                                        <span>Configure ngrok sync target URL for offline voting. To copy the live database to localhost, use <a href="db_sync.php">Database sync</a>.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Offline Voting Sync URL</h5>
                                                        <span>Enter the public URL exposed by ngrok for this voting system.</span>
                                                    </div>
                                                    <div class="card-block">
                                                        <form method="POST" action="ajax/save_ngrok_url.php">
                                                            <div class="form-group">
                                                                <label for="ngrok_sync_url" class="col-form-label">Ngrok Sync URL</label>
                                                                <input type="text" name="ngrok_sync_url" id="ngrok_sync_url"
                                                                    class="form-control"
                                                                    placeholder="https://your-id.ngrok.io/voting"
                                                                    value="<?php echo htmlspecialchars($syncUrl); ?>">
                                                                <small class="form-text text-muted">
                                                                    Example: https://abc123.ngrok.io/voting (must point to this system's root URL).
                                                                </small>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fa fa-save"></i> Save
                                                            </button>
                                                        </form>
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
            $('.theme-loader').fadeOut(200, function () {
                $(this).remove();
            });
        });
    </script>
</body>

</html>

