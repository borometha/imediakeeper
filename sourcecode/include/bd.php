<?php
include_once('function.php');
/********************** Funciones para la conexión de la base de datos *************************/
/**
*    Función de conexión a la base de datos.
*/
function conectar_bd () {
    /*$localhost = "localhost:3306";
    $openHost = OPENSHIFT_MYSQL_DB_HOST;
    $host = $openHost | $localhost;
    $user = "root";
    $password = "Int3c0";*/
    //mysql_connect("127.4.52.130:3306", "adminPY9NTaL", "2TFHhG9rPtfk");
    mysql_connect("localhost:3306", "root", "Int3c0");
    //mysql_connect($host, $user, $password);
    mysql_select_db("imediakeeper");
    mysql_set_charset('utf8');
}

/**
*    Función que desconecta de la base de datos.
*/
function desconectar_bd() {
    mysql_close();
}

/**
*    Función que manda la consulta a la base de datos.
*    @param consulta La consulta.
*    @return El array obtenido de la consulta.
*/
function sql_query($consulta) {
    $res=mysql_query($consulta);
    return $res;
}

/**
*    Función que inicia el comienzo de una transacción de mysql, que permite realizar
*    COMMIT y UPDATE del conjunto de transacciones realizadas.
*/
function start_transaction(){
    $res=mysql_query("START TRANSACTION");
    return $res;
}

/**
*    Función que realiza un COMMIT del conjunto de transacciones realizadas después
*    de un START TRANSACTION.
*/
function commit(){
    $res=mysql_query("COMMIT");
    return $res;
}

/**
*    Función que realiza un ROLLBACK del conjunto de transacciones realizadas después
*    de un START TRANSACTION.
*/
function rollback(){
    $res=mysql_query("ROLLBACK");
    return $res;
}

/********************** FIN Funciones para la conexión de la base de datos **********************/

/********************** Funciones para el login en la aplicacion **************************/

/**
 * Comprueba si el usuario y la contraseña son correctos.
 * @param   String   $user nombre de usuario en base de datos.
 * @param   String   $pass password del usuario en base de datos.
 * @returns Integer Si es correcto devuelve el id del usuario en base de datos, si no es correcto devuelve -1.
 */
function checkUser ($user, $pass) {
    $id = false;
    $consulta = 'SELECT id FROM login WHERE user = "'.$user.'" AND password = "'.sha1($pass).'"';
    $row = mysql_fetch_array(sql_query($consulta));
    if ($row['id'] == false) {
        $id = -1;
    } else {
        $id = $row['id'];
    }
    return $id;
}

/**
 * Devuelve el rol del usuario que hace login.
 * @param   Integer $id Id del usuario en base de datos.
 * @returns String Rol del usuario.
 */
function getRol ($id) {
    $rol = "";
    $consulta = 'SELECT rol FROM login WHERE id = "'.$id.'"';
    $rolId = mysql_fetch_array(sql_query($consulta));
    if ($rolId['rol'] != false){
        $consulta = 'SELECT rol FROM rol WHERE id = "'.$rolId['rol'].'"';
        $rol = mysql_fetch_array(sql_query($consulta));
        $rol = $rol['rol'];
    } else {
        $rol = false;
    }
    return $rol;
}

/********************** FIN Funciones para el login en la aplicacion **********************/

function isPais($pais){
    $consulta = 'SELECT id FROM pais WHERE pais = "'.$pais.'"';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row;
}

function isDirector($director){
    $consulta = 'SELECT id FROM director WHERE director = "'.$director.'"';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row;
}

function isGenero($genero){
    $consulta = 'SELECT id FROM genero WHERE genero = "'.$genero.'"';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row;
}

function isActor($actor){
    $consulta = 'SELECT id FROM actor WHERE actor = "'.$actor.'"';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row;
}

function existsFilm($titulo, $director,$id_formato){
    //$consulta = 'SELECT id FROM director WHERE director ="'.$director.'"';

    $consulta = 'SELECT id FROM datos_pelicula WHERE titulo = "'.$titulo.'"';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }
    if ($registro!= null){
        $id_director = get_id_director($director);
        if ($id_director!=null){
            $peliculas = get_peliculas_by_director_format($id_director,$id_formato);
            foreach ($peliculas as $pelicula) {
                for ($i=0;$i<count($registro);$i++){
                    if ($pelicula['id_datos_pelicula']==$registro[$i]['id']){
                        return true;
                    }
                }
            }
        }
    }

    return false;
}

