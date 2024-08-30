<?php
class NumbersEntriesInterface {
    private $balls;
    private $top;
    private $values;
    
    public function __construct($balls, $top, $values = null /*Si trae valor permite editar*/) {
        $this -> balls = $balls;
        $this -> top = $top;
        $this -> values = $values;
    }

    //Metodo para crear los inputs
    public function createInputs($html = "") {
        $html .= '<div class="d-flex flex-row flex-wrap">';
        //Se crean los inputs para los números
        if($this -> values != null) {
            for($i = 0; $i < $this -> balls; $i++) {
                $html .= '<input name="numbers[]" class="form-control m-2 px-2" style="max-width:3rem;" type="number" id="numbers" required min="1" max="'. $this -> top .'" value="'. $this -> values[$i] .'">';
            }
        } else {
            for($i = 0; $i < $this -> balls; $i++) {                        
                $html .= '<input name="numbers[]" class="form-control m-2 px-2" style="max-width:3rem;" type="number" id="numbers" required min="1" max="'. $this -> top .'">';
            }          
        }

        $html .= '</div>';

        return $html;
    }
}
?>