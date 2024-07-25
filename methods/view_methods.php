<?php
    //Inputs que recogen los numeros a jugar
    function add_numbers_input($numbers, $balls) {
        $html = '';
        $html .= '<div class="d-flex flex-row justify-content-center flex-wrap">';
    
        for($i = 0; $i < $numbers; $i++) {
            $html .= '<input name="numbers[]" class="form-control m-2 px-2" style="max-width:3rem;" type="number" id="numbers" required min="1" max="'. $balls .'">';
        }
        
        $html .= '</div>';

        return $html;
    }

    //Títulos de las tablas que muestran los numeros jugados

    function table_titles($numbers) {
        $html = '';
        $html .= '<tr>';
        $html .= '<th scope="col">Fecha</th>';
        for($i=1; $i<=$numbers; $i++) {
            $html .= '<th scope="col">'. $i .'º</th>';
        }
        $html .= '<th class="text-center" scope="col">Acción</th>';
        $html .= '</tr>';

        return $html;
    }

    //Muestra numeros jugados

    function numbers_played ($conn, $supr_dir) {
        $resultDate = $conn -> query("SELECT DISTINCT date FROM numbers ORDER BY date desc;");

        $dates = [];

        while($rowDate = $resultDate -> fetch_assoc()) {
            $dates[] = $rowDate ["date"];
        }

        $html = '';

        for($i=0; $i < count($dates); $i++) {                  
            $html .= '<tr>';
            $html .= '<th scope="row" style="width: 150px; display: block;">' . date("d-M-Y", strtotime($dates[$i])) . '</th>';
            
            $resultNumbers = $conn -> query("SELECT number FROM numbers WHERE date = '". $dates[$i] ."';");
            while($rowNumbers =  $resultNumbers -> fetch_assoc()) {                        
                $html .= "<td>" . $rowNumbers ["number"] . "</td>";                      
            }
            $html .= '<td><a class="text-danger" href="/lottery/actions/' . $supr_dir . '.php?date= ' . $dates[$i] . '">Eliminar</a></td>';      
            $html .= '</tr>';            
        }
        return $html;
    }
?>