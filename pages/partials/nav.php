<?php
//Links for the navigation
$navitems = [
  'Home' => root,
  'Estadísticas'  => root . 'estadistica',
  'Reset' => root . 'reset?reset=true'
];
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-2">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <?php
      //Print the links
      foreach ($navitems as $key => $value) {
        echo '<li class="nav-item">';
        echo '<a class="nav-link" href="' . $value . '">' . $key . '</a>';
        echo '</li>';
      }
      ?>
    </ul>
  </div>
</nav>
