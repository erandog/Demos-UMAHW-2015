<?php
/*
Copyright (C) 2014 Enrique Rando

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

http://www.gnu.org/licenses/gpl-2.0.html

-------------------------------------------------------------------------
Este programa es software libre; puedes redistribuirlo y/o
modificarlo bajo la Licencia Pública General de GNU
en los términos enque está publicada por la Free Software Foundation; bien en la versión 2014
de la Licencia, o (a tu elección) cualquier versión posterior.

Este programa se distribuye con la esperanza de que pueda resultar de utilidad,
pero SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita de tipo
COMERCIAL o de APLICABILIDAD A CUALQUIER PROPÓSITO PARTICULAR. Lea
la Licencia Pública General de GNU para obtener más detalles.

Deberías haber recibido una copia de la Licencia Pública General de GNU
junto con este programa; en caso contrario, escribe a la Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

http://www.gnu.org/licenses/gpl-2.0.html
-------------------------------------------------------------------------
Please, read this carefully:

This package consists of:
* A vulnerable application written in PHP / HTML
* A series of proofs of concept that allow experimenting with the application vulnerabilities. Please note that some of these proofs of concept have their own vulnerabilities or generate malware.
* Notes and lists of URLs to use when running proofs of concept

Note that:
* Under no circumstances should you install an application that has or may have vulnerabilities on a computer that is used for production purpouses or on a system whose security must be protected.

* The use of hacking techniques may constitute an offense under the legislation applicable in each case. Under no circumstances should you use these techniques without the express consent of the legally valid holders of the hardware and software equipment that could be affected.

* Under no circumstances should you perform any activity that would constitute a breach of applicable law.

Anyone not complying with this text will do so at their own risk and must bear the consequences. 


-------------------------------------------------------------------------

Por favor, lea detenidamente lo siguiente:

Este paquete consta de:
* Una aplicación vulnerable escrita en PHP / HTML
* Una serie de pruebas de concepto que permiten experimentar con las vulnerabilidades de dicha aplicación. Algunas de estas pruebas contienen sus propias vulnerabilidades o generan malware
* Notas y listas de URLs a utilizar en la ejecución de las pruebas de concepto

Ten en cuenta que:
* Bajo ninguna circunstancia se debe instalar una aplicación que tenga o pudiera tener vulnerabilidades en un equipo usado para explotación o en un sistema cuya seguridad se desee salvaguardar.

* El uso de técnicas de hacking puede constituir un delito según la normativa aplicable en cada caso. Bajo ninguna circunstancia se debe utilizar este tipo de técnicas sin el consentimiento expreso y legalmente válido de las personas titulares de los equipos físicos y lógicos que pudieran verse afectados.

* Bajo ninguna circunstancia se debe realizar ninguna actividad que pudiera suponer un incumplimiento de la normativa aplicable.

Cualquier persona que no se atuviera a lo expresado anteriormente lo hará bajo su propia responsabilidad y deberá atenerse a las consecuencias que pudieran tener sus actos.
*/

// ¿Se bloquean las cuentas por sobrepasar cierto número de errores de login?
$bloqueo_por_errores = false;

// Número máximo de errores de login permitidos
$errores_bloqueo = 4;

// Funciones para redirigir a otras páginas
require_once("redirigir.inc.php");
require_once("db.inc.php");

// Usar sesiones de PHP
session_start();

// Ruta de instalación de la app
$carpeta_instalacion = preg_replace('/(.*\/).*/','${1}',$_SERVER["PHP_SELF"]);

// URL de destino si las credenciales son correctas
if (isset($_GET["url"])) {
	$destino = $_GET["url"];
} else {
	$destino = $carpeta_instalacion . "principal.php";
}

// ¿Se han introducido credenciales correctas ?
$login_correcto = false;

// Si se ha rellenado anteriormente el formulario, comprobar los datos
if (isset($_POST["nombre"])) { 
	// Sentencia SQL a ejecutar
    $sql = "select * from usuarios where nombre = '" .
		$_POST["nombre"] .
		"' and pwd = '" .
		$_POST["pwd"] . "'" ;

	// Ejecuta la consulta
    $resultado = consulta($sql);

    // Si hay filas, los datos de acceso eran correctos
    if (numero_de_filas($resultado) != 0) {
		// Obtener los datos del usuario logado
		$fila = fila($resultado, 0);
		
		// Comprobar si el usuario está bloqueado
		if (! $bloqueo_por_errores or $fila["errores"] < $errores_bloqueo) {
			// Todo OK
			$login_correcto = true;
				
			// Resetear cuenta de errores
			consulta(
				"update usuarios set errores = 0 where nombre = '" .
				$_POST["nombre"] .
				"'"
			);

			// Almacenar su ID y nombre en los datos de la sesión
			$_SESSION["id_usuario"] = (int)$fila["id"];
			$_SESSION["nombre_usuario"] = $fila["descr"];
			$_SESSION["admin"] = $fila["admin"];

			// Redirigir a página de destino
			// "Asegurándose" de que la ruta hace referencia al mismo servidor
			redirige("http://" . $_SERVER['SERVER_NAME'] . $destino);
		} else {
			print "USUARIO BLOQUEADO";
		}
	}
}

// Si no se ha iniciado la sesión, mostrar un formulario de logon
if (! $login_correcto) {
	// Incrementar cuenta de errores de acceso para el usuario
	if (isset($_POST["nombre"]) and $bloqueo_por_errores) {	
		$sql = "update usuarios set errores = errores + 1 where nombre = '" .
			$_POST["nombre"] .
			"' and errores < " .
			$errores_bloqueo;

		consulta($sql);
	}
	
	?>
	<!DOCTYPE html>
	<html>
	<head><title>Login</title></head>
	<body>
	<h1>Acceso al sistema de comunicaciones</h1>
	<form method="POST" autocomplete="on"
				action="login.php?url=<?php print $destino; ?>">
		<table>
		<tr><td colspan="2">Introduzca sus datos de acceso</td></tr>
		<tr>
			<td>Nombre:&nbsp;</td>
			<td><input type="text" name="nombre" id="nombre"></td>
		</tr>
		<tr>
			<td>Clave:&nbsp;</td>
			<td><input type="password" name="pwd" id="pwd"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align:right">
				<input type="submit" value="Enviar">
			</td>
		</tr>
		</table>
		<p />
	</form>
	</body>
	</html>
	<?php
}

?>