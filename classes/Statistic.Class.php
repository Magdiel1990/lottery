<?php
require_once ("../classes/Lottery.Class.php");

class Statistic extends LotteryClass {
    protected function numberRange($position, $conn) {
        $maxNumber = $this-> maxNumberRange($position, $conn);
        $minNumber = $this-> minNumberRange($position, $conn);

        return [$minNumber, $maxNumber];
    }
    
    //Rango real de las jugadas
    public function statNumbersRanges ($balls, $conn) {
        $ranges = [];
        for ($i = 1; $i <= $balls; $i++) {
            $ranges [] = $this -> numberRange($i, $conn);
        }
        return $ranges;
    }
    //Repetición un día de otro
    public function statDailyRep ($balls, $conn, $days) {
        $totalNumbers = $this -> totalNumbers($balls, $conn);

        $count = 0;

        for($i = 0; $i < count($totalNumbers) - $days; $i++) {
            for ($j = 0; $j < count($totalNumbers[$i]); $j++) {
                if(in_array($totalNumbers[$i][$j], $totalNumbers[$i + $days])) {
                    $count += 1;
                    break;
                }
            }
        }
        $percentage = round (($count * 100 / count($totalNumbers)));

        return $percentage; 
    }

    public function normalNumbers($days, $balls, $conn) {
        $keys = $this-> lastNumbersMoreOftenPlayed ($days, $balls, $conn);
        $keys = array_chunk ($keys, $balls);
        $keys = $keys[0];

        return $keys;
    }

    protected function sumsArrayNumbers($conn) {
        $result = $conn -> query ("SELECT sum(number) AS suma, date FROM numbers GROUP BY date ORDER BY date desc;");

        $sums = [];

        while($row = $result -> fetch_assoc()) {
             $sums []  = intval($row ["suma"]);
        }

        return $sums;
    }

    public function sumsNumbers ($days, $conn) {
        $sums = $this -> sumsArrayNumbers($conn);
        
        $sums = array_chunk($sums, $days) ;

        return $sums [0];       
    }

    public function rangeStandardDeviation($days, $balls, $conn) {
        //Desviaciones estandares de jugadas anteriores
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);
        $arrayOfStandardDeviation =  $this -> standardDeviationArray($totalArrayNumbers);

        $arrayOfStandardDeviation = array_chunk($arrayOfStandardDeviation, $days);

        return $arrayOfStandardDeviation [0];     
    }
    
    public function productArray ($days, $balls, $conn) {
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);

        $productArray = [];

        for($i = 0; $i < $days; $i++) {
            $product = 1;
            for($j = 0; $j < count($totalArrayNumbers[$i]); $j++) { 
                $product *= $totalArrayNumbers[$i][$j];
            }
            $productArray[] = $product;
        }       

        return $productArray;          
    }

    public function numberDiff ($days, $down, $up, $conn) {
        $positionArrayDown = $this-> positionCalculation($down, $conn);
        $positionArrayUp = $this-> positionCalculation($up, $conn);

        $positionDiferences = [];

        for($i = 0; $i < count($positionArrayDown); $i++) {
            $positionDiferences [] = abs($positionArrayUp[$i] - $positionArrayDown[$i]);
        }

        $positionDiferences= array_chunk($positionDiferences, $days);

        return $positionDiferences [0]; 
    }   

    public function OddEven ($days, $balls, $conn) {
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);

        $even = [];

        for($i = 0; $i < $days; $i++) {       
            $count = 0;     
            for($j = 0; $j < count($totalArrayNumbers[$i]); $j++) { 
                if($totalArrayNumbers[$i][$j] % 2 == 0) {
                    $count += 1;
                }
            }   

            $even [] = $count;
        }   
        
        return $even;
    }

    public function totalDiff ($days, $balls, $conn) {
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);

        $diffArray = [];

        for($i = 0; $i < $days; $i++) {
            for($j = count($totalArrayNumbers[$i]) - 1; $j > count($totalArrayNumbers[$i]) - 2; $j--) { 
                $count = $totalArrayNumbers[$i][$j];
            }  

            for($j = count($totalArrayNumbers[$i]) - 2; $j >= 0; $j--) { 
                $count -= $totalArrayNumbers[$i][$j];
            }

            $diffArray [] = $count;
        }   

        return $diffArray;
    }

    public function averageOftheLastPlays ($days, $balls, $conn) {
        $totalArrayNumbers = $this-> totalNumbers($balls, $conn);

        $averageArray = [];

        for($i = 0; $i < $days; $i++) {
            $averageArray [] = $this -> average($totalArrayNumbers[$i]);
        }

        return $averageArray;       
    }

    public function dateProbability ($conn, $scale) {
        $totalPlays = $this -> totalPlays($conn);

        $result = $conn -> query("SELECT count(*) as `date` FROM numbers WHERE number = " . $scale . ";");
        $row = $result -> fetch_assoc();
        $days = $row["date"];

        return round(($days/$totalPlays) * 100);
    }

    /***************Abstract methods *****************/
    protected function diffRangeLoop($array, $conn) {
        return null;
    }
    protected function sumEachLoop($array, $conn) {
        return null;
    }
    protected function finalNumbers ($days, $balls, $conn) {
        return null;
    }
}
?>