
<?php
require ("../classes/Database.Class.php");
$conn = DatabaseClass::dbConnection();
require_once ("../classes/Random.Generators.Class.php");
require_once ("../classes/LP.Class.php");
include ("../partials/head.php");
include "../partials/nav.php";
?>
    <main class="container">
        <div class="text-center mt-4"> 
            <form action="" method="POST">
                <input type="hidden" value="ok" name="generate">
                <input class="btn btn-primary" type="submit" value="Generar">
            </form>
        </div>
        <div class="text-center mt-5 border">

        <?php 
        if(isset($_POST["generate"])){
            $finalArray = new LPClass();
            $numbers = $finalArray-> finalNumbers(15, 5, $conn, 0.02); 
            
            if(count($numbers) > 0) {
                for($i = 0; $i < count($numbers); $i++) {
        ?>  
                    <span class="mx-4"><?php echo $numbers[$i];?></span>                    
        <?php  
                }      
            }
        }
        ?>
        </div>
    </main>
<?php
    $conn -> close();
    require ("../partials/footer.php")
?>