function alta_pelicula($titulo,$anno,$duracion,$pais,$director,$sinopsis,$caratula,$generos,$reparto,$formato,$update){

    // Si el usuario ha indicado que quiere actualización se debo comprobar si la película con ese nombre,
    // ese formato y ese director ya existe en base de datos. En ese caso no incluirla.
    if (!$update){
        $idPais = isPais($pais);
        $idDirector = isDirector($director);

        if ($idPais!=false AND $idDirector!=false){
            foreach ($generos as $genero) {
                if (!isGenero($genero)){
                    alta_genero($genero);
                }
            }
            $i=0;
            foreach ($generos as $genero) {
                $generos_id[$i]= get_genero($genero) ;
                $i++;
            }

            foreach ($reparto as $actor) {
                if (!isActor($actor)){
                    alta_actor($actor);
                }
            }
            $i=0;
            foreach ($reparto as $actor) {
                $actores_id[$i]= get_actor($actor) ;
                $i++;
            }
        }
        #si idPais es false entonces no existe el pais por lo que primero hay que darle de alta, obtner su id e incluir en la tabla datos_pelicula
        #si idPais es distinto de false, entoces su valor es el id que tiene que ir en la tabla datos_peliculas
        #lo mismo con idDirector

        if ($idPais!=false AND $idDirector!=false){
            $consulta = 'INSERT INTO datos_pelicula (titulo,anno,duracion,id_pais,id_director,sinopsis) VALUES("'.$titulo.'",'.$anno.','.$duracion.','.$idPais['id'].','.$idDirector['id'].',"'.$sinopsis.'")';
            $res = sql_query($consulta);
            $id = mysql_insert_id();
            foreach ($generos_id as $genero_id) {
                alta_genero_pelicula($genero_id,$id);
            }
            foreach ($actores_id as $actor_id) {
                //echo 'actor: '.$actor_id.' pelicula: '.$id.'\n';
                alta_reparto($actor_id,$id);
            }

            $id_formato = get_id_formato($formato);
            $consulta = 'INSERT INTO pelicula (codigo,id_datos_pelicula,caratula,id_formato) VALUES ("'.get_formato($id_formato).$id.'",'.$id.',"'.$caratula.'",'.$id_formato.')';
            $res = sql_query($consulta);


        } else {
            if ($idPais==false AND $idDirector==false){
                alta_pais($pais);
                alta_director($director);
                alta_pelicula($titulo,$anno,$duracion,$pais,$director,$sinopsis,$caratula,$generos,$reparto,$formato,$update);
            } else if ($idPais==false AND $idDirector!=false){
                alta_pais($pais);
                alta_pelicula($titulo,$anno,$duracion,$pais,$director,$sinopsis,$caratula,$generos,$reparto,$formato,$update);
            } else if ($idPais!=false AND $idDirector==false){
                alta_director($director);
                alta_pelicula($titulo,$anno,$duracion,$pais,$director,$sinopsis,$caratula,$generos,$reparto,$formato,$update);
            }

        }
    } else {
        $id_formato = get_id_formato($formato);
        if (!existsFilm($titulo,$director,$id_formato)){
            $update = false;
            alta_pelicula($titulo,$anno,$duracion,$pais,$director,$sinopsis,$caratula,$generos,$reparto,$formato,$update);
        }
    }
}

function datos_pelicula(){
    $consulta = 'SELECT * FROM datos_pelicula WHERE id=5';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row;
}

function alta_pais($pais){
    $consulta = 'INSERT INTO pais (pais) VALUES ("'.$pais.'")';

    sql_query($consulta);
}

function alta_director($director){
    $consulta = 'INSERT INTO director (director) VALUES ("'.$director.'")';

    sql_query($consulta);
}

function alta_genero($genero){
    $consulta = 'INSERT INTO genero (genero) VALUES ("'.$genero.'")';

    sql_query($consulta);
}

function alta_actor($actor){
    $consulta = 'INSERT INTO actor (actor) VALUES ("'.$actor.'")';

    sql_query($consulta);
}

function get_datos_pelicula($id){
    $consulta = 'SELECT * FROM datos_pelicula WHERE id='.$id;
    $row = mysql_fetch_array(sql_query($consulta));

    return $row;
}

function get_genero($genero){
    $consulta = 'SELECT id FROM genero WHERE genero="'.$genero.'"';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['id'];
}

