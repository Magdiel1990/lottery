
<?php
//PRODUCTO DE TODOS LOS NUMEROS
class TotalProduct {
    public $test;
    public $numbers;
    public $balls;
    public $conn;

    public function __construct($test, $numbers, $balls, $conn) {
        $this -> test = $test;
        $this -> numbers = $numbers;
        $this -> balls = $balls;
        $this -> conn = $conn;
    }
    
    //Array de los productos
    private function productArray () {
        //Array de todas las jugadas pasadas
        $totalArrayNumbers = new PreviousPlaysOut($this -> test, $this -> conn, $this -> balls, $this -> numbers);
        $totalArrayNumbers = $totalArrayNumbers -> totalNumbers();

        $productArray = [];
        //Se calcula el producto de cada arreglo
        for($i = 0; $i < count($totalArrayNumbers); $i++) {
            $product = 1;
            for($j = 0; $j < count($totalArrayNumbers[$i]); $j++) { 
                $product *= $totalArrayNumbers[$i][$j];
            }
            $productArray[] = $product;
        }       
       //Se retorna el array de los productos
        return $productArray;          
    }
    //Rango de los productos
    private function rangeProducts() {   
        $minProduct = min($this -> productArray());
        $maxProduct = max($this -> productArray());

        return [$minProduct, $maxProduct];
    }

    //Producto de la jugada actual
    private function product() {
        $product = 1;

        for($i = 0; $i < count($this -> numbers); $i++) {
            $product *= $array[$i];
        }

        return $product;
    }


    public function testTotalProduct () {
        //producto actual
        $product = $this -> product();

        //rango de los productos
        $range = $this -> rangeProducts();
        //Si la prueba anterior falla
        if($this -> test == false) {
            return false;
        }

        //Si el producto no está en el rango
        if($product < $range[0] || $product > $range[1]) {
            return false;
        } else {
            return true;      
        }
    }
}
?>
