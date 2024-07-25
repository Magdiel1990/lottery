
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
        <div class="col-auto">
            <form action="" method="POST">
                <label for="numbers" class="form-label">Ingresa la jugada</label>                
                <?php
                    echo add_numbers_input($numbers, $balls);
                ?>
                <input class="btn btn-primary m-2" type="submit" value="Probar">
            </form>
        </div>
    </div>
</main>
<?php
    $conn -> close();
    require ("partials/footer.php")
?>