<?php
include '../../connection.php';

if (isset($_POST['id'])) {
    $myg = $_POST['id'];
    $acad = $_SESSION['acad'];
    
    // Always show both Course and Section fields for all year levels
    echo '
        <div class="form-group mr-2">
            <label class="col-form-label">Course</label>
            <select name="strand" class="form-control" id="strand" required>
                <option value=""></option>';
    
    // Fetch courses from database
    $sql = "SELECT * FROM courses WHERE acad_id='$acad' AND status=1 ORDER BY course_code ASC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['course_code'] . '">' . $row['course_code'] . ' - ' . $row['course_name'] . '</option>';
        }
    }
    
    echo '
            </select>
        </div>
        <div class="form-group">
            <label class="col-form-label">Section</label>
            <select name="section" class="form-control" id="section" required>
                <option value=""></option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
                <option value="I">I</option>
                <option value="J">J</option>
                <option value="K">K</option>
                <option value="L">L</option>
                <option value="M">M</option>
                <option value="N">N</option>
                <option value="O">O</option>
                <option value="P">P</option>
                <option value="Q">Q</option>
                <option value="R">R</option>
                <option value="S">S</option>
                <option value="T">T</option>
                <option value="U">U</option>
                <option value="V">V</option>
                <option value="W">W</option>
                <option value="X">X</option>
                <option value="Y">Y</option>
                <option value="Z">Z</option>
            </select>
        </div>
    ';
}
