<?php
include "conexion.php";
session_start();

if(!isset($_SESSION['Id_usuario'])){
    echo "No ha iniciado sesion";
    exit();
}
/* Unimos el backend php con el html en un solo archivos, para poder mostrar la interfaz sin la necesidad de 
que el codigo php se ejecute primero */

if(isset($_FILES['archivo'])){
    
$formato_origen = $_POST['formato_origen'];
$formato_destino = $_POST['formato_destino'];
$carpeta = "archivos_subidos/";

$peso_bytes = $_FILES['archivo']['size'];

$peso_kilobytes = $peso_bytes / 1024;

$peso_megabytes = $peso_kilobytes / 1024;

$peso_final = round($peso_megabytes,2);

if($peso_final > 50){
    echo "Por el momento el sistema solamente soporta arrchivos de 50Mb";
    exit();
}

$nombre_archivo = $_FILES['archivo']['name'];
$ruta_temporal = $_FILES['archivo']['tmp_name'];
$destino = $carpeta . $nombre_archivo;

move_uploaded_file($ruta_temporal, $destino);

$destino_seguro = escapeshellarg($destino);  /* esto se hace porque los nombre de los archivos
casi siempre llevan espacios, la consola usa los espacios para separar y python
creera que se le envian tres datos diferentes, cuando es uno, por eso se usa la funcion de php escapeshellarg()*/

if($formato_origen == "docx" && $formato_destino == "docx-pdf"){
$comando = "python conversor.py $destino_seguro";  
$respuesta = shell_exec($comando);

$destino_transformado = str_replace(".docx", ".pdf", $destino);

if(file_exists($destino_transformado)){

$peso_transformado_bytes = filesize($destino_transformado);

$peso_transformado_kilobytes = $peso_transformado_bytes / 1024;

$peso_transformado_megabytes = $peso_transformado_kilobytes / 1024;

$peso_transformado_final = round($peso_transformado_megabytes,2);


/* Guardamos los datos de los archivos en la variable superglobal para 
ocuparlos en la consulta de registro en la base de datos unicamente
cuando el usuario presiona el boton "Guardar" */

$_SESSION['ruta_archivos_subidos'] = $destino_seguro;           
$_SESSION['ruta_archivos_convertido'] = $destino_transformado;
$_SESSION['peso_archivo_subido'] = $peso_final;
$_SESSION['peso_archivo_convertido'] = $peso_transformado_final;
$_SESSION['id_tipo_conversion'] = 1;

}else{
    echo "Error en transformacion";
}
}elseif($formato_origen == "pdf" && $formato_destino == "pdf-docx"){
    $comando = "python conversor2.py $destino_seguro";  
$respuesta = shell_exec($comando);

$destino_transformado = str_replace(".pdf", ".docx", $destino);

if(file_exists($destino_transformado)){

$peso_transformado_bytes = filesize($destino_transformado);

$peso_transformado_kilobytes = $peso_transformado_bytes / 1024;

$peso_transformado_megabytes = $peso_transformado_kilobytes / 1024;

$peso_transformado_final = round($peso_transformado_megabytes,2);

/* Guardamos los datos de los archivos en la variable superglobal para 
ocuparlos en la consulta de registro en la base de datos unicamente
cuando el usuario presiona el boton "Guardar" */

$_SESSION['ruta_archivos_subidos'] = $destino_seguro;
$_SESSION['ruta_archivos_convertido'] = $destino_transformado;
$_SESSION['peso_archivo_subido'] = $peso_final;
$_SESSION['peso_archivo_convertido'] = $peso_transformado_final;
$_SESSION['id_tipo_conversion'] = 2;
}
}else{
    echo "Transformacion no disponible";
    exit();
}
}

/* Consulta Sql para registrar el historial de archivos subidos y transformados */

if(isset($_POST['guardar'])){
    if(isset($_SESSION['ruta_archivos_subidos']) && (isset($_SESSION['ruta_archivos_convertido']))){
        $id_usuario = $_SESSION['Id_usuario'];
        $archivo_subido = $_SESSION['ruta_archivos_subidos'];
        $archivo_convertido = $_SESSION['ruta_archivos_convertido'];
        $peso_archivo_subido = $_SESSION['peso_archivo_subido'];
        $peso_archivo_convertido = $_SESSION['peso_archivo_convertido'];
        $id_conversion = $_SESSION['id_tipo_conversion'];

        /*  Consulta SQL */
$sql_historial = "Insert into historial_conversion 
(Ruta_Archivo_Subido, Ruta_Archivo_Convertido, Tamano_Archivo_Original, tamano_archivo_destino, Id_usuario, Id_tipo_conversion)
 values('$archivo_subido','$archivo_convertido','$peso_archivo_subido','$peso_archivo_convertido','$id_usuario','$id_conversion')";

 if($conexion -> query ($sql_historial) === TRUE){

 }else{
    echo "Error en el registro de la base de datos" . $conexion -> error;
 }
    }
}