function get_genero_name($id){
    $consulta = 'SELECT genero FROM genero WHERE id='.$id;
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['genero'];
}

function get_all_generos_by_id($id_datos_pelicula){
    $consulta = 'SELECT id_genero FROM genero_pelicula WHERE id_datos_pelicula='.$id_datos_pelicula;
    $row = sql_query($consulta);

    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = get_genero_name($reg[0]);
        $i++;
    }

    return $registro;
}

function get_all_generos(){
    $consulta = 'SELECT * FROM genero ORDER BY genero';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    return $registro;
}
function get_actor($actor){
    $consulta = 'SELECT id FROM actor WHERE actor="'.$actor.'"';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['id'];
}

function get_actor_name($id){
    $consulta = 'SELECT actor FROM actor WHERE id='.$id;
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['actor'];
}

function get_all_actores_by_id($id_datos_pelicula){
    $consulta = 'SELECT id_actor FROM reparto WHERE id_datos_pelicula='.$id_datos_pelicula;
    $row = sql_query($consulta);

    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = get_actor_name($reg[0]);
        $i++;
    }

    return $registro;
}

function get_all_actores(){
    $consulta = 'SELECT * FROM actor ORDER BY actor';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    return $registro;
}

function get_all_annos(){
    $consulta = 'SELECT DISTINCT(anno) FROM datos_pelicula ORDER BY anno';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }


    return $registro;
}

function alta_genero_pelicula($genero, $pelicula){
    $consulta = 'INSERT INTO genero_pelicula (id_genero, id_datos_pelicula) VALUES ('.$genero.','.$pelicula.')';

    sql_query($consulta);
}

function alta_reparto($actor, $pelicula){
    $consulta = 'INSERT INTO reparto (id_datos_pelicula, id_actor) VALUES ('.$pelicula.','.$actor.')';

    sql_query($consulta);
}

function get_all_titulos(){
    $consulta = 'SELECT * FROM datos_pelicula ORDER BY titulo';

    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    return $registro;
}

function get_all_peliculas(){
    $consulta = 'SELECT * FROM pelicula';

    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    return $registro;
}

function get_all_peliculas_sorted($orden,$formato){
    if ($formato == 0){
        $consulta = 'SELECT id FROM datos_pelicula ORDER BY '.$orden;
        $row = sql_query($consulta);
        $i=0;
        while ($reg = mysql_fetch_array($row)){
            $registro[$i] = $reg;
            $i++;
        }

        $i=0;
        foreach ($registro as $id) {
            $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'];
            $row2[$i] = mysql_fetch_array(sql_query($consulta));
            $i++;
        }

        return $row2;
    } else {
        $consulta = 'SELECT id FROM datos_pelicula ORDER BY '.$orden;
        $row = sql_query($consulta);
        $i=0;
        while ($reg = mysql_fetch_array($row)){
            $registro[$i] = $reg;
            $i++;
        }

        $i=0;
        foreach ($registro as $id) {
            $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'].' AND id_formato ='.$formato;
            $aux = mysql_fetch_array(sql_query($consulta));
            if ($aux!=false){
                $row2[$i] = $aux;
                $i++;
            }

        }


        return $row2;
    }
}

function get_peliculas_by_name($titulo){
    $consulta = 'SELECT id FROM datos_pelicula WHERE titulo = "'.$titulo.'"';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    $i=0;
    if ($registro != null){
        foreach ($registro as $id) {
            $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'];
            $row2[$i] = mysql_fetch_array(sql_query($consulta));
            $i++;
        }
    }

    return $row2;
}

function get_director($id){
    $consulta = 'SELECT director FROM director WHERE id='.$id;
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['director'];
}

function get_all_director(){
    $consulta = 'SELECT * FROM director ORDER BY director';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    return $registro;
}

function get_id_director($director){
    $consulta = 'SELECT id FROM director WHERE director="'.$director.'"';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['id'];
}

function get_pais($id){
    $consulta = 'SELECT pais FROM pais WHERE id='.$id;
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['pais'];
}

function get_all_paises(){
    $consulta = 'SELECT * FROM pais ORDER BY pais';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    return $registro;
}

function get_formato($id){
    $consulta = 'SELECT formato FROM formato WHERE id='.$id;
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['formato'];
}

function get_id_formato($formato){
    $consulta = 'SELECT id FROM formato WHERE formato="'.strtoupper($formato).'"';
    $row = mysql_fetch_array(sql_query($consulta));

    return $row['id'];
}

