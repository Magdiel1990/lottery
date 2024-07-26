<?php
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