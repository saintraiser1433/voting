<?php
include '../../connection.php';
include '../nav/header.php';

$acad = isset($_GET['acad']) ? (int) $_GET['acad'] : 0;
$admin_mode = isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === 'department' ? 'department' : 'general';

if ($acad <= 0) {
    echo '<p class="text-muted">Invalid academic year.</p>';
    exit;
}

if ($admin_mode === 'department') {
    // Department results: show by department
    $dept_id = isset($_GET['dept_id']) ? (int) $_GET['dept_id'] : 0;
    if ($dept_id <= 0) {
        $dr = $conn->query("SELECT department_id FROM departments WHERE status = 1 ORDER BY department_name ASC LIMIT 1");
        if ($dr && $dr->num_rows > 0) {
            $dept_id = (int) $dr->fetch_assoc()['department_id'];
        }
    }
    $type_esc = $conn->real_escape_string($admin_mode);
    $et = $conn->query("SELECT id, is_finished FROM election_title WHERE acad_id = '$acad' AND election_type = 'department' LIMIT 1");
    if (!$et && strpos($conn->error ?? '', 'Unknown column') !== false) {
        $et = null;
    }
    $election_row = ($et && $et->num_rows > 0) ? $et->fetch_assoc() : null;
    $is_finished = $election_row && isset($election_row['is_finished']) && (int)$election_row['is_finished'] === 1;

    $deptRes = $conn->query("SELECT department_id, department_name FROM departments WHERE status = 1 ORDER BY department_name ASC");
    ?>
    <?php if ($deptRes && $deptRes->num_rows > 0) { ?>
    <div class="row mb-3">
        <div class="col-md-4">
            <label><strong>Department:</strong></label>
            <select id="deptSelectResult" class="form-control">
                <?php while ($d = $deptRes->fetch_assoc()) {
                    $did = (int) $d['department_id'];
                    ?>
                    <option value="<?php echo $did; ?>" <?php echo $did === $dept_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['department_name']); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <script>
    document.getElementById('deptSelectResult').addEventListener('change', function() {
        var url = new URL(window.location.href);
        url.searchParams.set('dept_id', this.value);
        url.searchParams.set('acad', '<?php echo $acad; ?>');
        window.location.href = url.toString();
    });
    </script>
    <?php } ?>

    <?php
    $date_start = isset($election_row['date_start']) ? $election_row['date_start'] : null;
    $date_end = isset($election_row['date_end']) ? $election_row['date_end'] : null;
    $label = 'Department voting';
    $running_time_id = 'dept-result';
    include '../includes/election_running_time.php';
    ?>
    <?php
    if ($is_finished && $dept_id > 0) {
        $wSql = "SELECT CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname, dp.description,
            (SELECT COUNT(*) FROM department_vote dvo WHERE dvo.candidate_id = dc.dc_id AND dvo.acad_id = dc.acad_id AND dvo.department_id = dc.department_id) AS totalvote,
            dp.max_vote, dc.img
            FROM dept_candidate dc
            INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id
            INNER JOIN dept_position dp ON dc.pos_id = dp.dp_id AND dc.acad_id = dp.acad_id
            WHERE dc.acad_id = '$acad' AND dc.department_id = '$dept_id'
            ORDER BY dp.priority ASC, totalvote DESC";
        $wRes = $conn->query($wSql);
        if ($wRes && $wRes->num_rows > 0) { ?>
    <button type="button" class="btn btn-primary" id="print" onclick="getprintDept()">PRINT</button>
    <div class="row users-card mt-2">
            <?php while ($r = $wRes->fetch_assoc()) { ?>
            <div class="col-lg-6 col-xl-3 col-md-6">
                <div class="card rounded-card user-card">
                    <div class="card-block">
                        <div class="img-hover">
                            <img class="img-fluid img-radius" src="../<?php echo htmlspecialchars($r['img']); ?>" alt="">
                        </div>
                        <div class="user-content">
                            <h4><?php echo htmlspecialchars($r['fname']); ?></h4>
                            <p class="m-b-0 text-muted text-capitalize">VOTES - <?php echo (int)$r['totalvote']; ?></p>
                            <p class="m-b-0 text-muted text-capitalize"><?php echo htmlspecialchars($r['description']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
    </div>
        <?php } else { ?>
    <p class="text-muted">No department votes recorded for this department.</p>
        <?php }
    } else { ?>
    <div class="d-flex flex-column justify-content-center">
        <h1 class="d-flex justify-content-center">Department voting is ongoing</h1>
        <button class="btn btn-danger mt-2 mx-auto" id="endelection">END ELECTION</button>
    </div>
    <?php } ?>

    <script>
    function getprintDept() {
        window.location.href = "resultm_dept.php?acad=<?php echo $acad; ?>&dept_id=<?php echo $dept_id; ?>";
    }
    $('#endelection').on('click', function (e) {
        e.preventDefault();
        swal({ title: "Are you sure?", text: "This will end the department election.", icon: "warning", buttons: true, dangerMode: true })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({ url: "ajax/updateSettings.php", method: 'POST', data: { myids: '<?php echo $acad; ?>' },
                        success: function () {
                            swal("Department vote is closed", { icon: "success" }).then(function () { location.reload(); });
                        }
                    });
                }
            });
    });
    </script>
    <?php
    exit;
}

