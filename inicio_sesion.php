<?php
/* Este codigo corrresponde al backend de inicio de sesion, en este caso se utiliza PHP para recibir los datos del formulario de inicio de sesion y verificar si el nombre de usuario y la contraseña son correctos. Si el inicio de sesion es exitoso, se mostrará un mensaje de exito, de lo contrario se mostrará un mensaje de error */
session_start();
include "conexion.php";
$nombre_usuario = trim($_POST['nombre_usuario']);
$contrasena = trim($_POST['contrasena']);

$sql = "select Contrasena_Hash, Id_usuario, Id_plan from Usuario where Nombre_Usuario = '$nombre_usuario'";

$resultado = $conexion -> query($sql);

if($resultado -> num_rows > 0){
    $fila = $resultado -> fetch_assoc();
    $contrasena_guardada = $fila['Contrasena_Hash'];
    $id_plan = $fila['Id_plan'];

    if(password_verify($contrasena, $contrasena_guardada)){
        $_SESSION['Id_usuario'] = $fila['Id_usuario'];
        if(empty($id_plan)){
            header("Location: Plan.html");
            exit();
    }elseif($id_plan == 1){
        header("Location: Databridge.html");
        exit();
    }elseif($id_plan == 2){
        header("Location: Databridge_Premium.html");
        exit();
    }
    }else{
        echo "Error: Contraseña incorrecta";
        exit();
    }
    }else{
        echo "Error: Usuario no encontrado";
        exit();
    }
$conexion -> close();
?>