?>

<!--Interfaz principal databridge para usuarios gratis-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Databridge</title>
        <link rel="stylesheet" href="Estilo.css">
        <link rel="icon" href="Databridge.jpeg" type="jpeg">
    </head>
    <body class = "premium">

        <!-- BARRA DE NAVEGACION -->
        <div class="navbar">
            <div class="navbar-izquierda">
                <img src="Databridge.jpeg" width="40" class="logo">
                <span class="titulo-navbar">Databridge</span>
            </div>
            <div class="navbar-derecha">
                <a href="historial.php"><button type="button" class="botonNavbar">Historial</button></a>
                <!-- MENU PERFIL -->
                <div class="contenedor-perfil">
                    <a href="perfil_premium.php"><button type="button" class="botonNavbar">Mi Perfil ▾</button></a>
                    <div class="dropdown-perfil" id="dropdown-perfil" style="display:none">
                    </div>
                </div>

            </div>
        </div>


            <!-- AREA DE TRABAJO: DOS COLUMNAS -->
            
            <form action="Databridge_Premium.php" method="post" enctype="multipart/form-data">

            <div class="area-trabajo">

                <!-- COLUMNA IZQUIERDA: ORIGINAL -->
                <div class="caja-archivo">
                    <h3>Original</h3>
                    <label>Formato de entrada</label>
                    <br><br>
                    <select class="select-formato" name = "formato_origen" required>
                        <option value="">Selecciona formato</option>
                        <option value="docx">docx </option>
                        <option value="pdf"> pdf </option>
                        <option value=""> </option>
                        <option value=""></option>
                    </select>
                    <br><br>

                    <!-- Zona de adjuntar archivo -->
                    <div class="zona-archivo">
                        <br>
                        <p>Selecciona el archivo a convertir</p>
                        <input type="file" name = "archivo" class="botonNavbar">
                           <br><br>
                    </div>

                    <br>
                    <label> Tamaño:
                        <?php if(isset($peso_final)){
                            echo $peso_final;
                        }else{
                            echo "0";
                        }
                        ?>
                        Mb</label>
                    <br><br>
                </div>
                    <button type="submit" class="aceptar"> Transformar </button>

                <!-- COLUMNA DERECHA: DESTINO -->
                <div class="caja-archivo">
                    <h3>Destino</h3>
                    <label>Formato de salida</label>
                    <br><br>
                    <select class="select-formato" name = "formato_destino" required>
                        <option value="">Selecciona conversion</option>
                        <option value="docx-pdf"> pdf </option>
                        <option value="pdf-docx"> docx </option>
                        <option value=""> </option>
                        <option value=""> </option>
                        <option value=""></option>
                        <option value=""></option>
                    </select>
                    <br><br>

                    <!-- Zona de vista previa -->
                    <div class="zona-archivo">
                        <?php if(isset($destino_transformado)):?>
                        <p> Archivo transformado: </p>
                        <p><b> <?php echo basename($destino_transformado); ?> </p></b>
                        <?php else: ?>
                        <p>El archivo convertido</p>
                        <p>aparecera aqui</p>
                        <?php endif; ?>
                        <br><br>
                    </div>

                    <br>
                    <label>Tamaño: 
                    <?php if(isset($peso_transformado_final)){
                        echo $peso_transformado_final;
                    }else{
                        echo "0";
                    }
                    ?>
                    Mb </label>
                </div>

            </div>
            </form>

            <!-- BOTONES DE ACCION -->
            <div class="barra-acciones">

                <!-- BOTON VISTA PREVIA -->
                <?php if(isset($destino_transformado)):?>
                <a href="<?php echo $destino_transformado; ?>" target="_blank"> <button type="button" class="botonNavbar">Vista Previa</button></a>
                <?php else: ?>
                <button type="button" class="botonNavbar">Vista Previa</button>
                <?php endif; ?>

                <!-- BOTON GUARDAR -->
                 <?php if(isset($destino_transformado)):?>
                    <form action="Databridge_Premium.php" method="post">
                 <button type="Submit" class="botonNavbar" name="guardar"> Guardar </button>
                 </form>
                <?php else: ?>
                <button type = "button" class = "botonNavbar"> Guardar </button>
                <?php endif; ?>

                <!-- BOTON DESCARGAR -->
                 <?php if(isset($destino_transformado)):?>
                <a href="<?php echo $destino_transformado; ?>" download>  <button type="button" class="botonNavbar">Descargar</button></a>
                <?php else: ?>
                <button type="button" class="botonNavbar">Descargar</button>
                <?php endif; ?>
            </div>

        </div>