<?php
require "classes/Database.Class.php";
$conn = DatabaseClassKino::dbConnection();

include "partials/head.php";
include "partials/nav.php";

if(isset($_POST["numbers"])){
    $numbers = $_POST["numbers"];
    $date = $_POST["date"];
    $result = $conn -> query("SELECT id FROM numbers WHERE date = '$date';");   

    if($result -> num_rows == 0) {
        $numbersSorted = array_unique($numbers, SORT_NUMERIC);
        
        if(count($numbersSorted) === count($numbers)) {    

            sort($numbers);

            $sql = "";

            for($i = 0; $i < count($numbers); $i++) {
                $sql .= "INSERT INTO numbers (number, position, date) VALUES ('" . $numbers[$i] . "', " . $i+1 . ", '$date');";
            }

            if($conn -> multi_query($sql)) {
                $_SESSION ["message"] = "Números agregados con éxito";
                $_SESSION ["message-alert"] = "success";
            } else {
                $_SESSION ["message"] = "Error al agregar números";
                $_SESSION ["message-alert"] = "danger";
            }
        } else {
            $_SESSION ["message"] = "No puede haber números repetidos";
            $_SESSION ["message-alert"] = "danger";
        }
    } else {
        $_SESSION ["message"] = "Este número ya existe";
        $_SESSION ["message-alert"] = "danger";
    }
}

$conn -> close();
?>
    <main class="container p-4">
    <?php
        if(isset($_SESSION ["message"])){
            $html = '<div class="mt-3">';
            $html .= '<h4 class = "text-center text-'. $_SESSION ["message-alert"] .'">' . $_SESSION ["message"] . '</h4>';
            $html .= '</div>';

            echo $html;
            
            unset($_SESSION ["message"], $_SESSION ["message-alert"]);
        }
    ?>  
        <div class="row justify-content-center mt-4 text-center"> 
            <div class="mb-3">
                <a href="/lottery/pages/kino.tv.php" class="btn btn-outline-info">Generar</a>
                <a href="/lottery/pages/random_test.kino.php" class="btn btn-outline-info">Test</a>
                <a href="/lottery/index.php" class="btn btn-outline-info">Inicio</a>
            </div>
            <div class="col-auto">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <label for="numbers" class="form-label">Agregar números</label>
                    <div class="d-flex flex-column p-2">
                        <div class="row">
                        <?php
                            for($i = 0; $i < 5; $i++) {
                                echo '<input name="numbers[]" class="form-control m-2 col" type="number" id="numbers' . $i . '" required min="1" max="80">';
                            }
                        ?>
                        </div>
                        <div class="row">
                        <?php
                            for($i = 6; $i < 11; $i++) {
                                echo '<input name="numbers[]" class="form-control m-2 col" type="number" id="numbers' . $i . '" required min="1" max="80">';
                            }
                        ?>
                        </div>
                        <div class="row">
                        <?php
                            for($i = 11; $i < 16; $i++) {
                                echo '<input name="numbers[]" class="form-control m-2 col" type="number" id="numbers' . $i . '" required min="1" max="80">';
                            }
                        ?>
                        </div>
                        <div class="row">
                        <?php
                            for($i = 15; $i < 20; $i++) {
                                echo '<input name="numbers[]" class="form-control m-2 col" type="number" id="numbers' . $i . '" required min="1" max="80">';
                            }
                        ?>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <input class="form-control m-2" type="date" name="date" required>
                        </div>
                    </div>
                    <input class="btn btn-primary m-2" type="submit" value="Agregar">
                </form>
            </div>
        </div>
        <?php
            $conn = DatabaseClassKino::dbConnection();
            $result = $conn -> query("SELECT id FROM numbers LIMIT 1;");

            if ($result -> num_rows > 0) {   
        ?>
        <div class="row justify-content-center my-4">
            <div class="col-auto table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">1º</th>
                            <th scope="col">2º</th>
                            <th scope="col">3º</th>
                            <th scope="col">4º</th>
                            <th scope="col">5º</th>
                            <th scope="col">6º</th>
                            <th scope="col">7º</th>
                            <th scope="col">8º</th>
                            <th scope="col">9º</th>
                            <th scope="col">10º</th>
                            <th scope="col">11º</th>
                            <th scope="col">12º</th>
                            <th scope="col">13º</th>
                            <th scope="col">14º</th>
                            <th scope="col">15º</th>
                            <th scope="col">16º</th>
                            <th scope="col">17º</th>
                            <th scope="col">18º</th>
                            <th scope="col">19º</th>
                            <th scope="col">20º</th>
                            <th class="text-center" scope="col">Acción</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php                            
                            $resultDate = $conn -> query("SELECT DISTINCT date FROM numbers ORDER BY date desc;");

                            $dates = [];

                            while($rowDate = $resultDate -> fetch_assoc()) {
                                $dates[] = $rowDate ["date"];
                            }

                            for($i=0; $i < count($dates); $i++) {        
                                echo '<tr>';
                                echo '<th scope="row">' . date("d-M-Y", strtotime($dates[$i])) . '</th>';
                                
                                $resultNumbers = $conn -> query("SELECT number FROM numbers WHERE date = '". $dates[$i] ."';");
                                while($rowNumbers =  $resultNumbers -> fetch_assoc()) {                        
                                    echo "<td>" . $rowNumbers ["number"] . "</td>";                      
                                }
                                echo '<td><a class="text-danger" href="/lottery/actions/deleteKino.php?date= ' . $dates[$i] . '">Eliminar</a></td>';      
                                echo '</tr>';   
                            }
                        ?>                            
                    </tbody>
                </table>                
            </div>
        </div>
        <?php
            }
        ?>
    </main>
    <script>
        deleteMessage("text-danger", "números");
        //Delete message
        function deleteMessage(button, pageName){
        var deleteButtons = document.getElementsByClassName(button);

            for(var i = 0; i<deleteButtons.length; i++) {
                deleteButtons[i].addEventListener("click", function(event){    
                    if(confirm("¿Desea eliminar estos " + pageName + "?")) {
                        return true;
                    } else {
                        event.preventDefault();
                        return false;
                    }
                })
            }
        }
    </script>
<?php
    $conn -> close();
    require ("partials/footer.php")
?>
