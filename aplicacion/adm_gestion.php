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
modificarlo bajo la Licencia P�blica General de GNU
en los t�rminos enque est� publicada por la Free Software Foundation; bien en la versi�n 2014
de la Licencia, o (a tu elecci�n) cualquier versi�n posterior.

Este programa se distribuye con la esperanza de que pueda resultar de utilidad,
pero SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita de tipo
COMERCIAL o de APLICABILIDAD A CUALQUIER PROP�SITO PARTICULAR. Lea
la Licencia P�blica General de GNU para obtener m�s detalles.

Deber�as haber recibido una copia de la Licencia P�blica General de GNU
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
* Una aplicaci�n vulnerable escrita en PHP / HTML
* Una serie de pruebas de concepto que permiten experimentar con las vulnerabilidades de dicha aplicaci�n. Algunas de estas pruebas contienen sus propias vulnerabilidades o generan malware
* Notas y listas de URLs a utilizar en la ejecuci�n de las pruebas de concepto

Ten en cuenta que:
* Bajo ninguna circunstancia se debe instalar una aplicaci�n que tenga o pudiera tener vulnerabilidades en un equipo usado para explotaci�n o en un sistema cuya seguridad se desee salvaguardar.

* El uso de t�cnicas de hacking puede constituir un delito seg�n la normativa aplicable en cada caso. Bajo ninguna circunstancia se debe utilizar este tipo de t�cnicas sin el consentimiento expreso y legalmente v�lido de las personas titulares de los equipos f�sicos y l�gicos que pudieran verse afectados.

* Bajo ninguna circunstancia se debe realizar ninguna actividad que pudiera suponer un incumplimiento de la normativa aplicable.

Cualquier persona que no se atuviera a lo expresado anteriormente lo har� bajo su propia responsabilidad y deber� atenerse a las consecuencias que pudieran tener sus actos.
*/


	require_once("cabecera.inc.php");
	require_once("db.inc.php");

	// Lectura de par�metros
	function post($nombre) {
		return isset($_POST[$nombre]) ? $_POST[$nombre] : "";
	}

	
	
	// Determinar si es cuenta b�sica o de administraci�n
	print "<h1>Cuentas ";
	if (isset($_GET["admin"]) and $_GET["admin"]==1) {
		$admin = 1;
		print "de Administraci&oacute;n</h1>";
	} else {
		$admin = 0;
		print "b&aacute;sicas</h1>";
	}

	// Determinar acci�n a realizar
	$orden = isset($_GET["orden"]) ? $_GET["orden"] : "menu";
	
	// Realizar acci�n
	switch($orden){
		case "menu":
			menu();
			break;
		case "borra":
			borra();
			break;
		case "edita":
			edita();
			break;
		default:
			print "Orden incorrecta";
	}

	// ACCIONES
	
	// Mostrar men�
	function menu() {
		global $admin;

		// Buscar las cuentas existentes
		$resultado = consulta(
			"select id, admin, nombre, descr from usuarios where admin = " . $admin
		);

		// Comprobar si hay cuentas
		$cuenta = numero_de_filas($resultado);
		if ($cuenta == 0) {
			print "NO HAY CUENTAS QUE ADMINISTRAR";
		} else {
			// Mostrar opciones
			
			print "<table border='1'>";
			print "<tr><td>NOMBRE</td><td>DESCRIPCI&Oacute;N</td><td colspan='2'>ACCIONES</td></tr>";
			
			for ($i=0;$i<$cuenta;$i++) {
					// Obtener una fila del resultado
					$fila = fila($resultado, $i);
					
					// ID de la fila actual
					$id = $fila["id"];
					
					// Mostrar opciones
					print "<tr>";
					print "<td>" . $fila["nombre"] . "</td>";
					print "<td>" . $fila["descr"] . "</td>";
					print "<td><a href='adm_gestion.php?orden=edita&admin=$admin&id=$id'>Editar</a></td>";
					print "<td><a href='adm_gestion.php?orden=borra&admin=$admin&id=$id'>Borrar</a></td>";
					print "</tr>";
			}
		}
		print "</table>";
		
		// Bot�n para a�adir
		print "<br><a href='adm_gestion.php?orden=edita&admin=$admin'>Nuevo<a>";
	}
	
	// Borrar cuenta
	function borra() {
		if (! isset($_GET["id"])) {
			print "ID incorrecto";
			
		} else {
			consulta(
				"delete from usuarios where id = " . $_GET["id"]
			);
		}
		menu();
	}
	
	// Altas y ediciones
	function edita() {
		global $admin;
		
		// Determinar si se rellen� el formulario
		if (isset($_POST["nombre"])) {
			// Leer datos del formulario
			$id = post("id");
			if ($id=="") { // Corregir
				$id = -1;
			}

			$nombre = post("nombre");
			$pwd = post("pwd");
			$descr = post("descr");
			
			
			// Determinar si es alta o edici�n
			if ($id > 0) { // Editar
				consulta(
					"update usuarios set " .
					"admin = $admin, " .
					"nombre = '$nombre', " .
					"pwd = '$pwd', " .
					"descr = '$descr' " .
					"where id = $id"
				);
			} else { // Alta
				consulta(
					"insert into usuarios (admin, nombre, pwd, descr) " .
					"values ($admin,'$nombre','$pwd','$descr')"
				);
			}
			
			menu();
		} else { // Si no se recibieron datos, mostrar formulario
			// Ver datos actuales del usuario a modificar, dependiendo de si es alta o edici�n
			if (isset($_GET["id"])) { // Editar
				$resultado = consulta(
					"select * from usuarios where id = $_GET[id]"
				);
				
				// Comprobar que el ID es correcto
				if (numero_de_filas($resultado) > 0) {
					// Obtener datos actuales
					$id = $_GET["id"];
					$fila = fila($resultado, 0);
					$nombre = $fila["nombre"];
					$pwd = $fila["pwd"];
					$descr = $fila["descr"];						
				} else {
					print "ID incorrecto";
					menu();
					return;
				}	
			} else { // En caso de alta, no hay datos previos
				$id= -1;
				$nombre = "";
				$pwd = "";
				$descr = "";
			}
			
			print "
				<form method='POST'>
					<input type='hidden' id='admin' name='admin' value='$admin'>
					<input type='hidden' id='id' name='id' value='$id'>
					
					<table>
						<tr><td>NOMBRE:</td><td><input type='text' id='nombre' name='nombre' value='$nombre'></td></tr>
						<tr><td>PASSWORD:</td><td><input type='text' id='pwd' name='pwd' value='$pwd'></td></tr>
						<tr><td>DESCRIPCI&Oacute;N:</td><td><input type='text' id='descr' name='descr' value='$descr'></td></tr>
					</table>
					
					<input type='submit' value='Enviar'>
				</form>
			";
		}
	}
	
?>