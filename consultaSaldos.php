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
				background-color:red;
			}

			div.menuSuperior{
				width:100%;
				height:15%;
				float:left;

			}	
		
			div.headerOpcionImpar{
				background-color:darkblue;
				height:100%;
				width:20%;
				float:left;
				color:white;
				font-size:25px;
				font-weight:bold;
				justify-content:center;
				align-items:center;
				display:flex;
			}	

			div.headerOpcionPar{
				background-color:lightblue;
				width:20%;
				height:100%;
				float:left;
				color:white;
				font-size:25px;
				font-weight:bold;
				justify-content:center;
				align-items:center;
				display:flex;
			}

			div.contenedorBody{
				height:100%;
				background-color:salmon;




			}

			div.interiorBody{
				background-color:white;
				width:50%;
				height:50%;
				left:25%;
				position:absolute;
				top:35%;


			}
			
		</style>
	</head>

	<body>
		<div class="contenedorPrincipal">
			<div class="menuSuperior">
				<div class="headerOpcionImpar">Logo</div>
				<div class="headerOpcionPar">Consulta de saldos</div>
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
							
							echo '<table>';
							echo '<tr>';
							echo '<th>Número cuenta</th>';
							echo '<th>Tipo</th>';
							echo '<th>Moneda</th>';
							echo '<th>Saldo</th>';
							echo '</tr>';
							while ($fila = pg_fetch_assoc($resultado)) {
								echo '<tr>';
  								echo '<td>' . $fila['nro_cuenta'] . '</td>';
  								echo '<td>' . $fila['tipo_cuenta'] . '</td>';
  								echo '<td>' . $fila['cod_moneda'] . '</td>';
  								echo '<td>' . $fila['saldo'] . '</td>';
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
			
			$(document).ready(function(){
				
				$("#divCerrarSesion").click(function(){
					
					$.ajax({
						type:"post",
						url:"./cerrarSesion.php",
						data:{},
						success:function(respuestaDelServer,estado){
							
						}
					});



				});
			});
		</script>

				
	</body>
</html>
