<?php
	$aux = explode("/",$_SERVER['PHP_SELF']);
	$pag = $aux[count($aux)-1];
?>
<div class="navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand <?php if($pag=="index.php"){echo 'active';} ?>" href="index.php"><i class="icon-home icon-white"></i> iMediaKeeper</a>
    		<div class="nav-collapse collapse">
    			<nav>
			    	<ul class="nav">
			    		<li class="<?php if($pag=="search.php"){echo 'active';} ?>"><a href="search.php"><i class="icon-search icon-white"></i> Búsqueda avanzada</a></li>
			    		<li class="<?php if($pag=="load.php"){echo 'active';} ?>"><a href="load.php"><i class="icon-upload icon-white"></i> Carga de datos</a></li>
			    	</ul>
		    	</nav>
		    </div>
    	</div>
    </div>
</div>		