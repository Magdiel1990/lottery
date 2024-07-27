<nav class="navbar navbar-expand-lg navbar-light bg-light px-2">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo root; ?>">Home</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?php echo root . 'estadistica'; ?>">Estadísticas</a>
      </li>
<!--
      <li class="nav-item">
        <a class="nav-link" id="reset" href="<?php //echo root . 'reset?reset=true'; ?>">Reset</a>
      </li>
-->
    </ul>
  </div>
</nav>
<!-- Reset confirmation -->
<script>
var reset = document.getElementById("reset");

reset.addEventListener("click", function() {
  var confirmReset = confirm("¿Estás seguro de que deseas eliminar todos los números?");
  if (confirmReset) {
    window.location.href = "/lottery/reset?reset=true";
  }
});
</script>