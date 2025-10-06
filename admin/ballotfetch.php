<?php
include '../connection.php';
include 'includes/slugify.php';
$acad = $_SESSION['acad'];
$sql = "SELECT * FROM position where acad_id='$acad'";
$pquery = $conn->query($sql);

$output = '';
$candidate = '';

$sql = "SELECT * FROM position where acad_id='$acad' ORDER BY priority ASC";
$query = $conn->query($sql);
$num = 1;
while ($row = $query->fetch_assoc()) {
    $input = ($row['max_vote'] > 1) ? ' <div class="checkbox-fade fade-in-success  mt-4" id="chck">
    <label>
        <input type="checkbox" class="' . slugify($row['description']) . '" name="' . slugify($row['description']) . "[]" . '">
        <span class="cr">
            <i class="cr-icon icofont icofont-ui-check txt-success"></i>
        </span>
        <span></span>
    </label>
</div>' : '<div class="form-radio m-b-30 mt-4">
<div class="radio radiofill radio-success radio-inline">
    <label class="col-form-label">
        <input type="radio" name="' . slugify($row['description']) . '" class="' . slugify($row['description']) . '">
        <i class="helper"></i>

    </label>

</div>
</div>';

    $sql = "SELECT *,candidate.img as im FROM candidate INNER JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id WHERE candidate.acad_id='$acad' and candidate.pos_id='" . $row['pos_id'] . "'";
    $cquery = $conn->query($sql);
    while ($crow = $cquery->fetch_assoc()) {
        $image = $crow['im'];
        if (is_null($crow['party_name'])) {
            $partyname = "IND";
        } else {
            $partyname = $crow['party_name'];
        }
        $candidate .= ' <div class="row mt-2">
        ' . $input . '
        <ul>
        <li><img src="../' . $image . '"  style="width: 120px; height:120px; border:2px solid steelblue; border-radius:10px;"></li>
            </ul>
            <div class="text-center mt-3 pl-3">
                <h4 class="text-uppercase font-weight-bold" id="myh3">' . $crow['fname'] . ',' . $crow['lname'] . ' ' . $crow['mname'][0] . '.' . ' - <span class="text-warning text-uppercase">' . $partyname . '</span></h3> 
            </div></div>
			';
    }

    $instruct = ($row['max_vote'] > 1) ? 'You may select up to ' . $row['max_vote'] . ' candidates' : 'Select only one candidate';

    $updisable = ($row['priority'] == 1) ? 'disabled' : '';
    $downdisable = ($row['priority'] == $pquery->num_rows) ? 'disabled' : '';

    $output .= '
			<div class="row">
            <div class="col-xl-12 col-md-12">
            <div class="card" id="' . $row['pos_id'] . '">
                <div class="card bg-c-green text-white">
                    <div class="card-block" id="myimgs">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="m-b-5 text-uppercase"><b>' . $row['description'] . '</b></h3>
                                <span class="text-white">' . $instruct . '</span>
                            </div>
                            <div class="col col-auto text-right">
                            <button type="button" class="btn btn-primary btn-sm moveup" data-id="' . $row['pos_id'] . '" ' . $updisable . '><i class="fa fa-arrow-up"></i> </button>
                            <button type="button" class="btn btn-warning btn-sm movedown" data-id="' . $row['pos_id'] . '" ' . $downdisable . '><i class="fa fa-arrow-down"></i></button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card-block-big">
                   
                        
                       ' . $candidate . '
                
                 

                   

                </div>


            </div>
        </div>
    </div>
			</div>
		';



    $num++;
    $candidate = '';
}

echo json_encode($output);
