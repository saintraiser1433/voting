<?php
include '../../connection.php';
$acad = (int) $_SESSION['acad'];
if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $pquery = $conn->query("SELECT COUNT(*) AS cnt FROM dept_position WHERE acad_id='$acad'");
    $total = $pquery && $row = $pquery->fetch_assoc() ? (int) $row['cnt'] : 0;
    $query = $conn->query("SELECT * FROM dept_position WHERE acad_id='$acad' AND dp_id='$id'");
    if ($query && $row = $query->fetch_assoc()) {
        $priority = (int) $row['priority'] + 1;
        if ($priority <= $total) {
            $conn->query("UPDATE dept_position SET priority = priority - 1 WHERE acad_id='$acad' AND priority = '$priority'");
            $conn->query("UPDATE dept_position SET priority = '$priority' WHERE dp_id = '$id'");
        }
    }
    echo json_encode(array('ok' => 1));
}
?>
