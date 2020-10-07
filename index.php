<?php
	
	if(count($_POST) > 0){

	include 'datosConexionBase.inc';

	 //establezco conexión con la base de datos
	$dbconn = pg_connect("host=" . HOST . " port=" . PORT . " dbname=" . BASE . " user=" . USUARIO . " password=" . PASS);

	//validar el usuario 
	$cons = "'" . $_POST['usuario'] . "'";
	$parte1 = 'SELECT * FROM public."USUARIOS" WHERE "nombre_usuarios" = ';
	
	//realizo la consulta
	$resultado = pg_query($dbconn, $parte1 . $cons);

	 if (!$resultado) {
		echo "Se produjo un error\n";
		exit;
	} else{

		if (pg_num_rows($resultado) == 1){

			$fila = pg_fetch_assoc($resultado);

			if (!$fila['pass'] == sha1($_POST['pass'])){

				echo "Usuario o contraseña incorrecta";
				exit();
			}

			if (!$fila['cod_estado'] == 'A'){

				echo "El usuario no se encuentra activo";
				exit();

			}

			echo "Usuario correcto";

		}else{

			echo "Usuario o contraseña incorrecta 2";

		}

		//echo '<script language="javascript">';
		//echo 'alert("GRACIAS POR TU PREGUNTA! SI TENES OTRA NO DUDES EN ENVIARLA.")';
		//echo '</script>';
	}


	 pg_close($dbconn);
	}


?>




<!DOCTYPE html>

<style>
	 html, body{
            height:100%;
            width:100%;
            margin:0%;
        }
        

        div.general{

        	
        	font-size: 25px;
        	text-align: center;
        	box-sizing: border-box;
        	padding-top: 5%;
            margin: auto;
            height: 50%;           
            width:50%;
            background: linear-gradient(0deg, darkblue, darkcyan); 
           
        }

        img.logo{

			width: 20%;
			margin: center;
			padding-top: 2%;
			padding-left: 1%;
		}

		button{
              
                width: 130px;
                height: 60px;          
                margin: auto;
            }
        input{
        	
                width: 300px;
                height: 20px;          
                margin: auto;

        }
    
</style>

<html>



	<head>
		<title>Iniciar sesión</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

		
	</head>



	<body background="fondo.jpg">

		<img src="logo.png" class="logo">

		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>


		<div class="general">
			<form action="" method="post">
				<label>Usuario</label>
				<br>
				<input type="text" name="usuario" id="usuario" autofocus="autofocus" required="required" title="Ingrese su usuario">
				<br>
				<br>
				<label>Contraseña</label>
				<br>
				<input type="password" name="pass" id="pass" required="required" title="Ingrese su contraseña">
				<br>
				<br>

				<button type="submit">Iniciar sesión</button>
			</form>
		</div>
		
	
				
	</body>
</html>
