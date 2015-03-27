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


require_once("cabecera.inc.php"); 
require_once("db.inc.php"); 


// Extrae el valor de un parámetro POST
// En caso de no existir, devuelve ""
function parametro($nombre) {
	return isset($_POST[$nombre]) ? $_POST[$nombre] : "" ;
}

// Comprobar si se han recibido datos del formulario
if (isset($_POST["destino"])) {
	// Obtener los valores del formulario
	$destino = parametro("destino");
	$asunto = parametro("asunto");
	$texto = parametro("texto");
	$enlace = parametro("enlace");

	// Subir fichero
	if (isset($_FILES["fichero"]) && $_FILES["fichero"]["error"] != UPLOAD_ERR_NO_FILE) {
		// Poner un valor aleatorio al principio del nombre de fichero para evitar 
		// sobreescibir otros de igual nombre y pasarlos a la carpeta uploads
		$nombre = "uploads/" . rand() . "_" . $_FILES["fichero"]["name"] ;

		// Informar del estado de la subida en caso de error
		if ($_FILES["fichero"]["error"]>0) {
			$upload_errors = array( 
			UPLOAD_ERR_OK        => "OK.", 
			UPLOAD_ERR_INI_SIZE    => "Superado upload_max_filesize.", 
			UPLOAD_ERR_FORM_SIZE    => "Superado el MAX_FILE_SIZE del formulario.", 
			UPLOAD_ERR_PARTIAL    => "Subida no completada.", 
			UPLOAD_ERR_NO_FILE        => "Sin fichero.", 
			UPLOAD_ERR_NO_TMP_DIR    => "Falta directorio temporal.", 
			UPLOAD_ERR_CANT_WRITE    => "Imposible escribir a disco.", 
			UPLOAD_ERR_EXTENSION     => "Extensi&oactue;n no permitida.", 
		 	 );

			print "ERROR en Fichero: " . htmlentities($upload_errors[$_FILES["fichero"]["error"]]) . "<br>";
		}

		// Mover el fichero a su ubicación final
		move_uploaded_file($_FILES["fichero"]["tmp_name"], $nombre);
	} else { 
		// Dejar vacío el nombre de fichero
		$nombre = "";
	}
	
	// Insertar mensaje en la base de datos
	consulta(<<<EOT
		insert into mensajes(remitente, destino, asunto, texto, enlace, fichero) 
		values 
		($_SESSION[id_usuario], $destino, '$asunto', '$texto', '$enlace', '$nombre')
EOT
	);
	
	print "<h3>Mensaje Enviado</h3>";	
}
?>

<script>
	function carga_descripcion() {
		ruta = window.location.href;
		
		tdescripcion = ruta.split("?")[1];
		if (tdescripcion) {
			document.getElementById("descripcion").innerHTML = decodeURIComponent(tdescripcion);
		}
	}
</script>

<body onload="carga_descripcion()">
<h1 id="descripcion">&nbsp;</h1>
<form method="POST" enctype="multipart/form-data">
	<table>
		<tr><td>Destino:</td>
			<td>
				<select id="destino" name="destino">
				<?php
					// Lista los usuarios como opciones del select
					$res = consulta("select id, nombre from usuarios");
					$n = numero_de_filas($res);
					for ($i=0; $i<$n; $i++) {
						$fila = fila($res, $i);
						print "<option value='$fila[id]'>$fila[nombre]</option>";
					}
				?>
				</select>
			</td>
		</tr>
		<tr><td>Asunto:</td><td><input type="text" id="asunto" name="asunto" size="40"></td></tr>
		<tr><td>Texto:</td><td><input type="text" id="texto" name="texto" size="90"></td></tr>
		<tr><td>Enlace (URL):</td><td><input type="text" id="enlace" name="enlace" size="90"></td></tr>
		<tr><td>Fichero:</td><td><input type="file" id="fichero" name="fichero"></td></tr>
		<tr><td>&nbsp;</td><td><input type="submit" value="Enviar"></td></tr>
	</table>
</form>
</body>