<div class="modal fade" id="platform1" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-titles">Platform</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="card p-2">
                            <p class="text-justify p-3" id="see"></p>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="members" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-titles">Member</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="card p-2">
                            <div id="member2"></div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="preview1" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-titles">My Ballot</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="card p-2">
                            <div id="preview2"></div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="ballots" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-titles">My Ballot</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="card p-2">
                            <?php
                            $vote1 = $_SESSION['v_id'];
                            $sql = "SELECT * FROM candidate LEFT JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN vote ON candidate.c_id=vote.candidate_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id LEFT JOIN position ON candidate.pos_id=position.pos_id where vote.voter_id='$vote1' and vote.acad_id='$acad' order by position.priority ASC";
                            $query = $conn->query($sql);
                            while ($row = $query->fetch_assoc()) {
                                if ($row['p_id'] == 0) {
                                    $party = "IND";
                                } else {
                                    $party = $row['party_name'];
                                }
                                echo "
                                        <div class='row votelist' style='font-size:11px;'>
                                          <span class='col-lg-4 col-4 text-uppercase'><span class='pull-right'><b>" . $row['description'] . " :</b></span></span> 
                                          <span class='col-lg-8 col-8 text-uppercase'>" . $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0] . "- (" . $party . ")</span>
                                        </div>
                                      ";
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>