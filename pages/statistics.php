<?php
include "../partials/head.php";
include "../partials/nav.php";
require_once ("../classes/Database.Class.php");
require_once ("../classes/Statistic.Class.php");
$conn = DatabaseClass::dbConnection();
$lotopool = new Statistic ();
?>

    <main class="container">
        <div class="text-center mt-4"> 
            <div id="lotopool" class="row">
                <h2>Loto Pool</h2>
                <div class="col-auto">
                    <?php
                    $ranges = $lotopool -> statNumbersRanges (5, $conn);

                    $html = "";
                    $html .= "<b>Rangos</b><br>";

                    for($i = 0; $i < count($ranges); $i++) {
                        $html .= "<span><b>" . $i + 1 . ":</b>";
                        for ($j = 0; $j < 1; $j++) {
                            $html .= " min = " . $ranges[$i][0] . " max = " . $ranges[$i][1] . " ";
                        }
                        $html .= "</span><br>";
                    }

                    echo $html;
                    ?>
                </div>
            </div>            
        </div>       
    </main>
</body>
</html>
<?php
    $conn -> close();
?>