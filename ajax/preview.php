<?php

include '../connection.php';
include '../admin/includes/slugify.php';

$acad = $_SESSION['acad'];
$sql = "SELECT * FROM position where acad_id='$acad' order by priority asc";
$query = $conn->query($sql);
$output = "";
while ($row = $query->fetch_assoc()) {
	$position = slugify($row['description']);
	$pos_id = $row['pos_id'];
	if (isset($_POST[$position])) {
		if ($row['max_vote'] > 1) {
			if (count($_POST[$position]) > $row['max_vote']) {
				$_SESSION['response'] = 'You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'] . '';
				$_SESSION['type'] = "warning";
			} else {
				foreach ($_POST[$position] as $key => $values) {
					$sql = "SELECT * FROM candidate INNER JOIN voters ON candidate.stud_id=voters.stud_id WHERE candidate.c_id = '$values'";
					$cmquery = $conn->query($sql);
					$cmrow = $cmquery->fetch_assoc();
					$output .= "
							<div class='row votelist'>
		                      	<span class='col-sm-4 col-4 text-uppercase'><span class='pull-right'><b>" . $row['description'] . " :</b></span></span> 
		                      	<span class='col-sm-8 col-8 text-uppercase'>" . $cmrow['lname'] . ", " . $cmrow['fname'] . " " . $cmrow['mname'][0] . "</span>
		                    </div>
						";
				}
			}
		} else {
			$candidate = $_POST[$position];
			$sql = "SELECT * FROM candidate INNER JOIN voters ON candidate.stud_id=voters.stud_id WHERE candidate.c_id = '$candidate'";
			$csquery = $conn->query($sql);
			$csrow = $csquery->fetch_assoc();
			$output .= "
					<div class='row votelist'>
                      	<span class='col-sm-4 col-4 text-uppercase'><span class='pull-right'><b>" . $row['description'] . " :</b></span></span> 
                      	<span class='col-sm-8 col-8 text-uppercase'>" . $csrow['lname'] . ", " . $csrow['fname'] . " " . $csrow['mname'][0] . "</span>
                    </div>
				";
		}
	}
}
$data = array(
	'list'   => $output,



);
echo json_encode($data);
