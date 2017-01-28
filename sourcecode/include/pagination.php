<?php if ($max < count($peliculas)){ ?>
<div class="pagination pagination-centered">
    <ul>
    	<?php if ($page > 1) { ?>
	    <li><a href="?page=<?= $page-1; ?>&amp;format=<?= $format; ?>&amp;max=<?= $max; ?>">Anterior</a></li>
	    <?php } else { ?>
	    <li class="disabled"><a href="#">Anterior</a></li>
	    <?php } ?>					    
	    <?php $valor = count($peliculas)/$max; for ($i = 1; $i <= ceil($valor); $i++) { ?>
		    <?php if ($page==$i){ ?>
		    <li class="active"><a href="#"><?= $i; ?></a></li>
		    <?php } else { ?>
		    <li><a href="?page=<?= $i; ?>&amp;format=<?= $format; ?>&amp;max=<?= $max; ?>"><?= $i; ?></a></li>
		    <?php } ?>
	    <?php } ?>
	    <?php if ($page < $valor) { ?>
	    <li><a href="?page=<?= $page+1; ?>&amp;format=<?= $format; ?>&amp;max=<?= $max; ?>">Siguiente</a></li>
	    <?php } else { ?>
	    <li class="disabled"><a href="#">Siguiente</a></li>
	    <?php } ?>
	</ul>
</div>
<?php } ?>