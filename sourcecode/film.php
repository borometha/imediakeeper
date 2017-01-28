<?php
    header('X-UA-Compatible: IE=edge,chrome=1');
    include_once('include/bd.php');
    include_once('include/function.php');
    conectar_bd();
    $datos = get_datos_pelicula($_GET['id']);
?>

<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
    <title>iMediaKeeper - <?= $datos['titulo']; ?></title>
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
                <div class="span12 text-center">
                    <section>
                        <?php $pelicula = get_pelicula($datos['id']); $https_caratula = get_https_caratula($pelicula['caratula']); ?>
                        <h2><?= $datos['titulo']; ?></h2>
                        <a href="#myModal1" role="button" data-toggle="modal"><img src="<?= $https_caratula ?>" alt="" class="img-polaroid" /></a>

                            <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">

                                <div class="modal-body">
                                    <img src="<?= get_large_image($https_caratula); ?>" alt="" />
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                                </div>
                            </div>
                    </section>
                </div>
            </div>
            <section>
                <div class="row-fluid">
                    <div class="span12">
                        <h3 class="h3-data">Ficha técnica</h3>
                    </div>
                </div>
                <div class="row-fluid">
                    <article>
                        <div class="span4">
                            <dl>
                                <dt class="data">Formato</dt>
                                    <dd><?= get_formato($pelicula['id_formato']); ?></dd>
                                <dt class="data">Año</dt>
                                    <dd><?= $datos['anno']; ?></dd>
                                <dt class="data">Duración</dt>
                                    <dd><?= $datos['duracion']; ?> minutos</dd>
                                <dt class="data">País</dt>
                                    <dd><?= get_pais($datos['id_pais']); ?></dd>
                                <dt class="data">Director</dt>
                                    <dd><?= get_director($datos['id_director']); ?></dd>

                            </dl>
                        </div>
                    </article>
                    <article>
                        <div class="span4">
                            <dl>
                                <dt class="data">Reparto:</dt>
                                    <dd>
                                        <?= get_reparto_str($pelicula); ?>
                                    </dd>
                                <dt class="data">Género:</dt>
                                    <dd>
                                        <?= get_generos_str($pelicula); ?>
                                    </dd>
                            </dl>
                        </div>
                    </article>
                    <article>
                        <div class="span4">
                            <p class="data">Sinopsis</p>
                            <blockquote>
                                <p><?= $datos['sinopsis']; ?></p>
                            </blockquote>
                        </div>
                    </article>
                </div>
            </section>
            <?php include_once('include/foot.php'); ?>
        </div>
    </div>
<?php desconectar_bd(); ?>
</body>
</html>
