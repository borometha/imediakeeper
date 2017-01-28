imediakeeper

Cataloge your films automatically, including its production information, synopsis... in spanish from fifilmaffinity

iMediaKeeper es una herramienta que genera una web con los datos de todas las películas que tienes almacenadas en cualquier dispositivo que pueda ser accedido por un ordenador.

La herramienta escanea el directorio que se le indica y obtiene los nombres de los archivos de las películas, generando un listado en un archivo de texto.

Recorre el archivo de texto obteniendo la información de cada película, consultado en la web filmaffinity.com, y vuelva la información en una base de datos MySQL.

La web generada permite mostrar todas las películas o filtrar los resultados en función de la información de producción de la película (título, director, país, género,actor y año) y por el formato (HD o SD).

Desde la consola de administración se pueden incluir películas manualmente, indicando la URL de la película en filmaffinity.com, o lanzar el proceso de escaneo (para poder realizar este proceso, el servidor debe poder acceder al dispositivo en el que se encuentra el directorio de películas).

Para poder ejecutar esta herramienta es necesario: MySQL 5, PHP 5.5 y un servidor web.

NOTA: Este proyecto está actualmente en proceso de actualización del acceso a base de datos desde PHP. Actualmente el acceso a base de datos usa funcionalidades declaradas obsoletas en PHP 5.0 y eliminadas en PHP 7.0

(Translation by google translate) iMediaKeeper is a tool that generates a web with the data of all the movies that you have stored in any device that can be acceded by a computer.

The tool scans the directory you are given and gets the filenames of the movies, generating a list in a text file.

Scroll through the text file by getting the information of each movie, consulted on the web filmaffinity.com, and return the information in a MySQL database.

The generated web allows you to display all the films or filter the results according to the production information of the film (title, director, country, genre, actor and year) and by format (HD or SD).

From the management console you can include movies manually, indicating the URL of the movie on filmaffinity.com, or launch the scanning process (in order to perform this process, the server must be able to access the device in which the films).

To be able to execute this tool it is necessary: ​​MySQL 5, PHP 5.5 and a web server.

NOTE: This project is currently in the process of updating database access from PHP. Currently database access uses features declared obsolete in PHP 5.0 and removed in PHP 7.0
