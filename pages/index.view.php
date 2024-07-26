
<?php
//Include the head
require_once ("partials/head.php");

//Include the nav
require_once ("partials/nav.php");

//Links for the navigation
$titles = ["Loto" => [root. 'loto/test', root. 'loto/agregar']];

?>
    <main class="container p-4 my-4">
        <div class="text-center d-flex flex-column flex-md-row align-items-center justify-content-md-around"> 
        <?php
        //Print the links
            foreach($titles as $title => $link) { 
        ?>        
            <div class="card mb-2 mx-md-2" style="min-width: 12rem;">
                <div class="card-body">
                    <h5 class="card-title text-success"><?php echo $title;?></h5>
                    <a href="<?php echo $link [0];?>" class="btn btn-primary my-3">Test</a>
                    <a href="<?php echo $link [1];?>" class="btn btn-primary my-3">Add</a>
                </div>
            </div>
        <?php
            }
        ?>
        </div>
    </main>
<?php
//Include the footer
    require_once ("partials/footer.php")
?>
