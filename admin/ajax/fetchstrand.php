<?php
if (isset($_POST['id'])) {
    $myg = $_POST['id'];
    if ($myg == "11" || $myg == "12") {
        echo '
            <div class="form-group mr-2">
       
            <label class="col-form-label">Strand</label>
            <select name="strand" class="form-control" id="strand" required>
                <option value=""></option>
                <option value="HUMMS">HUMMS</option>
                <option value="ABM">ABM</option>
                <option value="GAS">GAS</option>
                <option value="STEM">STEM</option>
                <option value="TVL-ICT">TVL-ICT</option>
                <option value="TVL-COOKERY">TVL-COOKERY</option>
            </select>
        </div>
        <div class="form-group">
            <label class="col-form-label">Section</label>
            <select name="section" class="form-control" id="section" required>
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
    } else {
        echo '
        <div class="form-group">
        <input type="hidden" name="strand">
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
}
