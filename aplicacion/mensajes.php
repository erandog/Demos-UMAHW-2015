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

include("sesion.inc.php");
include("db.inc.php");

// Determinar qué se pide...
$orden = isset($_GET["orden"]) ? $_GET["orden"] : "formulario";

// ... y actuar en consecuencia
switch ($orden) {
	case "formulario";
		include("cabecera.inc.php");
		formulario();
		break;
	case "lista_mensajes":
		lista_mensajes();
		break;
	case "carga_mensaje":
		if (isset($_GET["id"])) {
			carga_mensaje($_GET["id"]);
		} else {
			print "Se necesita identificador de mensaje";
		}
		break;
	case "borra_mensaje":
		if (isset($_GET["id"])) {
			borra_mensaje($_GET["id"]);
		} else {
			print "Se necesita identificador de mensaje";
		}
		break;
}


// Lista los mensajes del usuario actual
function lista_mensajes(){
	$res = consulta(<<<EOT
		select mensajes.id as id, usuarios.nombre as remite, asunto
		from mensajes, usuarios
		where destino = $_SESSION[id_usuario]
		and mensajes.remitente = usuarios.id
EOT
	);
	
	// Número de mensajes obtenidos
	$n = numero_de_filas($res);
	
	// Tabla usada para formatear los datos
	print "<table style='width:100%'>";
	print "<tr><td>REMITENTE</td><td>ASUNTO</td><td>ACCIONES</td></tr>";
	
	// Mostrar el resumen de cada mensaje, asociando las acciones de
	// cargar en el panel de detalle y de eliminar
	for ($i=0; $i<$n; $i++) {
		$reg = fila($res, $i);
		
		print <<<EOT
		<tr>
			<td onclick="carga_mensaje('$reg[id]')" style="width:33%;border:solid">
				$reg[remite]
			</td><td onclick="carga_mensaje('$reg[id]')"  style="width:33%;border:solid">
				$reg[asunto]
			</td><td>
				<button onclick="borra_mensaje('$reg[id]')">
					Borrar
				</button>
			</td>
		</tr>
EOT;
	}
	
	print "</table>";
}


// Proporciona la información de detalle de un mensaje
// Parámetro: $id -> id del mensaje
function carga_mensaje($id) {
	// Obtener el registro
	$res = consulta(<<<EOT
		select mensajes.id as id, usuarios.descr as remite, asunto, texto, enlace, fichero 
		from mensajes, usuarios
		where mensajes.id = $id
		and mensajes.remitente = usuarios.id		
EOT
	);

	// Formatear los datos
	if (numero_de_filas($res) > 0) {
		$resultado = fila($res, 0);
		$resultado["enlace"] = "<a href='$resultado[enlace]'>$resultado[enlace]</a>";
		$resultado["fichero"] = "<a href='$resultado[fichero]'>$resultado[fichero]</a>";
	} else {
		$resultado = array("asunto" => "<h3>Mensaje no encontrado</h3>");
	}

	// Enviar en formato JSON
	// El cliente lo evaluará con "eval"
	print json_encode($resultado);
}


// Borra un mensaje y muestra la lista de los mensajes restantes
// Parámetro: $id -> id del mensaje a borrar
function borra_mensaje($id) {
	consulta("delete from mensajes where id = $id");
	lista_mensajes();
}

