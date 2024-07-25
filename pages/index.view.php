
<?php
require_once ("partials/head.php");
require_once ("partials/nav.php");

$titles = ["Loto Pool", "Loto", "Kino TV"];
$links = [root. 'LP/agregar', root. 'Loto/agregar', root. 'Kino/agregar'];
?>

    <main class="container p-4 my-4">
        <div class="text-center d-flex flex-column flex-md-row align-items-center justify-content-md-around"> 
        <?php
            for($i = 0; $i < count($titles); $i++) {
        ?>        
            <div class="card mb-2 mx-md-2" style="min-width: 12rem;">
                <div class="card-body">
                    <h5 class="card-title text-success"><?php echo $titles[$i];?></h5>
                    <a href="<?php echo $links[$i];?>" class="btn btn-primary my-3">Acceder</a>
                </div>
            </div>
        <?php
            }
        ?>
        </div>
    </main>
<?php
    require_once ("partials/footer.php")
?>
