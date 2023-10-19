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
            <div id="lotopool" class="row justify-content-center">
                <h2 class="mb-4">Loto Pool</h2>
                <div class="col-auto">
                    <?php
                    $ranges = $lotopool -> statNumbersRanges (5, $conn);

                    $html = "";
                    $html .= "<b>Rangos</b><br>";

                    for($i = 0; $i < count($ranges); $i++) {
                        $html .= "<span>" . $i + 1 . ":";
                        for ($j = 0; $j < 1; $j++) {
                            $html .= " min = " . $ranges[$i][0] . " max = " . $ranges[$i][1] . " ";
                        }
                        $html .= "</span><br>";
                    }

                    echo $html;
                    ?>
                </div>
                <div class="col-auto">
                    <?php
                    $html = "";
                    for($i = 1; $i < 8; $i++) {
                        $statDailyRep = $lotopool -> statDailyRep (5, $conn, $i);

                        $html = "";
                        $html .= "<b>Números que se repiten cada " . $i . " días</b>";
                        $html .= "<div>" . $statDailyRep . "%";
                        $html .= "</div>";
    
                        echo $html;
                    }
                    ?>
                </div>
            </div>   
            <?php
                $conn -> close();
            ?>
            <div id="loto" class="row justify-content-center mt-2">
                <h2 class="mb-4">Loto</h2>
                <div class="col-auto">
                    <?php
                    $conn = DatabaseClassLoto::dbConnection();
                    $loto = new Statistic ();

                    $ranges = $loto -> statNumbersRanges (6, $conn);

                    $html = "";
                    $html .= "<b>Rangos</b><br>";

                    for($i = 0; $i < count($ranges); $i++) {
                        $html .= "<span>" . $i + 1 . ":";
                        for ($j = 0; $j < 1; $j++) {
                            $html .= " min = " . $ranges[$i][0] . " max = " . $ranges[$i][1] . " ";
                        }
                        $html .= "</span><br>";
                    }

                    echo $html;
                    ?>
                </div>
                <div class="col-auto">
                    <?php
                    $html = "";
                    for($i = 1; $i < 8; $i++) {
                        $statDailyRep = $loto -> statDailyRep (6, $conn, $i);

                        $html = "";
                        $html .= "<b>Números que se repiten cada " . $i . " días</b>";
                        $html .= "<div>" . $statDailyRep . "%";
                        $html .= "</div>";
    
                        echo $html;
                    }
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