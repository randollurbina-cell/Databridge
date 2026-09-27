<?php
include "conexion.php"; // Este codigo corresponde al frontend de registro de usuario, en este caso se utiliza PHP para conectarse a la base de datos y obtener los datos de las tablas Empresa y Cargo para mostrarlos en los select del formulario de registro. Si no hay empresas o cargos registrados, se mostrará un mensaje indicando que no hay registros y html para desarrollar la interfaz //
?>

<!DOCTyPE HTML>
    <html>
        <!--Interfaz de registro de usuario Databridge-->
        <head>
            <meta charset="UTF-8">
                <title>Databridge</title>
                <link rel="stylesheet" href="Estilo.css">
                <link rel="icon" href="Databridge.jpeg" type="jpeg">
        </head>
        <body>
            <div class="registro">
                <img src="Databridge.jpeg" width="100" class="logo">
            <h1>  Registro Usuario </h1>
            <form  action = "Registro_backend.php" method = "post"> 
             <label> Nombre </label>
                <br>
                <input type="text" name = "nombre" placeholder="Ingrese su nombre" required>
            <br><br>
            <label>Apellido</label>
            <br>
            <input type="text" name = "apellido" placeholder="Ingrese su apellido" required>
            <br><br>
            <label>Nombre de empresa</label>
            <br>
            <select name = "nombre_empresa" class = "select" required>
                <option value = ""> Seleccione su empresa</option>
                <?php
                $sql = "select Id_empresa, Nombre_empresa from Empresa";
                $resultado = $conexion -> query($sql);
                if($resultado -> num_rows > 0){
                    while($fila = $resultado -> fetch_assoc()){
                        echo '<option value = "' . $fila['Id_empresa'].'">' .$fila['Nombre_empresa'] . '</option>'; 
                    }
                }else
                echo "<option> no hay empresas registradas</option>";
                ?>
        </select>
            <br><br>
            <label>Cargo</label>
            <br>
            <select name = "cargo" class = "select" required>
                <?php
                $sql = "select Id_cargo, Nombre_cargo from Cargo";
                $resultado = $conexion -> query($sql);
                if($resultado -> num_rows > 0){
                    while($fila = $resultado -> fetch_assoc()){
                        echo '<option value = "' . $fila['Id_cargo'] . '">' . $fila['Nombre_cargo'] . '</option>'; 
                    }
                }else
                 echo "<option> no hay cargos registrados</option>";
                ?>
            </select>
            <br><br>
            <label>Correo empresarial</label>
            <br>
            <input type="email" name = "correo_empresarial" placeholder="Ingrese su correo empresarial" required>
            <br><br>
            <label>Nombre de usuario</label>
            <Br>
           <input type = "text" name = "nombre_usuario" placeholder = "Ingrese nombre de usuario" required>
           <br> <br>
           <label>Contraseña</label>
           <br>
           <input type = "password" name = "contrasena" placeholder = "Ingrese contraseña" id="Contraseña" required>
              <br> <br>
              <label>Confirmar contraseña</label>
              <br>  
             <input type = "password" name = "confirmar_contrasena" placeholder = "Confirme contraseña" id="ConfirmarContraseña" required>
             <br><br>
           <input type = "submit" class="Crear" value = "Registrar Usuario">
         </div>
        </form>
        </body>
    </html>