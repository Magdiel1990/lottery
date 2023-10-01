<?php
require "../classes/Database.Class.php";
$conn = DatabaseClass::dbConnection();

include "../partials/head.php";
?>

<main class="container">
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
            <form action="../actions/test.php" method="POST">
                <label for="numbers" class="form-label">Números</label>
                <div class="d-flex">
                    <?php
                        if(!isset($_SESSION ["lastnumbers"])) {
                            for($i = 0; $i < 5; $i++) {
                                echo '<input name="numbers[]" class="form-control m-2" type="number" id="numbers" required min="1" max="31">';
                            }
                        } else {
                            for($i = 0; $i < 5; $i++) {
                                echo '<input name="numbers[]" value="' . $_SESSION ["lastnumbers"] [$i]. '" class="form-control m-2" type="number" id="numbers" required min="1" max="31">';
                            }
                        }
                    ?>
                </div>
                <div class="d-flex mt-3 row justify-content-center">
                    <div class="col-auto">
                        <label for="amount" class="form-label">Cantidad: </label>
                        <?php
                        if(!isset($_SESSION ["lastnumbers"])) {
                            echo '<input class="form-control" type="number" name="amount" id="amount" required min="10" max="10000000" required>';
                        } else {
                            echo '<input class="form-control" value="' . $_SESSION ["lastnumbers"][6] . '"type="number" name="amount" id="amount" required min="10" max="10000000" required>';
                        }
                        ?>
                    </div>
                </div>
                <input class="btn btn-primary mt-3" type="submit" value="Test">
            </form>
        </div>
    </div>
  </main>
</body>
</html>
<?php
    $conn -> close();
?>