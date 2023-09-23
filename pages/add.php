<?php
require "../classes/Database.Class.php";
$conn = DatabaseClass::dbConnection();

include "../partials/head.php";

if(isset($_POST["numbers"])){

$numbers = $_POST["numbers"];
$date = $_POST["date"];

    $numbersSorted = array_unique($numbers, SORT_NUMERIC);

    if(count($numbersSorted) === count($numbers)) {    

        $sql = "";

        for($i = 0; $i < count($numbers); $i++) {
            $sql .= "INSERT INTO numbers (number, position, type_id, date) VALUES ('" . $numbers[$i] . "', " . $i+1 . ", 1, '$date');";
        }

        $result = $conn -> multi_query($sql);

        if($result) {
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
}

?>
    <main class="container p-4">
    <?php
        include "../partials/nav.php";

        if(isset($_SESSION ["message"])){
            $html = '<div class="mt-3">';
            $html .= '<h4 class = "text-center text-'. $_SESSION ["message-alert"] .'">' . $_SESSION ["message"] . '</h4>';
            $html .= '</div>';

            echo $html;
            
            unset($_SESSION ["message"], $_SESSION ["message-alert"]);

        }
    ?>  
        <div class="row justify-content-center text-center mt-4"> 
            <div class="col-auto">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <label for="numbers" class="form-label">Números</label>
                    <div class="d-flex">
                        <?php
                            for($i = 0; $i < 5; $i++) {
                                echo '<input name="numbers[]" class="form-control m-2" type="number" id="numbers" required min="1" max="31">';
                            }
                        ?>
                    </div>
                    <div>
                        <input class="form-control m-2" type="date" name="date" required>
                    </div>
                    <input class="btn btn-primary m-2" type="submit" value="Agregar">
                </form>
            </div>
        </div>
        <?php
            $result = $conn -> query("SELECT id FROM numbers LIMIT 1;");
            $num_rows = $result -> num_rows > 0;

           // mysqli_store_result($conn);

            if ($num_rows > 0) {   
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
                            <th class="text-center" scope="col">Acción</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $resultdate = $conn -> query("SELECT DISTINCT date FROM numbers ORDER BY date desc;");
                            while($rowdate = $resultdate -> fetch_assoc()) {
                        ?>
                        <tr>
                            <th scope="row"><?php echo date("d-M-Y", strtotime($rowdate["date"])) ?></th>
                        <?php
                            $resultNumber = $conn -> query("SELECT number FROM numbers WHERE date = '". $rowdate["date"] ."' ORDER BY date desc;");
                            while($rowNumber =  $resultNumber -> fetch_assoc()) {
                        ?>
                            <td><?php echo $rowNumber["number"]?></td>                                        
                        <?php
                             }
                        ?>
                        <td><a class="text-danger" href="/lottery/actions/delete.php?date=<?php echo $rowdate["date"]; ?>">Eliminar</a></td>      
                        </tr>
                                                                        
                        <?php
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
</body>
</html>
<?php
    $conn -> close();
?>