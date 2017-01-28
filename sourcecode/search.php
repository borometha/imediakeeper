<?php
    header('X-UA-Compatible: IE=edge,chrome=1');
    include_once('include/bd.php');
    conectar_bd();
    $selected_title = -1;
    $selected_director = -1;
    $selected_country = -1;
    $selected_gener = -1;
    $selected_actor = -1;
    $selected_year = -1;
    $selected_format = -1;
    if ($_POST){
        if ($_POST['title']!=-1){
            $peliculas = get_peliculas_by_id_format($_POST['title'],$_POST['format']);
            $selected_title = $_POST['title'];
        }
        if ($_POST['director']!=-1){
            $peliculas = get_peliculas_by_director_format($_POST['director'],$_POST['format']);
            $selected_director = $_POST['director'];
        }
        if ($_POST['country']!=-1){
            $peliculas = get_peliculas_by_pais_format($_POST['country'],$_POST['format']);
            $selected_country = $_POST['country'];
        }
        if ($_POST['gener']!=-1){
            $peliculas = get_peliculas_by_genero_format($_POST['gener'],$_POST['format']);
            $selected_gener = $_POST['gener'];
        }
        if ($_POST['actor']!=-1){
            $peliculas = get_peliculas_by_actor_format($_POST['actor'],$_POST['format']);
            $selected_actor = $_POST['actor'];
        }
        if ($_POST['year']!=-1){
            $peliculas = get_peliculas_by_year_format($_POST['year'],$_POST['format']);
            $selected_year = $_POST['year'];
        }
        if ($_POST['title']==-1 && $_POST['director']==-1 && $_POST['country']==-1 && $_POST['gener']==-1 && $_POST['actor']==-1 && $_POST['year']==-1){
            $peliculas = get_all_peliculas_sorted('titulo',0);
        }
        $selected_format = $_POST['format'];
        $format = $_POST['format'];
        /*if (isset($_POST['format'])){
            echo 'manuel';
            $selected_format = $_POST['format'];
            $format = $_POST['format'];
            if ($format!=-1){
                $peliculas = get_all_peliculas_sorted('titulo',$format);
            } else {
                $peliculas = get_all_peliculas_sorted('titulo',0);
            }
        } else {
            echo 'pepe';
            $peliculas = get_all_peliculas_sorted('titulo',0);
            $selected_format = -1;
            $format = 0;
        }*/
        if (isset($_POST['number'])) {
            if ($_POST['number']!=-1) {
                $max = $_POST['number'];
            } else {
                $max = count($peliculas);
            }
        }
        $page = 1;
    } else {
        if (isset($_GET['format'])){
            $selected_format = $_GET['format'];
            $format = $_GET['format'];
            if ($format!=-1){
                $peliculas = get_all_peliculas_sorted('titulo',$format);
            } else {
                $peliculas = get_all_peliculas_sorted('titulo',0);
            }
        } else {
            $peliculas = get_all_peliculas_sorted('titulo',0);
            $selected_format = -1;
            $format = 0;
        }
        if (!isset($_GET['page'])){
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        if (isset($_GET['title']) && $_GET['title']!=-1){
            $peliculas = get_peliculas_by_id_format($_GET['title'],$_GET['format']);
            $selected_title = $_GET['title'];
        }
        if (isset($_GET['director']) && $_GET['director']!=-1){
            $peliculas = get_peliculas_by_director_format($_GET['director'],$_GET['format']);
            $selected_director = $_GET['director'];
        }
        if (isset($_GET['country']) && $_GET['country']!=-1){
            $peliculas = get_peliculas_by_pais_format($_GET['country'],$_GET['format']);
            $selected_country = $_GET['country'];
        }
        if (isset($_GET['gener']) && $_GET['gener']!=-1){
            $peliculas = get_peliculas_by_genero_format($_GET['gener'],$_GET['format']);
            $selected_gener = $_GET['gener'];
        }
        if (isset($_GET['actor']) && $_GET['actor']!=-1){
            $peliculas = get_peliculas_by_actor_format($_GET['actor'],$_GET['format']);
            $selected_actor = $_GET['actor'];
        }
        if (isset($_GET['year']) && $_GET['year']!=-1){
            $peliculas = get_peliculas_by_year_format($_GET['year'],$_GET['format']);
            $selected_year = $_GET['year'];
        }
        $max = 50;
        if (isset($_GET['max'])){
            $max = $_GET['max'];
        }
    }
    $total = count($peliculas);
?>
<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
    <title>iMediaKeeper - Búsqueda avanzada</title>
    <?php include_once('include/meta.php'); ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-61056727-2', 'auto');
        ga('send', 'pageview');

    </script>
</head>
<body>
<div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <?php include_once('include/head.php'); ?>
            </div>

            <div class="row-fluid">
                <div class="span12">
                    <h2 class="text-center">Búsqueda avanzada de películas</h2>
                    <section>
                        <form class="form-search lineared" action="search.php" method="post">
                            <fieldset>
                                <legend>Buscar película</legend>
                                    <span class="help-block">Selecciona un criterio para la búsqueda.</span>
                                    <ul class="unstyled inline">
                                        <li>
                                            <label for="title">Título</label>
                                            <select class="input-small" name="title" id="title">
                                                <option value="-1">...</option>
                                                <?php $titulos = get_all_titulos();
                                                    foreach ($titulos as $titulo) { ?>
                                                         <option value="<?= $titulo['id']; ?>" <?php if ($selected_title==$titulo['id']){ echo 'selected="selected"';} ?>><?= $titulo['titulo']; ?></option>
                                                    <?php } ?>
                                            </select>
                                        </li>
                                        <li>
                                            <label for="director">Director</label>
                                            <select class="input-small" name="director" id="director">
                                                <option value="-1">...</option>
                                                <?php $directores = get_all_director();
                                                    foreach ($directores as $director) { ?>
                                                         <option value="<?= $director['id']; ?>" <?php if ($selected_director==$director['id']){ echo 'selected="selected"';} ?>><?= $director['director']; ?></option>
                                                    <?php } ?>
                                            </select>
                                        </li>
                                        <li>
                                            <label for="country">País</label>
                                            <select class="input-small" name="country" id="country">
                                                <option value="-1">...</option>
                                                <?php $paises = get_all_paises();
                                                    foreach ($paises as $pais) { ?>
                                                         <option value="<?= $pais['id']; ?>" <?php if ($selected_country==$pais['id']){ echo 'selected="selected"';} ?>><?= $pais['pais']; ?></option>
                                                    <?php } ?>
                                            </select>
                                        </li>
                                        <li>
                                            <label for="gener">Género</label>
                                            <select class="input-small" name="gener" id="gener">
                                                <option value="-1">...</option>
                                                <?php $generos = get_all_generos();
                                                    foreach ($generos as $genero) { ?>
                                                         <option value="<?= $genero['id']; ?>"<?php if ($selected_gener==$genero['id']){ echo 'selected="selected"';} ?>><?= $genero['genero']; ?></option>
                                                    <?php } ?>
                                            </select>
                                        </li>
                                        <li>
                                            <label for="actor">Actor</label>
                                            <select class="input-small" name="actor" id="actor">
                                                <option value="-1">...</option>
                                                <?php $actores = get_all_actores();
                                                    foreach ($actores as $actor) { ?>
                                                         <option value="<?= $actor['id']; ?>" <?php if ($selected_actor==$actor['id']){ echo 'selected="selected"';} ?>><?= $actor['actor']; ?></option>
                                                    <?php } ?>
                                            </select>
                                        </li>
                                        <li>
                                            <label for="year">Año</label>
                                            <select class="input-small" name="year" id="year">
                                                <option value="-1">...</option>
                                                <?php $annos = get_all_annos();
                                                    foreach ($annos as $anno) { ?>
                                                         <option value="<?= $anno['anno']; ?>" <?php if ($selected_year==$anno['anno']){ echo 'selected="selected"';} ?>><?= $anno['anno']; ?></option>
                                                    <?php } ?>
                                            </select>
                                        </li>
                                    </ul>
                            </fieldset>
                            <fieldset style="float:left; margin-right:3em;">
                                <legend>Ver</legend>
                                    <select class="input-small" name="format" id="format">
                                        <option value="-1" <?php if ($selected_format==-1){ echo 'selected="selected"';} ?>>Todas</option>
                                        <option value="1" <?php if ($selected_format==1){ echo 'selected="selected"';} ?>>HD</option>
                                        <option value="2" <?php if ($selected_format==2){ echo 'selected="selected"';} ?>>SD</option>
                                    </select>
                            </fieldset>
                            <fieldset style="float:left;">
                                <legend>Número de películas por página</legend>
                                    <select class="input-small" name="number" id="number">
                                        <option value="-1">...</option>
                                        <?php $number = count($peliculas)/5; for ($i = 1; $i <= ceil($number); $i++) {?>
                                        <option value="<?= $i*5; ?>" <?php if (($max==$i*5) || (($max>$i*5) && ($i==ceil($number)))){ echo 'selected="selected"';} ?>><?= $i*5 ?></option>
                                        <?php } ?>
                                    </select>
                                    <p>De <?php echo $total; ?></p>
                            </fieldset>
                        </form>
                    </section>
                </div>
            </div>
            <section>
                <ul id="og-grid" class="unstyled og-grid">
                    <?php if ($peliculas != null){
                            $limit = ($max * $page) - 1;
                            $init = $limit - ($max - 1);
                            if ((count($peliculas)-1)<$limit){
                                $limit = count($peliculas)-1;
                            }
                            for ($i = $init; $i <= $limit; $i++) { $pelicula = $peliculas[$i];?>
                                <li>
                                    <?php $datos_pelicula = get_datos_pelicula($pelicula['id_datos_pelicula']); $https_caratula = get_https_caratula($pelicula['caratula']); ?>
                                    <a href="film.php?id=<?= $pelicula['id_datos_pelicula']; ?>" data-largesrc="<?= $https_caratula; ?>" data-title="<?= $datos_pelicula['titulo']; ?>" data-description="<p><?php echo $datos_pelicula['titulo']; ?></p><p><?php echo get_director($datos_pelicula['id_director']); ?></p><p><?php echo get_pais($datos_pelicula['id_pais']); ?></p><p><?php echo get_generos_str($pelicula); ?></p><p><?php echo get_formato($pelicula['id_formato']); ?></p>">
                                        <img src="<?= $https_caratula; ?>" alt="" class="img-polaroid img-size" />
                                    </a>
                                </li>
                            <?php } } else { ?>
                            <div class="alert alert-error">
                                <p>No se ha encontrado ninguna película.</p>
                            </div>
                            <?php } ?>
                </ul>
            </section>
            <?php if ($max < count($peliculas)){ ?>
            <section>
                <div class="span12">
                    <?php include_once('include/pagination.php'); ?>
                </div>
            </section>
            <?php } ?>
            <?php include_once('include/foot.php'); ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/grid.js"></script>
    <script>
        $(function() {
            Grid.init();
        });
    </script>
    <script type="text/javascript">
        $('#title').change(
            function(){
                $("#director option[value=-1]").attr("selected",true);
                $("#country option[value=-1]").attr("selected",true);
                $("#gener option[value=-1]").attr("selected",true);
                $("#actor option[value=-1]").attr("selected",true);
                $("#year option[value=-1]").attr("selected",true);
                $("#format option[value=-1]").attr("selected",true);
                $(this).closest('form').trigger('submit');
            });
        $('#director').change(
            function(){
                $("#title option[value=-1]").attr("selected",true);
                $("#country option[value=-1]").attr("selected",true);
                $("#gener option[value=-1]").attr("selected",true);
                $("#actor option[value=-1]").attr("selected",true);
                $("#year option[value=-1]").attr("selected",true);
                $("#format option[value=-1]").attr("selected",true);
                $(this).closest('form').trigger('submit');
            });
        $('#country').change(
            function(){
                $("#title option[value=-1]").attr("selected",true);
                $("#director option[value=-1]").attr("selected",true);
                $("#gener option[value=-1]").attr("selected",true);
                $("#actor option[value=-1]").attr("selected",true);
                $("#year option[value=-1]").attr("selected",true);
                $("#format option[value=-1]").attr("selected",true);
                $(this).closest('form').trigger('submit');
            });
        $('#gener').change(
            function(){
                $("#title option[value=-1]").attr("selected",true);
                $("#country option[value=-1]").attr("selected",true);
                $("#director option[value=-1]").attr("selected",true);
                $("#actor option[value=-1]").attr("selected",true);
                $("#year option[value=-1]").attr("selected",true);
                $("#format option[value=-1]").attr("selected",true);
                $(this).closest('form').trigger('submit');
            });
        $('#actor').change(
            function(){
                $("#title option[value=-1]").attr("selected",true);
                $("#country option[value=-1]").attr("selected",true);
                $("#gener option[value=-1]").attr("selected",true);
                $("#director option[value=-1]").attr("selected",true);
                $("#year option[value=-1]").attr("selected",true);
                $("#format option[value=-1]").attr("selected",true);
                $(this).closest('form').trigger('submit');
            });
        $('#year').change(
            function(){
                $("#title option[value=-1]").attr("selected",true);
                $("#country option[value=-1]").attr("selected",true);
                $("#gener option[value=-1]").attr("selected",true);
                $("#actor option[value=-1]").attr("selected",true);
                $("#director option[value=-1]").attr("selected",true);
                $("#format option[value=-1]").attr("selected",true);
                $(this).closest('form').trigger('submit');
            });
        $('#format').change(
            function(){
                $(this).closest('form').trigger('submit');
            });
        $('#number').change(
            function(){
                 $(this).closest('form').trigger('submit');
            });
    </script>
<?php desconectar_bd(); ?>
</body>
</html>
