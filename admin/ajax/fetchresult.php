<?php include '../../connection.php';
include '../nav/header.php';

$acad = $_GET['acad'];

?>


<?php

$sqlt = "SELECT
                                                CONCAT(
                                                    UPPER(v.lname),
                                                    ', ',
                                                    UPPER(v.fname)
                                                ) AS fname,
                                                
                                                pos.description,
                                                vt.totalvote,
                                                pos.max_vote,
                                                c.img
                                            FROM
                                                candidate c
                                            INNER JOIN voters v ON
                                                c.stud_id = v.stud_id
                                            INNER JOIN partylist p ON
                                                c.p_id = p.p_id
                                            INNER JOIN POSITION pos ON
                                                c.pos_id = pos.pos_id
                                            INNER JOIN election_title et ON
                                                et.acad_id = c.acad_id

                                            LEFT JOIN(
                                                SELECT candidate_id,
                                                    COUNT(DISTINCT voter_id) AS totalvote
                                                FROM
                                                    vote
                                                GROUP BY
                                                    candidate_id
                                            ) vt
                                            ON
                                                vt.candidate_id = c.c_id
                                            WHERE
                                                c.acad_id = $acad AND(
                                                SELECT
                                                    COUNT(*)
                                                FROM
                                                    candidate c2
                                                LEFT JOIN(
                                                    SELECT candidate_id,
                                                        COUNT(DISTINCT voter_id) AS totalvote
                                                    FROM
                                                        vote
                                                    GROUP BY
                                                        candidate_id
                                                ) vt2
                                            ON
                                                vt2.candidate_id = c2.c_id
                                            WHERE
                                                c2.pos_id = c.pos_id AND vt2.totalvote > vt.totalvote
                                            ) < pos.max_vote
                                            and et.is_finished = 1
                                            ORDER BY
                                                pos.priority ASC,
                                                vt.totalvote
                                            DESC";
$rs = $conn->query($sqlt);
if ($rs->num_rows > 0) { ?>
    <button type="button" class="btn btn-primary" id="print" onclick="getprint()">PRINT</button>
    <div class="row users-card mt-2">

        <?php foreach ($rs as $row) { ?>
            <div class="col-lg-6 col-xl-3 col-md-6">

                <div class="card rounded-card user-card">
                    <div class="card-block">
                        <div class="img-hover">
                            <img class="img-fluid img-radius" src="../<?php echo $row['img'] ?>" alt="round-img">

                        </div>
                        <div class="user-content">
                            <h4 class=""><?php echo $row['fname'] ?></h4>
                            <p class="m-b-0 text-muted text-capitalize">
                                VOTES - <?php echo $row['totalvote'] ?>
                            </p>
                            <p class="m-b-0 text-muted text-capitalize">
                                <?php echo $row['description'] ?>
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        <?php }
} else { ?>
    </div>
    <hr />
    <div class="d-flex flex-column justify-content-center ">
        <h1 class="d-flex justify-content-center ">Voting is ongoing</h1>
        <button class="btn btn-danger mt-2 mx-auto" id="endelection">END ELECTION</button>
    </div>

<?php } ?>

<script>
    function getprint() {
        window.location.href = "resultm.php?acad=<?php echo $acad ?>";
    }

    $('#endelection').on('click', function (e) {
        e.preventDefault()
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "ajax/updateSettings.php",
                        method: 'POST',
                        data: {
                            myids: '<?php echo $acad ?>'
                        },
                        success: function (html) {
                            swal("Vote is closed", {
                                icon: "success",
                            }).then((value) => {
                                location.reload();
                            });
                        }
                    })
                }
            })

    }); 
</script>