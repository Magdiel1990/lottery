<?php
class NumbersEntriesInterface {
    private $balls;
    private $top;
    
    public function __construct($balls, $top) {
        $this -> balls = $balls;
        $this -> top = $top;
    }

    public function createInputs() {
        $html = '<div class="d-flex flex-row justify-content-center flex-wrap">';
        //Se crean los inputs para los números
        for($i = 0; $i < $this -> balls; $i++) {                        
            $html .= '<input name="numbers[]" class="form-control m-2 px-2" style="max-width:3rem;" type="number" id="numbers" required min="1" max="'. $this -> top .'">';
        }          

        $html .= '</div>';

        echo $html;
    }
}
?>