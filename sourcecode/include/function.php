<?php
    include_once ("simple_html_dom.php");
    include_once ("bd.php");
    function getActores($actores){
        $all = explode(',', $actores);
        $k = 0;
        foreach ($all as $actor) {
            echo 'actor '.$k.': '.$actor.'<br />';
            $k++;
        }
    }

    function login($user, $pass) {
        $login = false;
        conectar_bd();
        $id = checkUser($user,$pass);
        if ($id != -1) {
            $rol = getRol($id);
            if ($rol != false) {
                $_SESSION['rol'] = $rol;
                $login = true;
            }
        }
        desconectar_bd();

        return $login;
    }

    function logout(){
        unset($_SESSION['rol']);
        session_write_close();
    }

    function init($film, $formato,$directo,$update){
        if (!$directo){
            $film = str_replace(' ', '+', $film);

            $html = file_get_html('http://www.filmaffinity.com/es/search.php?stext='.$film);
        } else {
            $html = file_get_html($film);
        }

        $title = $html->find('#main-title span',0)->plaintext;

        if ($title != ""){

            $lista = $html->find('#left-column',0)->find('dl[class="movie-info"]');

            $i=0;
            foreach ($lista as $nombres) {
                $nombre = $nombres->find('dt');
                foreach ($nombre as $dato) {
                    $aux = html_entity_decode($dato->plaintext,ENT_COMPAT | ENT_HTML401,'UTF-8');
                    if ($aux=="Año"){
                        $anno = $dato->next_sibling()->plaintext;
                        $anno = trim($anno);
                    }
                    if ($aux=="Duración"){
                        $duracion = $dato->next_sibling()->plaintext;
                        $aux = substr($duracion, 0,3);
                        if (!is_numeric($aux)){
                            $aux = substr($duracion, 0,2);
                            if (!is_numeric($aux)){
                                $aux = substr($duracion, 0,1);
                            }
                        }
                        $duracion = trim($aux);
                    }
                    if ($aux=="País"){
                        $pais = $dato->next_sibling()->plaintext;
                        $pais = str_replace('&nbsp;','',$pais);
                        $pais = trim($pais);
                    }
                    if ($aux=="Director"){
                        $director = $dato->next_sibling()->first_child()->plaintext;
                        $director = trim($director);
                    }
                    if ($aux=="Reparto"){
                        #puede ser multiple
                        $total = $dato->next_sibling()->children;
                        for ($i=0; $i < count($total); $i++) {
                            $reparto[$i] = trim($dato->next_sibling()->children($i)->plaintext);
                        }
                    }
                    if ($aux=="Género"){
                        #puede ser multiple
                        $total = $dato->next_sibling()->children;
                        for ($i=0; $i < count($total); $i++) {
                            $generos[$i] = trim($dato->next_sibling()->children($i)->plaintext);
                        }
                    }
                    if ($aux=="Sinopsis"){
                        $sinopsis = $dato->next_sibling()->plaintext;
                        $sinopsis = str_replace(' (FILMAFFINITY)', '', $sinopsis);
                    }

                    $i++;
                }
            }

            if ($html->find('#movie-main-image-container',0)->first_child()->first_child()==null){
                $caratula="img/no_image.jpg";
            } else {
                $caratula = $html->find('#movie-main-image-container',0)->first_child()->first_child()->src;
            }




            /*echo '<p>Titulo: '.$title.'</p>';
            echo '<p>Año: '.$anno.'</p>';
            echo '<p>Duración: '.$duracion.'</p>';
            echo '<p>País: '.$pais.'</p>';
            echo '<p>Director: '.$director.'</p>';
            echo '<p>Reparto:</p>';
            echo '<ul>';
            foreach ($reparto as $actor) {
                echo '<li>'.$actor.'</li>';
            }
            echo ('</ul>');
            echo '<p>Género:</p>';
            echo '<ul>';
            foreach ($generos as $genero) {
                echo '<li>'.$genero.'</li>';
            }
            echo ('</ul>');
            echo '<p>Sinopsis: '.$sinopsis.'</p>';
            echo '<p>Carátula: '.$caratula.'</p>';*/

            conectar_bd();
            alta_pelicula($title,$anno,$duracion,$pais,$director,$sinopsis,$caratula,$generos,$reparto,$formato,$update);
            desconectar_bd();
            return 'true';
        } else {
            return $film;
        }

    }

    function get_fail_names($film,$formato){

        $html = file_get_html('http://www.filmaffinity.com/es/search.php?stext='.$film);

        $button = $html->find('div[class="see-all-button"]',0)->plaintext;
        if ($button!=""){
            $href = 'http://www.filmaffinity.com/es/'.($html->find('div[class="see-all-button"]',0)->parent()->href);
            $html = file_get_html($href);
        }
        $titles = $html->find('div[class="mc-title"]');

        $i=0;
        foreach ($titles as $title) {
            $ask[$i][0] = 'http://www.filmaffinity.com'.$title->first_child()->href;
            $ask[$i][1] = $title->plaintext;
            $ask[$i][2] = str_replace('+', ' ', $film);
            $ask[$i][3] = $formato;
            $i++;
        }
        return $ask;
    }

    function truncate_db(){
        truncate();
    }


    function get_generos_str($pelicula){
        $generos = get_all_generos_by_id($pelicula['id_datos_pelicula']);
        $result = "";
        for ($i=0;$i<count($generos);$i++){
            if ($i==count($generos)-1){
                $result .= $generos[$i];
            } else {
                $result .= $generos[$i]." - ";
            }
        }

        return $result;
    }

    function get_reparto_str($pelicula){
        $reparto = get_all_actores_by_id($pelicula['id_datos_pelicula']);
        $result = "";
        for ($i=0;$i<count($reparto);$i++){
            if ($i==count($reparto)-1){
                $result .= $reparto[$i];
            } else {
                $result .= $reparto[$i]." - ";
            }
        }

        return $result;
    }

    function get_large_image($image){
        $result = '';
        if ($image == 'img/no_imag.jpg'){
            $result = $image;
        } else {
            $result = str_replace("-main.", "-large.", $image);
        }

        return $result;
    }

    function clean_null_array($array){
        for ($i=0;$i<=count($array);$i++){
            if ($array[$i]==null){
                unset($array[$i]);
            }
        }

        sort($array);

        return $array;
    }

    function get_https_caratula($caratula) {
        $https_caratula = substr_replace($caratula, 's', 4, 0);

        return $https_caratula;
    }
?>
