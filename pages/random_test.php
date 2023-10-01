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



  </main>
</body>
</html>
<?php
    $conn -> close();
?>