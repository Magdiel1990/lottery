<?php


public function multipleCalculation ($times, $number, $balls, $conn) {
    $totalNumbers = $this -> totalNumbers($balls, $conn);
    //Total de jugadas
    $totalPlays = $this -> totalPlays($conn);

    $count = [];

    for ($i = 0; $i < count($totalNumbers); $i++) {
        $repeat = 0;
        for($j = 0; $j < count($totalNumbers[$i]); $j++) {
            if($totalNumbers[$i][$j] % $number == 0) {
                $repeat += 1;
            }
        }
        $count [] = $repeat;
    }

    $rep = $this -> element_rep ($times, $count);

    return intval(round ($rep * 100/$totalPlays));
}

protected function multipleCounter($number, $array) {
    if(count($array) == 0) {
        return -1;
    }

    $count = 0;
    
    for($i=0; $i < count($array); $i++) {
        if($array [$i] % $number == 0) {
            $count += 1;
        }
    }
    return $count;
}

?>