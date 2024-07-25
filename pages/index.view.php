
<?php
require_once ("partials/head.php");
require_once ("partials/nav.php");

$titles = ["Loto" => root. 'loto/test'];
?>
    <main class="container p-4 my-4">
        <div class="text-center d-flex flex-column flex-md-row align-items-center justify-content-md-around"> 
        <?php
            foreach($titles as $title => $link) { 
        ?>        
            <div class="card mb-2 mx-md-2" style="min-width: 12rem;">
                <div class="card-body">
                    <h5 class="card-title text-success"><?php echo $title;?></h5>
                    <a href="<?php echo $link;?>" class="btn btn-primary my-3">Test</a>
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
