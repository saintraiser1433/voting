<?php
/**
 * Connected demo seeder for voting system.
 * Run: php admin/db/seed_connected_demo.php
 */

require_once __DIR__ . '/../../connection.php';

function s_table_exists(mysqli $conn, string $table): bool
{
    $t = $conn->real_escape_string($table);
    $r = $conn->query("SHOW TABLES LIKE '$t'");
    return $r && $r->num_rows > 0;
}

function s_columns(mysqli $conn, string $table): array
{
    $cols = [];
    $res = $conn->query("SHOW COLUMNS FROM `$table`");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $cols[] = $row['Field'];
        }
    }
    return $cols;
}

function s_has_col(array $cols, string $name): bool
{
    return in_array($name, $cols, true);
}

function s_insert(mysqli $conn, string $table, array $data, array $cols): bool
{
    $filtered = [];
    foreach ($data as $k => $v) {
        if (s_has_col($cols, $k)) {
            $filtered[$k] = $v;
        }
    }
    if ($filtered === []) {
        return false;
    }

    $keys = [];
    $vals = [];
    foreach ($filtered as $k => $v) {
        $keys[] = "`$k`";
        if ($v === null) {
            $vals[] = "NULL";
        } else {
            $vals[] = "'" . $conn->real_escape_string((string)$v) . "'";
        }
    }

    $sql = "INSERT INTO `$table` (" . implode(',', $keys) . ") VALUES (" . implode(',', $vals) . ")";
    return (bool)$conn->query($sql);
}

if (!s_table_exists($conn, 'acad_tbl') || !s_table_exists($conn, 'voters')) {
    exit("Required tables not found.\n");
}

$acadCols = s_columns($conn, 'acad_tbl');
$voterCols = s_columns($conn, 'voters');

// 1) Academic year
$acadId = 0;
$active = $conn->query("SELECT acad_id FROM acad_tbl WHERE status=1 ORDER BY acad_id DESC LIMIT 1");
if ($active && $active->num_rows > 0) {
    $acadId = (int)$active->fetch_assoc()['acad_id'];
} else {
    s_insert($conn, 'acad_tbl', ['description' => '2026', 'status' => 1], $acadCols);
    $acadId = (int)$conn->insert_id;
}
if ($acadId <= 0) {
    exit("Could not resolve active academic year.\n");
}

// 2) Election settings (general + department when supported)
if (s_table_exists($conn, 'election_title')) {
    $etCols = s_columns($conn, 'election_title');
    $types = ['general'];
    if (s_has_col($etCols, 'election_type')) {
        $types[] = 'department';
    }

    foreach ($types as $type) {
        $checkSql = "SELECT id FROM election_title WHERE acad_id='$acadId'";
        if (s_has_col($etCols, 'election_type')) {
            $tEsc = $conn->real_escape_string($type);
            $checkSql .= " AND election_type='$tEsc'";
        }
        $checkSql .= " LIMIT 1";
        $has = $conn->query($checkSql);
        if (!$has || $has->num_rows === 0) {
            $base = [
                'title' => strtoupper($type) . " ELECTION AY " . date('Y'),
                'acad_id' => $acadId,
                'is_finished' => 0,
                'date_start' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'date_end' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'election_type' => $type,
            ];
            s_insert($conn, 'election_title', $base, $etCols);
        }
    }
}

// 3) Departments
$departmentIds = [];
if (s_table_exists($conn, 'departments')) {
    $depCols = s_columns($conn, 'departments');
    $seedDeps = ['IT Department', 'Business Department', 'Education Department'];
    foreach ($seedDeps as $depName) {
        $dn = $conn->real_escape_string($depName);
        $exists = $conn->query("SELECT department_id FROM departments WHERE department_name='$dn' LIMIT 1");
        if ($exists && $exists->num_rows > 0) {
            $departmentIds[] = (int)$exists->fetch_assoc()['department_id'];
            continue;
        }
        s_insert($conn, 'departments', ['department_name' => $depName, 'status' => 1], $depCols);
        if ($conn->insert_id > 0) {
            $departmentIds[] = (int)$conn->insert_id;
        }
    }
}

