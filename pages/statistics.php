<?php
require "../classes/Database.Class.php";
$conn = DatabaseClass::dbConnection();

include "../partials/head.php";
?>

    <main class="container">
        <?php
            include "../partials/nav.php";
        ?>  
        <div class="text-center mt-4"> 




        

            
        </div>       
    </main>
</body>
</html>
<?php
    $conn -> close();
?>