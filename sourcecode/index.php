<?php
    header('X-UA-Compatible: IE=edge,chrome=1');
    include_once("include/bd.php");
    include_once("include/function.php");
    conectar_bd();

    if ($_POST){
        if (isset($_POST['format'])){
            $selected = $_POST['format'];
            $format = $_POST['format'];
            $peliculas = get_all_peliculas_sorted('titulo',$format);
        }
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
            $selected = $_GET['format'];
            $format = $_GET['format'];
            $peliculas = get_all_peliculas_sorted('titulo',$format);
        } else {
            $peliculas = get_all_peliculas_sorted('titulo',0);
            $selected = 0;
        }
        $max = 50;
        if (!isset($_GET['page'])){
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        if (isset($_GET['max'])){
            $max = $_GET['max'];
        }

    }
    $total = count($peliculas);
?>

<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
    <title>iMediaKeeper</title>
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
                    <h2 class="text-center">Listado de películas</h2>
                    <section>
                        <form class="form-search" action="index.php" method="post">
                            <fieldset style="float:left; margin-right:3em;">
                                <legend>Ver</legend>
                                    <select class="input-small" name="format" id="format">
                                        <option value="0" <?php if ($selected==0){ echo 'selected="selected"';} ?>>Todas</option>
                                        <option value="1" <?php if ($selected==1){ echo 'selected="selected"';} ?>>HD</option>
                                        <option value="2" <?php if ($selected==2){ echo 'selected="selected"';} ?>>SD</option>
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
                                    <?php $datos_pelicula = get_datos_pelicula($pelicula['id_datos_pelicula']); $https_caratula = get_https_caratula($pelicula['caratula']);?>
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
