<?php
    include "../partials/head.php";
    include "../partials/nav.php";
?>

<main class="container">
    <?php
        if(isset($_SESSION ["message"])){
            $html = '<div class="mt-3">';
            $html .= '<h4 class = "text-center text-'. $_SESSION ["message-alert"] .'">' . $_SESSION ["message"] . '</h4>';
            $html .= '</div>';

            echo $html;
            
            unset($_SESSION ["message"], $_SESSION ["message-alert"]);
        } else {
            //Desactivar contador de intentos
            unset($_SESSION ["try"]);
        }
    ?>  
    <div class="row justify-content-center text-center mt-4"> 
        <div class="col-auto">
            <form action="../actions/test.Kino.php" method="POST">
                <label for="numbers" class="form-label">Números</label>
                <div class="d-flex flex-column p-2">                    
                    <?php
                        if(!isset($_SESSION ["lastnumbers"])) {
                    ?>
                    <div class="row">
                    <?php
                            for($i = 0; $i < 10; $i++) {
                                echo '<input name="numbers[]" class="form-control m-2 col" type="number" id="numbers' . $i . '" required min="1" max="80">';
                            }
                    ?>
                    </div>
                    <div class="row">
                        <?php
                            for($i = 10; $i < 20; $i++) {
                                echo '<input name="numbers[]" class="form-control m-2 col" type="number" id="numbers' . $i . '" required min="1" max="80">';
                            }
                        ?>
                    </div>
                        <?php                            
                        } else {
                        ?>
                    <div class="row">
                        <?php
                            for($i = 0; $i < 10; $i++) {
                                echo '<input name="numbers[]" value="' . $_SESSION ["lastnumbers"] [$i]. '" class="form-control m-2 col" type="number" id="numbers' . $i . '" required min="1" max="80">';
                            }
                        ?>
                    </div>
                    <div class="row">
                        <?php
                            for($i = 10; $i < 20; $i++) {
                                echo '<input name="numbers[]" value="' . $_SESSION ["lastnumbers"] [$i]. '" class="form-control m-2 col" type="number" id="numbers' . $i . '" required min="1" max="80">';
                            }
                        ?>
                    </div>
                    <?php
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
                <input class="btn btn-primary mt-3 col-auto" type="submit" value="Test">
            </form>
        </div>
        <div class="row mt-4">
            <?php
            //Mensaje de intentos
            if(!isset($_SESSION ["try"])) {
                $_SESSION ["try"] = 0;
            } else {
                echo "<p class='text-center text-warning'>Intento: " . $_SESSION ["try"] .  "</p>";
            }
            ?>
        </div>
    </div>
  </main>
</body>
</html>
<?php
    unset($_SESSION ["lastnumbers"]);
?>
