<?php
    header('X-UA-Compatible: IE=edge,chrome=1');
    include_once('include/function.php');
    @session_start();
?>
<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
    <title>Sistema de carga de datos de iMediaKeeper</title>
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
<?php
if($_POST){
    if ($_SESSION['rol'] == "admin") {
        if (isset($_POST['logout'])) {
            logout();
        } else {
            if ($_POST['path']!=""){
                function get_films_file($ruta){
                    $ruta = str_replace('\\\\', '\\', $ruta);
                    exec('dir '.$ruta.' /S /B > peliculas.txt',$output,$respuesta);
                    if ($respuesta == 0){
                        return true;
                    } else {
                        return false;
                    }
                }
                function get_film_data($nombre_fichero){
                    $file = fopen($nombre_fichero, "r") or exit("No es posible abrir el archivo");
                    $i = 0;
                    while(!feof($file)){
                        $lines[$i] = fgets($file);
                        $i++;
                    }
                    fclose($file);
                    $j=0;
                    foreach ($lines as $line) {
                        $ruta = explode("\\",$line);
                        $aux = explode(".",$ruta[count($ruta)-1]);
                        if (count($aux) == 2){
                            $aux[1] = str_replace("\r\n", "", $aux[1]);
                            $aux[0] = str_replace("IV - ", "", $aux[0]);
                            $aux[0] = str_replace("V - ", "", $aux[0]);
                            $aux[0] = str_replace("III - ", "", $aux[0]);
                            $aux[0] = str_replace("II - ", "", $aux[0]);
                            $aux[0] = str_replace("I - ", "", $aux[0]);
                            $aux[0] = str_replace("01 - ", "", $aux[0]);
                            $aux[0] = str_replace("02", "", $aux[0]);
                            $aux[0] = str_replace("03 - ", "", $aux[0]);
                            $aux[0] = str_replace("04 - ", "", $aux[0]);
                            $aux[0] = str_replace("05 - ", "", $aux[0]);
                            $aux[0] = str_replace("06 - ", "", $aux[0]);
                            $aux[0] = str_replace("07 - ", "", $aux[0]);
                            $aux[0] = str_replace("08 - ", "", $aux[0]);
                            $aux[0] = str_replace("09 - ", "", $aux[0]);
                            $aux[0] = str_replace("10 - ", "", $aux[0]);
                            $aux[0] = str_replace("11 - ", "", $aux[0]);
                            $aux[0] = str_replace("12 - ", "", $aux[0]);
                            $aux[0] = str_replace("13 - ", "", $aux[0]);
                            $aux[0] = str_replace("14 - ", "", $aux[0]);
                            $aux[0] = str_replace("15 - ", "", $aux[0]);
                            $aux[0] = str_replace("16 - ", "", $aux[0]);
                            $aux[0] = str_replace("17 - ", "", $aux[0]);
                            $aux[0] = str_replace("18 - ", "", $aux[0]);
                            $aux[0] = str_replace("19 - ", "", $aux[0]);
                            $aux[0] = str_replace("20 - ", "", $aux[0]);
                            $aux[0] = str_replace("21 - ", "", $aux[0]);
                            $aux[0] = str_replace("-", "", $aux[0]);
                            if (($aux[1]=='avi') OR ($aux[1]=='mpeg') OR ($aux[1]=='mov') OR ($aux[1]=='mpg') OR ($aux[1]=='wmv') OR ($aux[1]=='rm')){
                                if (strlen($aux[0])>0){
                                    $film_data[$j][0] = $aux[0];
                                    $film_data[$j][1] = 'sd';
                                    $j++;
                                }
                            } else if (($aux[1]=='mkv') OR ($aux[1]=='m2t') OR ($aux[1]=='mp4')){
                                if (strlen($aux[0])>0){
                                    $film_data[$j][0] = $aux[0];
                                    $film_data[$j][1] = 'hd';
                                    $j++;
                                }
                            }
                        }
                    }
                    return $film_data;
                }

                if (get_films_file($_POST['path'])){
                    if ($_POST['mode']=="1"){
                        //truncate_db();
                        exec('mysql -u root -pInt3c0 < imediakeeper.sql',$output,$respuesta);
                        $update = false;
                    } else {
                        $update = true;
                    }
                    $film_data = get_film_data('peliculas.txt');
                    $j=0;
                    for ($i=0;$i<count($film_data);$i++){
                        $result = init($film_data[$i][0],$film_data[$i][1],false,$update);

                        if ($result!='true'){
                            $fail[$j][0] = $result;
                            $fail[$j][1] = $film_data[$i][1];
                            $j++;
                        }

                    }
                    $n = 0;
                    for ($j=0;$j<count($fail);$j++){
                        $names[$j] = get_fail_names($fail[$j][0],$fail[$j][1]);
                        if ($names[$j]==null){
                            $no_data[$n] = str_replace('+', ' ', $fail[$j][0]);
                            $n++;
                        }
                    }

                    $names = clean_null_array($names);

                    if (isset($names)){ ?>
                    <section>
                        <div class="row-fluid">
                            <div class="span12">
                                <h4>Coincidencias para películas</h4>
                                <form method="post" action="load.php">
                                    <?php for ($k=0;$k<count($names);$k++){ ?>
                                    <?php if ($names[$k][0][2]!="") { ?>
                                    <p style="color:#8C8C8C">Se han encontrado varias coincidencias para la película: <strong><?= $names[$k][0][2]; ?></strong>. Seleccione la película correcta.</p>
                                        <?php for ($m=0;$m<count($names[$k]);$m++){ ?>
                                        <div class="" style="margin-left:2%;">
                                            <label class="radio">
                                                <input type="radio" id="radio<?= $k.$m; ?>" name="pelicula<?= $k; ?>" value="<?= $names[$k][$m][3]; ?>-<?= $names[$k][$m][0]; ?>" />
                                                <?= $names[$k][$m][1];?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    <?php } }?>
                                    <input class="btn btn-inverse" type="submit" value="Enviar" />
                                    <input type="hidden" value="<?= count($names); ?>" name="k" />
                                    <input type="hidden" value="<?= $_POST['mode']; ?>" name="update" />
                                </form>
                            </div>
                        </div>
                    </section>
                    <?php }
                    if (isset($no_data)){ ?>
                    <section>
                        <div class="row-fluid">
                            <div class="span12">
                                <h4>No se ha encontrado información de películas</h4>
                                <p style="color:#8C8C8C">No se ha conseguido información de los siguientes archivos. Compruebe que el nombre del archivo es correcto y vuelva a intentar la carga de películas.</p>
                                <ul>
                                    <?php foreach ($no_data as $name_no_data) { ?>
                                        <li><?= $name_no_data; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </section>
                    <?php } } else { ?>
                        <div class="alert alert-error">
                            <p>Existe un error en la ruta indicada.</p>
                        </div>
                    <?php }
            }

            if (isset($_POST['k'])){
                $k = $_POST['k'];
                if ($_POST['update']==0){
                    $update = true;
                } else {
                    $update = false;
                }
                for ($i=0;$i<$k;$i++){
                    if (!isset($_POST['pelicula'.$i]) || $_POST['pelicula'.$i]==""){

                    } else {
                        $aux = explode('-', $_POST['pelicula'.$i]);
                        init($aux[1],$aux[0],true,$update);
                    }
                } ?>
                <div class="alert alert-success">
                    <p>Se ha realizado la carga de datos de forma correcta.</p>
                </div>
            <?php }
            if ($_POST['url']!="") {
                init($_POST['url'],$_POST['format'],true,true); ?>
                <div class="alert alert-success">
                    <p>Se ha realizado la carga de datos de forma correcta.</p>
                </div>
            <?php }
        }
    } else {
        $error = "";
        $validate = login($_POST['user'], $_POST['pass']);
        if ($validate == false) {
            $error= 'Usuario o contraseña incorrectos.';
        }
    }
} ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <?php include_once('include/head.php'); ?>
            </div>
            <?php if ($_SESSION['rol'] == "admin") { ?>
            <div class="row-fluid">
                <div class="span12">
                    <form role="form" action="load.php" method="post" class="float-right">
                        <button type="submit" class="btn-inverse boton-redondo" id="logout" name="logout"><i class="icon-off icon-white"></i> Log out</button>
                    </form>
                    <h2 class="text-center">Carga de datos a base de datos</h2>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#automatica" data-toggle="tab">Carga automática</a></li>
                        <li><a href="#manual" data-toggle="tab">Carga manual</a></li>
                    </ul>
                    <div class="tab-content">
                        <section class="tab-pane active" id="automatica">
                            <h3>Carga automática</h3>
                            <form class="form-search" action="load.php" method="post">
                                <span class="help-block">Indique si desea actualizar la base de datos o hacer una nueva carga. (La primera vez se debe realizar una nueva carga)</span>
                                <select class="input" name="mode" id="mode">
                                    <option value="0">Actualización</option>
                                    <option value="1">Nueva carga</option>
                                </select>
                                <span class="help-block">Indique la ruta completa del directorio de películas. Por ejemplo: D:\Peliculas</span>
                                <div class="input-append">
                                    <input class="span10 search-query" type="text" id="path" name="path" />
                                    <button type="submit" class="btn btn-inverse"><i class="icon-play icon-white"></i> Iniciar</button>
                                </div>
                            </form>
                        </section>
                        <section class="tab-pane" id="manual">
                            <h3>Carga manual</h3>
                            <form class="form-search" action="load.php" method="post">
                                <span class="help-block">Indique la URL en filmaffinity.com de la película. Y el formato.</span>
                                <div class="input-append">
                                    <input class="span10 separation-right" type="text" name="url" id="url" />
                                    <select class="input  separation-right" name="format" id="format">
                                        <option value="hd">HD</option>
                                        <option value="sd">SD</option>
                                    </select>
                                    <button type="submit" class="btn btn-inverse"><i class="icon-play icon-white"></i> Buscar</button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <?php include_once('include/login.php'); ?>
            <?php } ?>
        <?php include_once('include/foot.php'); ?>
        </div>
    </div>
</body>
</html>