// Muestra el formulario de la parte cliente
// Para evitar confusiones, las funciones JavaScript que realizan peticiones 
// tienen el mismo nombre que sus contrapartidas en la parte servidora
function formulario() {
	print <<<EOT
		<script>
			// Crea un objeto para realizar peticiones HTTP
			function crea_peticion() {
				var peticion;
				
				if (window.XMLHttpRequest) {
					// IE7+, Firefox, Chrome, Opera, Safari
					peticion = new XMLHttpRequest();
				} else {
					// IE6, IE5
					peticion =new ActiveXObject("Microsoft.XMLHTTP");
				}
				return peticion;
			}

			// Pide la lista de mensajes y la muestra en pantalla
			function lista_mensajes() {
				// Preparar petición XMLHTTPRequest
				peticion = crea_peticion();
				
				// Acciones a realizar cuando se reciba respuesta
				peticion.onreadystatechange = function () {
					if (peticion.readyState==4 && peticion.status==200) {
						document.getElementById("lista").innerHTML = peticion.responseText;
					}
				}
				
				// Enviar petición
				peticion.open("GET", "mensajes.php?orden=lista_mensajes", true);
				peticion.send();
			
			}
			
			// Pide los datos de un mensaje y los muestra en pantalla
			function carga_mensaje(id) {
				// Preparar petición XMLHTTPRequest
				peticion = crea_peticion();
				
				// Acciones a realizar cuando se reciba respuesta
				peticion.onreadystatechange = function () {
					if (peticion.readyState==4 && peticion.status==200) {
						tmp = eval("("+peticion.responseText+")");
						for (indice in tmp) {
							document.getElementById(indice).innerHTML = tmp[indice];
						}
					}
				}
				
				// Enviar petición
				peticion.open("GET", "mensajes.php?orden=carga_mensaje&id=" + id, true);
				peticion.send();
			}
			
			// Borra un mensaje y recarga la lista de mensajes
			function borra_mensaje(id) {
				// Preparar petición XMLHTTPRequest
				peticion = crea_peticion();
				
				// Acciones a realizar cuando se reciba respuesta
				peticion.onreadystatechange = function () {
					if (peticion.readyState==4 && peticion.status==200) {
						document.getElementById("lista").innerHTML = peticion.responseText;
						
						// Si el mensaje mostrado en el detalle era el borrado, limpiar la pantalla
						if (document.getElementById("id").innerHTML == id) {
							document.getElementById("asunto").innerHTML = '&nbsp';
							document.getElementById("remite").innerHTML = '&nbsp';
							document.getElementById("texto").innerHTML = '&nbsp';
							document.getElementById("enlace").innerHTML = '&nbsp';
							document.getElementById("fichero").innerHTML = '&nbsp';
							document.getElementById("id").innerHTML = '&nbsp';
						}
					}
				}
				
				// Enviar petición
				peticion.open("GET", "mensajes.php?orden=borra_mensaje&id=" + id, true);
				peticion.send();	
			}
		</script>
		
		<html>
		<head><title>Panel de Mensajes</title></head>
		<body onload="lista_mensajes()">
			<h1>Mensajes Recibidos</h1>
			<p><a href="envia_mensaje.php?Componer Mensaje"
					target="_blank">Escribir un mensaje</a></p>
			<p />
			<table style="width:100%">
				<tr>
					<td style="border:solid;width:30%;vertical-align:top">
						<div style="background-color:lightblue">Mensajes</div>
						<div id="lista">&nbsp;</div>
					</td>
					<td style="border:solid;width:70%;vertical-align:top">
						<div id="id" style="display:none"></div>
						<h1 style="background-color:lightblue;text-align:center">
							ASUNTO: <span id="asunto">&nbsp;</span>
						</h1>
						<h2 style="background-color:lightgreen;text-align:center">
							REMITENTE: <span id="remite">&nbsp;</span>
						</h2>
						<br>TEXTO:<br><br>
						<div id="texto">&nbsp</div>
						<br>
						<table style="border:solid;width:100%">
							<tr>
								<td style="text-align:right">
									Enlace:
								</td><td>
									<div id="enlace">&nbsp;</div>
								</td>
							</tr><tr>
								<td style="text-align:right">
									Fichero:
								</td><td>
									<div id="fichero">&nbsp;</div>
								</td>
							</tr>
						</table>
					</td>
				<tr>
			</table>
			<button onclick="lista_mensajes()">Recargar</button>
		</body>
		</html>
EOT;
}
?>