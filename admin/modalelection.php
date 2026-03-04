<?php
$acad = (int) $_SESSION['acad'];
$admin_mode = isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === 'department' ? 'department' : 'general';
$election_type_esc = $conn->real_escape_string($admin_mode);
// Form posts to save_election_settings.php which redirects back with message
$current_page = basename($_SERVER['PHP_SELF']);
$save_url = 'save_election_settings.php?return=' . urlencode($current_page);
?>

<div class="modal fade z-index-1" id="mytitleq" role="dialog" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-titles">Election Settings</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="location.reload();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo htmlspecialchars($save_url); ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php
                    // Load election settings for current responsibility (General or Department)
                    $sql = "SELECT * FROM election_title WHERE acad_id='$acad' AND (election_type = '$election_type_esc' OR (election_type IS NULL AND '$election_type_esc' = 'general')) LIMIT 1";
                    $rs1233 = $conn->query($sql);
                    if (!$rs1233 && strpos($conn->error, 'Unknown column') !== false) {
                        $rs1233 = $conn->query("SELECT * FROM election_title WHERE acad_id='$acad' LIMIT 1");
                    }
                    $rowww = ($rs1233 && $rs1233->num_rows > 0) ? $rs1233->fetch_assoc() : null;
                    ?>
                    <p class="text-muted small mb-2">Settings for <strong><?php echo $admin_mode === 'department' ? 'Department' : 'General'; ?> Voting</strong></p>
                    <div class="form-group">
                        <label class="col-form-label">Election title (per academic year):</label>
                        <input type="hidden" class="form-control" name="idhidden" value="<?php echo $rowww ? (int)$rowww['id'] : ''; ?>">
                        <input type="text" class="form-control" name="titles" value="<?php echo $rowww ? htmlspecialchars($rowww['title']) : ''; ?>" placeholder="e.g. SSC Election 2024-2025">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Start date & time (when voting opens):</label>
                        <input type="datetime-local" class="form-control" name="date_start" value="<?php
                            if ($rowww && !empty($rowww['date_start'])) {
                                echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($rowww['date_start'])));
                            }
                        ?>">
                        <small class="text-muted">Leave empty for no start limit.</small>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">End date & time (when voting closes):</label>
                        <input type="datetime-local" class="form-control" name="date_end" value="<?php
                            if ($rowww && !empty($rowww['date_end'])) {
                                echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($rowww['date_end'])));
                            }
                        ?>">
                        <small class="text-muted">Voting will auto-close after this time. Leave empty for manual close only.</small>
                    </div>

                </div>
                <div class="modal-footer" id="footersa">

                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="submitqwe"
                        id="submits">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }


</script>