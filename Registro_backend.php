<?php
 /* Este codigo corresponde al backend de registro de usuario, en este caso se utiliza PHP para recibir los datos del formulario de registro y guardarlos en la base de datos. Si el registro es exitoso, se mostrará un mensaje de exito, de lo contrario se mostrará un mensaje de error */

session_start();
include "conexion.php";  

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$nombre_empresa = $_POST['nombre_empresa'];
$cargo = $_POST['cargo'];
$correo_empresarial = $_POST['correo_empresarial'];
$nombre_usuario = $_POST['nombre_usuario'];
$contrasena = $_POST['contrasena'];
$confirmar_contrasena = $_POST['confirmar_contrasena'];

$Nombre = trim($nombre);
$Apellido = trim($apellido);
$Nombre_usuario = trim($nombre_usuario);

$Patron = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/";
$Patron2 = "/^[a-zA-Z0-9._-]{5,25}$/";
$Patron3 = "/^\S{4,12}$/";

if(!preg_match($Patron, $Nombre)){
    echo "<script> alert('Unicamente ingrese letras en este campo')
    window.location.href = 'Registro_frontend.php';
    </script>";
    exit();
}
if(!preg_match($Patron, $Apellido)){
   echo "<script> alert('Unicamente ingrese letras en este campo')
    window.location.href = 'Registro_frontend.php';
    </script>";
    exit();
}

if(!filter_var($correo_empresarial, FILTER_VALIDATE_EMAIL)){
     echo "<script> alert('Ingrese un formato de correo electronnico valido')
    window.location.href = 'Registro_frontend.php';
    </script>";
    exit();
}

if(!preg_match($Patron2, $Nombre_usuario)){
 echo "<script> alert('No puede ingresar letras con acentos y el nombre debe tener minimo 5 y maximo 25 caracteres')
    window.location.href = 'Registro_frontend.php';
    </script>";
    exit();
}

if(!preg_match($Patron3, $contrasena) || !preg_match($Patron3, $confirmar_contrasena) ){
     echo "<script> alert('Debe ingresar minimo 4 y maximo 12 caracteres')
    window.location.href = 'Registro_frontend.php';
    </script>";
    exit();
}

if($contrasena === $confirmar_contrasena){
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
}else{
     echo "<script> alert('Las contraseñas no coinciden')
    window.location.href = 'Registro_frontend.php';
    </script>";
    exit();
}
    
$sql = "insert into Usuario(Nombre, Apellido, Id_Empresa, Id_Cargo, Correo_Empresarial, Nombre_Usuario, Contrasena_Hash) 
values ('$Nombre', '$Apellido', '$nombre_empresa', '$cargo', '$correo_empresarial', '$Nombre_usuario', '$contrasena_hash')";

if($conexion ->query($sql) === TRUE){
    $Id_usuario = $conexion -> insert_id;
    $_SESSION['Id_usuario'] = $Id_usuario;

    header("Location: Plan.html");

    exit();
}else{
 echo "<script> alert('Error al registrar al usuario')
    window.location.href = 'Registro_frontend.php';
    </script>";
}

$conexion -> close();
?>