// 4) Partylist
$partyIds = [];
if (s_table_exists($conn, 'partylist')) {
    $partyCols = s_columns($conn, 'partylist');
    $seedParty = [
        ['name' => 'Unity Party', 'platform' => 'Transparent leadership and student welfare.'],
        ['name' => 'Progress Party', 'platform' => 'Campus innovation and inclusive activities.'],
    ];
    foreach ($seedParty as $p) {
        $pn = $conn->real_escape_string($p['name']);
        $exists = $conn->query("SELECT p_id FROM partylist WHERE acad_id='$acadId' AND party_name='$pn' LIMIT 1");
        if ($exists && $exists->num_rows > 0) {
            $partyIds[] = (int)$exists->fetch_assoc()['p_id'];
            continue;
        }
        s_insert($conn, 'partylist', [
            'acad_id' => $acadId,
            'party_name' => $p['name'],
            'platform' => $p['platform'],
            'img' => 'libraries/img/logo.png',
        ], $partyCols);
        if ($conn->insert_id > 0) {
            $partyIds[] = (int)$conn->insert_id;
        }
    }
}

// 5) General positions
$generalPositions = [];
if (s_table_exists($conn, 'position')) {
    $posCols = s_columns($conn, 'position');
    $seedPos = [
        ['President', 1, 1],
        ['Vice President', 1, 2],
        ['Secretary', 1, 3],
        ['Treasurer', 1, 4],
        ['Auditor', 1, 5],
        ['P.I.O', 2, 6],
    ];
    foreach ($seedPos as $p) {
        $desc = $conn->real_escape_string($p[0]);
        $exists = $conn->query("SELECT pos_id FROM position WHERE acad_id='$acadId' AND description='$desc' LIMIT 1");
        if ($exists && $exists->num_rows > 0) {
            $generalPositions[] = (int)$exists->fetch_assoc()['pos_id'];
            continue;
        }
        s_insert($conn, 'position', [
            'description' => $p[0],
            'max_vote' => $p[1],
            'acad_id' => $acadId,
            'priority' => $p[2],
        ], $posCols);
        if ($conn->insert_id > 0) {
            $generalPositions[] = (int)$conn->insert_id;
        }
    }
}

// 6) Department positions
$deptPositions = [];
if (s_table_exists($conn, 'dept_position')) {
    $dposCols = s_columns($conn, 'dept_position');
    $seedDPos = [
        ['Department Governor', 1, 1],
        ['Assistant Governor', 1, 2],
        ['Representative', 2, 3],
    ];
    foreach ($seedDPos as $p) {
        $desc = $conn->real_escape_string($p[0]);
        $exists = $conn->query("SELECT dp_id FROM dept_position WHERE acad_id='$acadId' AND description='$desc' LIMIT 1");
        if ($exists && $exists->num_rows > 0) {
            $deptPositions[] = (int)$exists->fetch_assoc()['dp_id'];
            continue;
        }
        s_insert($conn, 'dept_position', [
            'description' => $p[0],
            'max_vote' => $p[1],
            'acad_id' => $acadId,
            'priority' => $p[2],
        ], $dposCols);
        if ($conn->insert_id > 0) {
            $deptPositions[] = (int)$conn->insert_id;
        }
    }
}

// 7) Voters
$demoVoters = [];
for ($i = 1; $i <= 18; $i++) {
    $stud = 'DEMO-' . date('y') . '-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT);
    $demoVoters[] = [
        'stud_id' => $stud,
        'fname' => 'Demo' . $i,
        'lname' => 'Student',
        'mname' => 'A',
        'grade_level' => 1 + ($i % 4),
        'strand' => 'BSIT',
        'section' => 'A',
        'password' => md5('12345'),
        'is_verified' => 1,
        'is_verified_general' => 1,
        'is_verified_dept' => 1,
    ];
}

