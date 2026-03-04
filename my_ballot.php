<?php
/**
 * View and print the voter's submitted ballot (general or department).
 * ?type=general or ?type=department. Requires login and that the voter has voted in that type.
 */
include 'connection.php';

$acad = isset($_SESSION['acad']) ? (int) $_SESSION['acad'] : 0;
$voter = isset($_SESSION['v_id']) ? (int) $_SESSION['v_id'] : 0;
$type = isset($_GET['type']) && $_GET['type'] === 'department' ? 'department' : 'general';

if (!$voter) {
    header('Location: index.php');
    exit;
}

$sel = $conn->query("SELECT description FROM acad_tbl WHERE acad_id = $acad");
$acad_row = $sel && $sel->num_rows > 0 ? $sel->fetch_assoc() : null;
$acad_desc = $acad_row ? $acad_row['description'] : '';

$title = $type === 'department' ? 'Department Election - My Ballot' : 'General Election - My Ballot';
$rows = [];
$election_title = '';

if ($type === 'general') {
    $chk = $conn->query("SELECT 1 FROM vote WHERE acad_id='$acad' AND voter_id='$voter' LIMIT 1");
    if (!$chk || $chk->num_rows === 0) {
        $_SESSION['response'] = 'You have not voted in the general election yet.';
        $_SESSION['type'] = 'warning';
        header('Location: home.php');
        exit;
    }
    $et = $conn->query("SELECT title FROM election_title WHERE acad_id='$acad' AND (election_type = 'general' OR election_type IS NULL) LIMIT 1");
    if ($et && $et->num_rows > 0) {
        $election_title = $et->fetch_assoc()['title'];
    }
    $sql = "SELECT pos.description AS position_name, v.lname, v.fname, v.mname, p.party_name
            FROM vote vt
            INNER JOIN candidate c ON vt.candidate_id = c.c_id AND vt.acad_id = c.acad_id
            INNER JOIN position pos ON c.pos_id = pos.pos_id
            INNER JOIN voters v ON c.stud_id = v.stud_id AND c.acad_id = v.acad_id
            LEFT JOIN partylist p ON c.p_id = p.p_id
            WHERE vt.voter_id = '$voter' AND vt.acad_id = '$acad'
            ORDER BY pos.priority ASC, v.lname, v.fname";
    $res = $conn->query($sql);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $pos = $r['position_name'];
            if (!isset($rows[$pos])) $rows[$pos] = [];
            $m = (!empty($r['mname'])) ? substr($r['mname'], 0, 1) . '.' : '';
            $party = (!empty($r['party_name'])) ? $r['party_name'] : 'IND';
            $rows[$pos][] = $r['lname'] . ', ' . $r['fname'] . ' ' . $m . ' (' . $party . ')';
        }
    }
} else {
    $chk = $conn->query("SELECT 1 FROM department_vote WHERE acad_id='$acad' AND voter_id='$voter' LIMIT 1");
    if (!$chk || $chk->num_rows === 0) {
        $_SESSION['response'] = 'You have not voted in the department election yet.';
        $_SESSION['type'] = 'warning';
        header('Location: department_home.php');
        exit;
    }
    $et = $conn->query("SELECT title FROM election_title WHERE acad_id='$acad' AND election_type = 'department' LIMIT 1");
    if ($et && $et->num_rows > 0) {
        $election_title = $et->fetch_assoc()['title'];
    }
    $sql = "SELECT dp.description AS position_name, v.lname, v.fname, v.mname
            FROM department_vote dv
            INNER JOIN dept_candidate dc ON dv.candidate_id = dc.dc_id AND dv.acad_id = dc.acad_id
            INNER JOIN dept_position dp ON dc.pos_id = dp.dp_id AND dc.acad_id = dp.acad_id
            INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id
            WHERE dv.voter_id = '$voter' AND dv.acad_id = '$acad'
            ORDER BY dp.priority ASC, v.lname, v.fname";
    $res = $conn->query($sql);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $pos = $r['position_name'];
            if (!isset($rows[$pos])) $rows[$pos] = [];
            $m = (!empty($r['mname'])) ? substr($r['mname'], 0, 1) . '.' : '';
            $rows[$pos][] = $r['lname'] . ', ' . $r['fname'] . ' ' . $m;
        }
    }
}

if (empty($rows)) {
    $_SESSION['response'] = 'No ballot record found.';
    $_SESSION['type'] = 'warning';
    header('Location: ' . ($type === 'department' ? 'department_home.php' : 'home.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($title); ?> - Print</title>
    <link rel="stylesheet" href="libraries/bower_components/bootstrap/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .ballot-report { box-shadow: none; border: 1px solid #ddd; }
        }
        .ballot-report { max-width: 800px; margin: 0 auto; padding: 1rem; }
        .ballot-report h4 { margin-bottom: 0.5rem; }
        .ballot-report table { width: 100%; margin-bottom: 1rem; }
        .ballot-report th { text-align: left; padding: 0.35rem 0.5rem; border-bottom: 1px solid #dee2e6; }
        .ballot-report td { padding: 0.35rem 0.5rem; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container ballot-report">
        <div class="no-print mb-3 d-flex flex-wrap gap-2 align-items-center">
            <a href="<?php echo $type === 'department' ? 'department_home.php' : 'home.php'; ?>" class="btn btn-outline-secondary btn-sm">&larr; Back</a>
            <button type="button" class="btn btn-primary btn-sm" onclick="window.print();"><i class="fa fa-print"></i> Print Ballot</button>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="text-center text-uppercase"><?php echo htmlspecialchars($title); ?></h4>
                <?php if ($election_title) { ?>
                    <p class="text-center text-muted mb-2"><?php echo htmlspecialchars($election_title); ?></p>
                <?php } ?>
                <p class="text-center small mb-3">Academic Year: <?php echo htmlspecialchars($acad_desc); ?></p>

                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Candidate(s) Voted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $position => $candidates) { ?>
                            <tr>
                                <td class="font-weight-bold"><?php echo htmlspecialchars($position); ?></td>
                                <td><?php echo htmlspecialchars(implode('; ', $candidates)); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <p class="small text-muted text-right mt-2">Generated on <?php echo date('M d, Y h:i A'); ?></p>
            </div>
        </div>
    </div>
    <script src="libraries/bower_components/jquery/js/jquery.min.js"></script>
    <link rel="stylesheet" href="libraries/assets/icon/font-awesome/css/font-awesome.min.css">
</body>
</html>
