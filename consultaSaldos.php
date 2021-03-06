<?php


	session_start();

	if(!isset($_SESSION['logueado'])){
		header("Location: index.php");
		exit;
	}

	include 'datosConexionBase.inc';

	//establezco conexión con la base de datos
	$dbconn = pg_connect("host=" . HOST . " port=" . PORT . " dbname=" . BASE . " user=" . USUARIO . " password=" . PASS);


	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	//-------------- ACA HARDCODEO EL ID DE USUARIO ----------------
	//$_POST['usuario'] = 'pepe';
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


	$cons = "'" . $_SESSION['usuario'] . "'";

	$parte1 = 'SELECT "nro_cuenta" , "tipo_cuenta", "cod_moneda", "saldo"
  				FROM public."CUENTAS" 
			   WHERE "id_cuenta" IN (SELECT "id_cuenta"
					     FROM public."CUENTAS_USUARIOS"
					    WHERE "id_usuario" in (SELECT "id_usuario"
											     FROM public."USUARIOS"
											    WHERE "nombre_usuario" = ';

	$parte2 = '))';

	

	//realizo la consulta
	$resultado = pg_query($dbconn, $parte1 . $cons . $parte2);


	if (!$resultado) {
			echo "Ocurrió un error.\n";
			exit;
	}

	//pg_close($dbconn);


?>



<!DOCTYPE html>

<html>



	<head>
		<title>Consulta de saldos</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

		<style>

			html, body{
				height:100%;
				width:100%;
				margin:0%;
				
			}

			div.contenedorPrincipal{
				height:100%;
				width:100%;
			
			}

			div.menuSuperior{
				width:100%;
				height:10%;
				float:left;

			}	
		
			div.headerOpcionImpar{
				background-color:rgb(47, 92, 215);
				height:100%;
				width:20%;
				font-family: Helvetica;
				float:left;
				color:white;
				font-size:120%;
				font-weight: bold;
				justify-content:center;
				align-items:center;
				display:flex;
			
			}	

			div.headerOpcionPar{
				background-color:rgb(74, 112, 215);
				width:20%;
				height:100%;
				float:left;
				color:white;
				font-family: Helvetica;
				font-size:120%;
				font-weight:bold;
				justify-content:center;
				align-items:center;
				display:flex;
	
			}

			div.contenedorBody{
				height:100%;
			}

			div.interiorBody{
				background-color:lightblue;
				width:60%;
				height:55%;
				left:20%;
				position:absolute;
				top:30%;


			}
			img{
				height: 50%;
				width: 80%;
			}

			[campo-dato='c1']{
			width:25%; 
			}
			[campo-dato='c2']{
			width:25%; 
			}
			[campo-dato='c3']{
			width:25%; 
			}
			[campo-dato='c4']{
			width:25%; 
			}

			#cuentas{
				border-collapse: collapse;
  				width: 100%;
			}

			#cuentas td, #cuentas th{
				border: 1px solid #ddd;
			}

			#cuentas tr:nth-child(even){background-color: #f2f2f2;}

			#cuentas tr:hover {background-color: #ddd;}

			#cuentas th {
			  padding-top: 12px;
			  padding-bottom: 12px;
			  text-align: left;
			  background-color: #4CAF50;
			  color: white;
			}

			
		</style>
	</head>

	<body background="fondo.jpg">
		<div class="contenedorPrincipal" >
			<div class="menuSuperior">
				<div class="headerOpcionImpar"><img src="logo.png"></div>
				<div class="headerOpcionPar" id="divConsultaSaldos">Consulta de saldos</div>
				<div class="headerOpcionImpar">Tarjetas</div>
				<div class="headerOpcionPar">Transferencias</div>
				<div class="headerOpcionImpar" id="divCerrarSesion">Cerrar sesión</div>
			</div>
			<div class="contenedorBody">
				<div class="interiorBody">
					
					<?php

						if (pg_num_rows($resultado) == 0){
							echo "No posee cuentas";
						} else{
							
							echo '<table id="cuentas">';
							echo '<tr>';
							echo '<th campo-dato="c1">Número cuenta</th>';
							echo '<th campo-dato="c2">Tipo</th>';
							echo '<th campo-dato="c3">Moneda</th>';
							echo '<th campo-dato="c4">Saldo</th>';
							echo '</tr>';
							while ($fila = pg_fetch_assoc($resultado)) {
								echo '<tr>';
  								echo '<td campo-dato="c1">' . $fila['nro_cuenta'] . '</td>';
  								echo '<td campo-dato="c2">' . $fila['tipo_cuenta'] . '</td>';
  								echo '<td campo-dato="c3">' . $fila['cod_moneda'] . '</td>';
  								echo '<td campo-dato="c4">' . $fila['saldo'] . '</td>';
  								echo '</tr>';
							}
							echo '</table>';


						}

						
					?>

				</div>
			</div>
		</div>

	<script src="./jquery.js"></script>
	<script type="text/javascript">
			
			document.getElementById("divConsultaSaldos").onmouseover = function(){			
				document.getElementById("divConsultaSaldos").style.cursor = "pointer";
			}

			document.getElementById("divConsultaSaldos").onmouseout = function(){			
				document.getElementById("divConsultaSaldos").style.cursor = "auto";
			}


			document.getElementById("divCerrarSesion").onmouseover = function(){			
				document.getElementById("divCerrarSesion").style.cursor = "pointer";
			}

			document.getElementById("divCerrarSesion").onmouseout = function(){			
				document.getElementById("divCerrarSesion").style.cursor = "auto";
			}

			$(document).ready(function(){
				
				$("#divConsultaSaldos").click(function(){
					
					window.location.href="consultaSaldos.php";

				});

				$("#divCerrarSesion").click(function(){
					
					$.ajax({
						type:"post",
						url:"./cerrarSesion.php",
						data:{},
						success:function(respuestaDelServer,estado){
							window.location.href="index.php";	
						}
					});



				});
			});
		</script>

				
	</body>
</html>