$demoVoterIds = [];
foreach ($demoVoters as $idx => $v) {
    $sid = $conn->real_escape_string($v['stud_id']);
    $existing = $conn->query("SELECT v_id FROM voters WHERE stud_id='$sid' AND acad_id='$acadId' LIMIT 1");
    if ($existing && $existing->num_rows > 0) {
        $demoVoterIds[] = (int)$existing->fetch_assoc()['v_id'];
        continue;
    }
    $depId = 0;
    if (!empty($departmentIds)) {
        $depId = $departmentIds[$idx % count($departmentIds)];
    }
    $insertData = [
        'stud_id' => $v['stud_id'],
        'acad_id' => $acadId,
        'fname' => $v['fname'],
        'lname' => $v['lname'],
        'mname' => $v['mname'],
        'grade_level' => $v['grade_level'],
        'strand' => $v['strand'],
        'section' => $v['section'],
        'auth_code' => md5($v['stud_id'] . microtime(true)),
        'date_issued' => date('Y-m-d H:i:s'),
        'password' => $v['password'],
        'is_verified' => $v['is_verified'],
        'is_verified_general' => $v['is_verified_general'],
        'is_verified_dept' => $v['is_verified_dept'],
        'department_id' => $depId > 0 ? $depId : null,
    ];
    s_insert($conn, 'voters', $insertData, $voterCols);
    if ($conn->insert_id > 0) {
        $demoVoterIds[] = (int)$conn->insert_id;
    }
}

// 8) General candidates (2 per position)
$generalCandidateIdsByPos = [];
if (s_table_exists($conn, 'candidate') && !empty($generalPositions)) {
    $candCols = s_columns($conn, 'candidate');
    $candSeedVoters = $conn->query("SELECT stud_id FROM voters WHERE acad_id='$acadId' ORDER BY v_id ASC LIMIT 12");
    $studPool = [];
    if ($candSeedVoters) {
        while ($r = $candSeedVoters->fetch_assoc()) {
            $studPool[] = $r['stud_id'];
        }
    }
    $sidx = 0;
    foreach ($generalPositions as $posId) {
        $generalCandidateIdsByPos[$posId] = [];
        for ($slot = 0; $slot < 2; $slot++) {
            if (!isset($studPool[$sidx])) {
                break;
            }
            $stud = $conn->real_escape_string($studPool[$sidx]);
            $sidx++;
            $exists = $conn->query("SELECT c_id FROM candidate WHERE acad_id='$acadId' AND stud_id='$stud' AND pos_id='$posId' LIMIT 1");
            if ($exists && $exists->num_rows > 0) {
                $generalCandidateIdsByPos[$posId][] = (int)$exists->fetch_assoc()['c_id'];
                continue;
            }
            $pid = !empty($partyIds) ? $partyIds[$slot % count($partyIds)] : 0;
            s_insert($conn, 'candidate', [
                'acad_id' => $acadId,
                'p_id' => $pid,
                'stud_id' => $stud,
                'pos_id' => $posId,
                'img' => 'libraries/img/logo.png',
                'platform' => 'Demo platform for testing.',
            ], $candCols);
            if ($conn->insert_id > 0) {
                $generalCandidateIdsByPos[$posId][] = (int)$conn->insert_id;
            }
        }
    }
}

