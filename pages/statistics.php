<?php
//Include the head
include "partials/head.php";
//Include the nav
include "partials/nav.php";

require_once ("classes/Statistic.Class.php");

$lotopool = new Statistic ();
?>
<main class="container">
    <div class="text-center m-4"> 
        <!-- LOTO -->
        <div id="loto" class="row justify-content-center mt-2 overflow">
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
            <?php
            for($i = 1; $i <= 6; $i++) {
                for($j = $i + 1; $j <= 6; $j++) {
                    echo '<div class="col-auto">';                   
                    $days = 60;
                    $down = $i;
                    $up = $j;
                    $numbers = $loto -> numberDiff ($days, $i, $j, $conn);

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
            <?php                    
                $days = 60;
                $numbers = $loto -> totalDiff ($days, 6, $conn);
                echo '<div class="col-auto">';   

                $html = "";
                $html .= "<b>Diferencias totales de los " . $days . " días</b>";
                $html .= "<div>";
                for($i = 0; $i < count($numbers); $i++) {
                    $html .= "<span>" . $numbers[$i] . ", </span>";
                }
                $html .= "</div>";

                echo $html;
                echo '</div>';  

            for($i = 0; $i <= 10; $i++) {    
                $span = $i;    
                $numbers = $loto -> dateProbability ($conn, "DAY(date)", $span);
                echo '<div class="col-auto">';                     
                $html = "";
                $html .= "<b>Número coincide con día más ".$span."</b>";
                $html .= "<div>";
                $html .= "<span>" . $numbers . "%</span>";
                $html .= "</div>";

                echo $html;
                echo '</div>';  
            }
            for($i = 0; $i <= 10; $i++) {    
                $span = $i;
                echo '<div class="col-auto">';      
                $numbers = $loto -> dateProbability ($conn, "MONTH(date)", $span);

                $html = "";
                $html .= "<b>Número coincide con mes más ".$span."</b>";
                $html .= "<div>";
                $html .= "<span>" . $numbers . "%</span>";
                $html .= "</div>";

                echo $html;
                echo '</div>';
            }                       

            for($k = 2; $k <= 10; $k++) {
                echo '<div class="col-auto">';
                $times = $k;
                $numbers = $loto -> timesOut ($times, 6, $conn);

                $html = "";
                $html .= "<b>".$times." veces el número</b>";
                $html .= "<div>";
                $html .= "<span>" . $numbers . "%</span>";
                $html .= "</div>";

                echo $html;
                echo '</div>';
            }

            for($k = 1; $k <= 5; $k++) {
                echo '<div class="col-auto">';
                $amount = $k;
                $numbers = $loto -> commonCombinations ($amount, 6, $conn);

                $html = "";
                $html .= "<b>".$amount." números comúnes</b>";
                $html .= "<div>";
                $html .= "<span>" . $numbers . "%</span>";
                $html .= "</div>";

                echo $html;
                echo '</div>';
            } 

            for($k = 2; $k <= 10; $k++) {                    
                $number = $k;
                for ($i = 0; $i <= 4; $i++) {
                    $times = $i;
                    $numbers = $loto -> multipleCalculation ($times, $number, 6, $conn);

                    echo '<div class="col-auto">';
                    $html = "";
                    $html .= "<b>Hay ". $times ." múltiplos de ". $number . "</b>";
                    $html .= "<div>";
                    $html .= "<span>" . $numbers . "%</span>";
                    $html .= "</div>";

                    echo $html;
                    echo '</div>';
                } 
            }              
            ?>    
    </div>       
</main>
<?php
    $conn -> close();
    require ("partials/footer.php")
?>