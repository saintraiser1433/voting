<?php
require_once '../connection.php';
require_once '../admin/includes/slugify.php';

header('Content-Type: application/json');

// Read raw JSON body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid payload']);
    exit;
}

$mode     = isset($data['mode']) ? $data['mode'] : 'general';
$voter_id = isset($data['v_id']) ? (int)$data['v_id'] : 0;
$acad_id  = isset($data['acad_id']) ? (int)$data['acad_id'] : 0;
$dept_id  = isset($data['dept_id']) ? (int)$data['dept_id'] : 0;
$votes    = isset($data['votes']) && is_array($data['votes']) ? $data['votes'] : [];

if (!$voter_id || !$acad_id || empty($votes)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

// Basic voter existence check
$vres = $conn->query("SELECT v_id FROM voters WHERE v_id = '$voter_id' AND acad_id = '$acad_id' LIMIT 1");
if (!$vres || $vres->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Voter not found']);
    exit;
}

if ($mode === 'department') {
    if (!$dept_id) {
        echo json_encode(['status' => 'error', 'message' => 'Missing department']);
        exit;
    }

    // Duplicate guard
    $chk = $conn->query("SELECT 1 FROM department_vote WHERE acad_id='$acad_id' AND voter_id='$voter_id' LIMIT 1");
    if ($chk && $chk->num_rows > 0) {
        echo json_encode(['status' => 'already_voted']);
        exit;
    }

    // Build inserts following submit_department_ballot.php logic
    $sql = "SELECT * FROM dept_position WHERE acad_id='$acad_id'";
    $query = $conn->query($sql);
    if (!$query) {
        echo json_encode(['status' => 'error', 'message' => 'Unable to load positions']);
        exit;
    }

    $sql_array = [];
    while ($row = $query->fetch_assoc()) {
        $position = slugify($row['description']);
        if (!isset($votes[$position])) {
            continue;
        }

        $max_vote = (int)$row['max_vote'];
        $vals = $votes[$position];
        if (!is_array($vals)) {
            $vals = [$vals];
        }
        $vals = array_map('intval', array_filter($vals));

        if (count($vals) > $max_vote) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'You can only choose ' . $max_vote . ' candidates for ' . $row['description'],
            ]);
            exit;
        }

        foreach ($vals as $dc_id) {
            if ($dc_id > 0) {
                $sql_array[] = "INSERT INTO department_vote (voter_id, candidate_id, acad_id, department_id) VALUES ('$voter_id', '$dc_id', '$acad_id', '$dept_id')";
            }
        }
    }

    foreach ($sql_array as $sql_row) {
        $conn->query($sql_row);
    }

    echo json_encode(['status' => 'ok']);
    exit;
}

// GENERAL MODE
// Duplicate guard
$chk = $conn->query("SELECT 1 FROM vote WHERE acad_id='$acad_id' AND voter_id='$voter_id' LIMIT 1");
if ($chk && $chk->num_rows > 0) {
    echo json_encode(['status' => 'already_voted']);
    exit;
}

$sql = "SELECT * FROM position WHERE acad_id='$acad_id'";
$query = $conn->query($sql);
if (!$query) {
    echo json_encode(['status' => 'error', 'message' => 'Unable to load positions']);
    exit;
}

$sql_array = [];
while ($row = $query->fetch_assoc()) {
    $position = slugify($row['description']);
    if (!isset($votes[$position])) {
        continue;
    }

    $max_vote = (int)$row['max_vote'];
    $vals = $votes[$position];
    if (!is_array($vals)) {
        $vals = [$vals];
    }
    $vals = array_map('intval', array_filter($vals));

    if ($max_vote > 1) {
        if (count($vals) > $max_vote) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'You can only choose ' . $max_vote . ' candidates for ' . $row['description'],
            ]);
            exit;
        }
        foreach ($vals as $cid) {
            if ($cid > 0) {
                $sql_array[] = "INSERT INTO vote (voter_id, candidate_id, acad_id) VALUES ('$voter_id', '$cid', '$acad_id')";
            }
        }
    } else {
        $cid = isset($vals[0]) ? (int)$vals[0] : 0;
        if ($cid > 0) {
            $sql_array[] = "INSERT INTO vote (voter_id, candidate_id, acad_id) VALUES ('$voter_id', '$cid', '$acad_id')";
        }
    }
}

foreach ($sql_array as $sql_row) {
    $conn->query($sql_row);
}

echo json_encode(['status' => 'ok']);