// 9) Department candidates
$deptCandidateIds = [];
if (s_table_exists($conn, 'dept_candidate') && !empty($deptPositions) && !empty($departmentIds)) {
    $dcCols = s_columns($conn, 'dept_candidate');
    foreach ($departmentIds as $depId) {
        foreach ($deptPositions as $dpId) {
            $studRes = $conn->query("SELECT stud_id FROM voters WHERE acad_id='$acadId'" . (s_has_col($voterCols, 'department_id') ? " AND department_id='$depId'" : '') . " ORDER BY v_id ASC LIMIT 2");
            $pool = [];
            if ($studRes) {
                while ($r = $studRes->fetch_assoc()) {
                    $pool[] = $r['stud_id'];
                }
            }
            foreach ($pool as $stud) {
                $studEsc = $conn->real_escape_string($stud);
                $exists = $conn->query("SELECT dc_id FROM dept_candidate WHERE acad_id='$acadId' AND stud_id='$studEsc' AND pos_id='$dpId' AND department_id='$depId' LIMIT 1");
                if ($exists && $exists->num_rows > 0) {
                    $deptCandidateIds[] = (int)$exists->fetch_assoc()['dc_id'];
                    continue;
                }
                s_insert($conn, 'dept_candidate', [
                    'acad_id' => $acadId,
                    'stud_id' => $stud,
                    'pos_id' => $dpId,
                    'department_id' => $depId,
                    'img' => 'libraries/img/logo.png',
                    'platform' => 'Department-level demo platform.',
                ], $dcCols);
                if ($conn->insert_id > 0) {
                    $deptCandidateIds[] = (int)$conn->insert_id;
                }
            }
        }
    }
}

// 10) Votes (only for demo voters with no existing votes in this acad)
if (s_table_exists($conn, 'vote') && !empty($generalCandidateIdsByPos)) {
    $voteCols = s_columns($conn, 'vote');
    foreach ($demoVoterIds as $vid) {
        $already = $conn->query("SELECT id FROM vote WHERE voter_id='$vid' AND acad_id='$acadId' LIMIT 1");
        if ($already && $already->num_rows > 0) {
            continue;
        }
        foreach ($generalCandidateIdsByPos as $candIds) {
            if (empty($candIds)) {
                continue;
            }
            s_insert($conn, 'vote', [
                'voter_id' => $vid,
                'candidate_id' => $candIds[array_rand($candIds)],
                'acad_id' => $acadId,
            ], $voteCols);
        }
    }
}

// 11) Department votes (for demo voters with no dept votes)
if (s_table_exists($conn, 'department_vote') && !empty($departmentIds)) {
    $dvCols = s_columns($conn, 'department_vote');
    foreach ($demoVoterIds as $vid) {
        $vInfo = $conn->query("SELECT department_id FROM voters WHERE v_id='$vid' LIMIT 1");
        if (!$vInfo || $vInfo->num_rows === 0) {
            continue;
        }
        $dep = (int)$vInfo->fetch_assoc()['department_id'];
        if ($dep <= 0) {
            continue;
        }
        $already = $conn->query("SELECT id FROM department_vote WHERE voter_id='$vid' AND acad_id='$acadId' LIMIT 1");
        if ($already && $already->num_rows > 0) {
            continue;
        }
        if (s_table_exists($conn, 'dept_candidate') && s_table_exists($conn, 'dept_position')) {
            foreach ($deptPositions as $dpId) {
                $candRes = $conn->query("SELECT dc_id FROM dept_candidate WHERE acad_id='$acadId' AND department_id='$dep' AND pos_id='$dpId' ORDER BY dc_id ASC");
                $pool = [];
                if ($candRes) {
                    while ($r = $candRes->fetch_assoc()) {
                        $pool[] = (int)$r['dc_id'];
                    }
                }
                if (empty($pool)) {
                    continue;
                }
                s_insert($conn, 'department_vote', [
                    'voter_id' => $vid,
                    'candidate_id' => $pool[array_rand($pool)],
                    'acad_id' => $acadId,
                    'department_id' => $dep,
                ], $dvCols);
            }
        }
    }
}

echo "Seed complete for acad_id=$acadId\n";
echo "Demo voters processed: " . count($demoVoterIds) . "\n";
echo "General positions: " . count($generalPositions) . "\n";
echo "Department positions: " . count($deptPositions) . "\n";
echo "Departments: " . count($departmentIds) . "\n";

