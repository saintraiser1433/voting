
<?php
include '../../connection.php';
$acad = $_SESSION['acad'];
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM position WHERE acad_id='$acad' and pos_id='$id'";
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();

    $priority = $row['priority'] - 1;

    if ($priority == 0) {
    } else {
        $sql = "UPDATE position SET priority = priority + 1 WHERE priority = '$priority'";
        $conn->query($sql);

        $sql = "UPDATE position SET priority = '$priority' WHERE pos_id = '$id'";
        $conn->query($sql);
    }

    echo json_encode($output);
}

?>