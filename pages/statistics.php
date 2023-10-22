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
                <div class="col-auto">
                <?php                    
                    $days = 7;
                    $numbers = $lotopool -> normalNumbers ($days, 5, $conn);

                    $html = "";
                    $html .= "<b>Números más jugados en " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $lotopool -> averageOftheLastPlays ($days, 5, $conn);

                    $html = "";
                    $html .= "<b>Promedios de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>

                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $lotopool -> sumsNumbers ($days, $conn);

                    $html = "";
                    $html .= "<b>Sumas de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>    
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $lotopool -> rangeStandardDeviation ($days, 5, $conn);

                    $html = "";
                    $html .= "<b>Desviaciones de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . round($numbers[$i], 1) . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>    
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $lotopool -> productArray ($days, 5, $conn);

                    $html = "";
                    $html .= "<b>Multiplicaciones de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>    
                <?php
                for($i = 1; $i <= 5; $i++) {
                    for($j = $i + 1; $j <= 5; $j++) {
                        echo '<div class="col-auto">';                   
                        $days = 60;
                        $down = $i;
                        $up = $j;
                        $numbers = $lotopool -> numberDiff ($days, $i, $j, $conn);

                        $html = "";
                        $html .= "<b>Diferencia entre ". $down . " y " . $up ." de los últimos " . $days . " días</b>";
                        $html .= "<div>";
                        for($k = 0; $k < count($numbers); $k++) {
                            $html .= "<span>" . $numbers[$k] . ", </span>";
                        }
                        $html .= "</div>";

                        echo $html;

                        echo "</div> ";
                    }
                }
                ?>  
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $lotopool -> OddEven ($days, 5, $conn);

                    $html = "";
                    $html .= "<b>Cantidad de pares de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>  
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $lotopool -> totalDiff ($days, 5, $conn);

                    $html = "";
                    $html .= "<b>Diferencias totales de los " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>                  
            </div>          
            </div>   
            <?php
                $conn -> close();
            ?>
            <div id="loto" class="row justify-content-center mt-2 text-center">
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
                <div class="col-auto">
                <?php                    
                    $days = 7;
                    $numbers = $loto -> normalNumbers ($days, 6, $conn);

                    $html = "";
                    $html .= "<b>Números más jugados en " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ". </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $loto -> averageOftheLastPlays ($days, 6, $conn);

                    $html = "";
                    $html .= "<b>Promedios de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . round($numbers[$i], 1) . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $loto -> sumsNumbers ($days, $conn);

                    $html = "";
                    $html .= "<b>Sumas de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>    
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $loto -> productArray ($days, 6, $conn);

                    $html = "";
                    $html .= "<b>Multiplicaciones de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>    
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $lotopool -> productArray ($days, 5, $conn);

                    $html = "";
                    $html .= "<b>Multiplicaciones de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>    
                <?php
                for($i = 1; $i <= 6; $i++) {
                    for($j = $i + 1; $j <= 6; $j++) {
                        echo '<div class="col-auto">';                   
                        $days = 60;
                        $down = $i;
                        $up = $j;
                        $numbers = $lotopool -> numberDiff ($days, $i, $j, $conn);

                        $html = "";
                        $html .= "<b>Diferencia entre ". $down . " y " . $up ." de los últimos " . $days . " días</b>";
                        $html .= "<div>";
                        for($k = 0; $k < count($numbers); $k++) {
                            $html .= "<span>" . $numbers[$k] . ", </span>";
                        }
                        $html .= "</div>";

                        echo $html;

                        echo "</div> ";
                    }
                }
                ?>  
                <div class="col-auto">
                <?php                    
                    $days = 80;
                    $numbers = $loto -> OddEven ($days, 6, $conn);

                    $html = "";
                    $html .= "<b>Cantidad de pares de los últimos " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>  
                <div class="col-auto">
                <?php                    
                    $days = 60;
                    $numbers = $loto -> totalDiff ($days, 6, $conn);

                    $html = "";
                    $html .= "<b>Diferencias totales de los " . $days . " días</b>";
                    $html .= "<div>";
                    for($i = 0; $i < count($numbers); $i++) {
                        $html .= "<span>" . $numbers[$i] . ", </span>";
                    }
                    $html .= "</div>";

                    echo $html;
                ?>
                </div>    
            </div>                   
        </div>       
    </main>
<?php
    $conn -> close();
    require ("../partials/footer.php")
?>