function get_pelicula($id_datos_pelicula){
    $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula='.$id_datos_pelicula;
    $row = mysql_fetch_array(sql_query($consulta));

    return $row;
}

function get_peliculas_by_id_format($id_datos_pelicula,$id_formato){
    if ($id_formato!=-1){
        $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula='.$id_datos_pelicula.' AND id_formato = '.$id_formato;
    } else {
        $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula='.$id_datos_pelicula;
    }

    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        if ($reg != false){
            $registro[$i] = $reg;
            $i++;
        }
    }

    return $registro;

}
function get_peliculas_by_director_format($id_director,$id_formato){
    $consulta = 'SELECT id FROM datos_pelicula WHERE id_director = '.$id_director.' ORDER BY titulo';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    $i=0;
    if ($registro != null){
        foreach ($registro as $id) {
            if ($id_formato!=-1){
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'].' AND id_formato = '.$id_formato;
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            } else {
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'];
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            }

        }
    }

    return $row2;
}

function get_peliculas_by_pais_format($id_pais,$id_formato){
    $consulta = 'SELECT id FROM datos_pelicula WHERE id_pais = '.$id_pais.' ORDER BY titulo';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    $i=0;
    if ($registro != null){
        foreach ($registro as $id) {
            if ($id_formato!=-1){
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'].' AND id_formato = '.$id_formato;
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            } else {
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'];
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            }

        }
    }

    return $row2;
}

function get_peliculas_by_genero_format($id_genero,$id_formato){
    $consulta = 'SELECT id_datos_pelicula FROM genero_pelicula WHERE id_genero ='.$id_genero;
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    $i=0;
    if ($registro != null){
        foreach ($registro as $id) {
            if ($id_formato!=-1){
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id_datos_pelicula'].' AND id_formato = '.$id_formato;
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            } else {
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id_datos_pelicula'];
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            }

        }
    }

    return $row2;
}

function get_peliculas_by_actor_format($id_actor,$id_formato){
    $consulta = 'SELECT id_datos_pelicula FROM reparto WHERE id_actor ='.$id_actor;
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    $i=0;
    if ($registro != null){
        foreach ($registro as $id) {
            if ($id_formato!=-1){
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id_datos_pelicula'].' AND id_formato = '.$id_formato;
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            } else {
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id_datos_pelicula'];
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            }

        }
    }

    return $row2;
}

function get_peliculas_by_year_format($anno,$id_formato){
    $consulta = 'SELECT id FROM datos_pelicula WHERE anno = '.$anno.' ORDER BY titulo';
    $row = sql_query($consulta);
    $i=0;
    while ($reg = mysql_fetch_array($row)){
        $registro[$i] = $reg;
        $i++;
    }

    $i=0;
    if ($registro != null){
        foreach ($registro as $id) {
            if ($id_formato!=-1){
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'].' AND id_formato = '.$id_formato;
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            } else {
                $consulta = 'SELECT * FROM pelicula WHERE id_datos_pelicula ='.$id['id'];
                $aux = mysql_fetch_array(sql_query($consulta));
                if ($aux!=false){
                    $row2[$i] = $aux;
                    $i++;
                }
            }

        }
    }

    return $row2;
}

function truncate(){
    /*conectar_bd();
    $consulta ='TRUNCATE TABLE `actor`, `datos_pelicula`, `director`, `genero`, `genero_pelicula`, `pais`, `pelicula`, `reparto`';
    $consulta ='TRUNCATE TABLE actor';
    sql_query($consulta);
    $consulta ='TRUNCATE TABLE datos_pelicula';
    sql_query($consulta);
    $consulta ='TRUNCATE TABLE director';
    sql_query($consulta);
    $consulta ='TRUNCATE TABLE genero';
    sql_query($consulta);
    $consulta ='TRUNCATE TABLE genero_pelicula';
    sql_query($consulta);
    $consulta ='TRUNCATE TABLE pais';
    sql_query($consulta);
    $consulta ='TRUNCATE TABLE pelicula';
    sql_query($consulta);
    $consulta ='TRUNCATE TABLE reparto';
    sql_query($consulta);
    desconectar_bd();*/
    echo 'truncate';
    exec('mysql -u root -pInt3c0 < ../imediakeeper.sql',$output,$respuesta);
    echo $respuesta;
    var_dump($output);
    if ($respuesta == 0){
        return true;
    } else {
        return false;
    }

}
?>