// General results – load election row for running time
$etGen = $conn->query("SELECT date_start, date_end FROM election_title WHERE acad_id = '$acad' AND (election_type = 'general' OR election_type IS NULL) LIMIT 1");
$election_gen = ($etGen && $etGen->num_rows > 0) ? $etGen->fetch_assoc() : null;
$date_start = ($election_gen && !empty($election_gen['date_start'])) ? $election_gen['date_start'] : null;
$date_end = ($election_gen && !empty($election_gen['date_end'])) ? $election_gen['date_end'] : null;
$label = 'General voting';
$running_time_id = 'gen-result';
include '../includes/election_running_time.php';

$type_esc = $conn->real_escape_string($admin_mode);
$sqlt = "SELECT CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname, pos.description, vt.totalvote, pos.max_vote, c.img
    FROM candidate c
    INNER JOIN voters v ON c.stud_id = v.stud_id
    INNER JOIN partylist p ON c.p_id = p.p_id
    INNER JOIN position pos ON c.pos_id = pos.pos_id
    INNER JOIN election_title et ON et.acad_id = c.acad_id AND (et.election_type = '$type_esc' OR (et.election_type IS NULL AND '$type_esc' = 'general'))
    LEFT JOIN (SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote FROM vote GROUP BY candidate_id) vt ON vt.candidate_id = c.c_id
    WHERE c.acad_id = $acad AND et.is_finished = 1
    AND (SELECT COUNT(*) FROM candidate c2
         LEFT JOIN (SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote FROM vote GROUP BY candidate_id) vt2 ON vt2.candidate_id = c2.c_id
         WHERE c2.pos_id = c.pos_id AND vt2.totalvote > vt.totalvote) < pos.max_vote
    ORDER BY pos.priority ASC, vt.totalvote DESC";
$rs = $conn->query($sqlt);
if (!$rs && strpos($conn->error ?? '', 'Unknown column') !== false) {
    $sqlt = "SELECT CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname, pos.description, vt.totalvote, pos.max_vote, c.img
        FROM candidate c INNER JOIN voters v ON c.stud_id = v.stud_id INNER JOIN partylist p ON c.p_id = p.p_id
        INNER JOIN position pos ON c.pos_id = pos.pos_id INNER JOIN election_title et ON et.acad_id = c.acad_id
        LEFT JOIN (SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote FROM vote GROUP BY candidate_id) vt ON vt.candidate_id = c.c_id
        WHERE c.acad_id = $acad AND et.is_finished = 1
        AND (SELECT COUNT(*) FROM candidate c2 LEFT JOIN (SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote FROM vote GROUP BY candidate_id) vt2 ON vt2.candidate_id = c2.c_id WHERE c2.pos_id = c.pos_id AND vt2.totalvote > vt.totalvote) < pos.max_vote
        ORDER BY pos.priority ASC, vt.totalvote DESC";
    $rs = $conn->query($sqlt);
}
if ($rs && $rs->num_rows > 0) { ?>
<button type="button" class="btn btn-primary" id="print" onclick="getprint()">PRINT</button>
<div class="row users-card mt-2">
    <?php foreach ($rs as $row) { ?>
    <div class="col-lg-6 col-xl-3 col-md-6">
        <div class="card rounded-card user-card">
            <div class="card-block">
                <div class="img-hover">
                    <img class="img-fluid img-radius" src="../<?php echo $row['img']; ?>" alt="round-img">
                </div>
                <div class="user-content">
                    <h4 class=""><?php echo $row['fname']; ?></h4>
                    <p class="m-b-0 text-muted text-capitalize">VOTES - <?php echo $row['totalvote']; ?></p>
                    <p class="m-b-0 text-muted text-capitalize"><?php echo $row['description']; ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php } else { ?>
<div class="d-flex flex-column justify-content-center ">
    <h1 class="d-flex justify-content-center ">Voting is ongoing</h1>
    <button class="btn btn-danger mt-2 mx-auto" id="endelection">END ELECTION</button>
</div>
<?php } ?>

<script>
function getprint() {
    window.location.href = "resultm.php?acad=<?php echo $acad; ?>";
}
$('#endelection').on('click', function (e) {
    e.preventDefault();
    swal({ title: "Are you sure?", text: "Once deleted, you will not be able to recover this imaginary file!", icon: "warning", buttons: true, dangerMode: true })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({ url: "ajax/updateSettings.php", method: 'POST', data: { myids: '<?php echo $acad; ?>' },
                    success: function (html) {
                        swal("Vote is closed", { icon: "success" }).then((value) => { location.reload(); });
                    }
                });
            }
        });
});
</script>
