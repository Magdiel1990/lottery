
<?php
require_once ("partials/head.php");
require_once ("partials/nav.php");
?>

    <main class="container p-4">
        <div class="text-center mt-4 d-flex justify-content-around align-items-center"> 
            <div class="card" style="width: 14rem;">
                <div class="card-body">
                    <h5 class="card-title text-success">Loto Pool</h5>
                    <a href="<?php echo root. 'LP/agregar'?>" class="btn btn-primary my-3">Acceder</a>
                </div>
            </div>
            <div class="card" style="width: 14rem;">
                <div class="card-body">
                    <h5 class="card-title text-success">Loto</h5>
                    <a href="<?php echo root. 'Loto/agregar'?>" class="btn btn-primary my-3">Acceder</a>
                </div>
            </div>
            <div class="card" style="width: 14rem;">
                <div class="card-body">
                    <h5 class="card-title text-success">Kino TV</h5>
                    <a href="<?php echo root. 'Kino/agregar'?>" class="btn btn-primary my-3">Acceder</a>
                </div>
            </div>
        </div>
    </main>
<?php
    require_once ("partials/footer.php")
?>
