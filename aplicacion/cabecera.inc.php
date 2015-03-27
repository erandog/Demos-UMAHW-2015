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


require_once("sesion.inc.php");

if (isset($_SESSION["id_usuario"])) {
	?>
	<table style='width:100%'>
		<tr>
			<td style='width:30%'>Usuario logado: <?php print $_SESSION["nombre_usuario"]; ?></td>
			<td style='text-align:center;width:30%'>
				<a href="principal.php">Men&uacute;</a>
			</td>
			<td style='text-align:right;width:30%'>
				<a href="?logout=1">Cerrar sesi&oacute;n</a>
			</td>
		</tr>
	</table>
	<?php
}
?>
<hr>