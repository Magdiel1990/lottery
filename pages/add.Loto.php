<?php
require "classes/Database.Class.php";
$conn = DatabaseClassLoto::dbConnection();

//Special Variables
$balls = 40;
$numbers = 6;
/*****************/

require "methods/view_methods.php";

require "partials/head.php";
require "partials/nav.php";

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
        <div class="row justify-content-center text-center mt-4"> 
            <div class="mb-3">
                <a href="<?php echo root . 'Loto/generar';?>" class="btn btn-outline-info">Generar</a>
            </div>
            <div class="col-auto">
                <form action="" method="POST">
                    <label for="numbers" class="form-label">Agregar números</label>
                   
                    <?php
                        echo add_numbers_input($numbers, $balls);
                    ?>
              
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
            $conn = DatabaseClassLoto::dbConnection();
            $result = $conn -> query("SELECT id FROM numbers LIMIT 1;");

            if ($result -> num_rows > 0) {   
        ?>
        <div class="row justify-content-center my-4">
            <div class="col-auto table-responsive">
                <table class="table overflow">
                    <thead>
                    <?php                     
                        echo table_titles($numbers);
                    ?>                            
                    </thead>
                    <tbody>
                    <?php                            
                        echo numbers_played ($conn, "deleteLoto");
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
