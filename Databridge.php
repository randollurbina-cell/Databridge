<?php
session_start();

if(!isset($_SESSION['Id_usuario'])){
    echo "No ha iniciado sesion";
    exit();
}
/* Unimos el backend php con el html en un solo archivos, para poder mostrar */
if(isset($_FILES['archivo'])){
    
$formato_origen = $_POST['formato_origen'];
$formato_destino = $_POST['formato_destino'];
$carpeta = "archivos_subidos/";

$peso_bytes = $_FILES['archivo']['size'];

$peso_kilobytes = $peso_bytes / 1024;

$peso_megabytes = $peso_kilobytes / 1024;

$peso_final = round($peso_megabytes,2);

if($peso_final > 3){
    header("Location:Cambiar_plan.html");
    exit();
}

    $nombre_archivo = $_FILES['archivo']['name'];
    $ruta_temporal = $_FILES['archivo']['tmp_name'];
$destino = $carpeta . $nombre_archivo;

move_uploaded_file($ruta_temporal, $destino);

$destino_seguro = escapeshellarg($destino);   /* esto se hace porque los nombre de los archivos
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
}
}elseif($formato_origen = "pdf" && $formato_destino == "pdf-docx"){
    $comando = "python conversor2.py $destino_seguro";  
$respuesta = shell_exec($comando);

$destino_transformado = str_replace(".pdf", ".docx", $destino);

if(file_exists($destino_transformado)){

$peso_transformado_bytes = filesize($destino_transformado);

$peso_transformado_kilobytes = $peso_transformado_bytes / 1024;

$peso_transformado_megabytes = $peso_transformado_kilobytes / 1024;

$peso_transformado_final = round($peso_transformado_megabytes,2);
}
}else{
    echo "Transformacion no disponible";
    exit();
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
    <body>

        <!-- BARRA DE NAVEGACION -->
        <div class="navbar">
            <div class="navbar-izquierda">
                <img src="Databridge.jpeg" width="40" class="logo">
                <span class="titulo-navbar">Databridge</span>
            </div>
            <div class="navbar-derecha">
                <button type="button" class="botonNavbar" onclick="document.getElementById('modal-historial').style.display='flex'">Historial</button>
                <a href="plan.html"><button type="button" class="botonNavbar">Cambiar Plan</button></a>

                <!-- MENU PERFIL -->
                <div class="contenedor-perfil">
                    <button type="button" class="botonNavbar" onclick="togglePerfil()">Mi Perfil ▾</button>
                    <div class="dropdown-perfil" id="dropdown-perfil" style="display:none">
                        <p><b>Nombre:</b> Usuario</p>
                        <p><b>Cargo:</b> Cargo</p>
                        <p><b>Empresa:</b> Empresa</p>
                        <br>
                        <a href="login.html"><button type="button" class="botonNavbar">Cerrar Sesion</button></a>
                    </div>
                </div>

            </div>
        </div>


            <!-- AREA DE TRABAJO: DOS COLUMNAS -->
            
            <form action="Databridge.php" method="post" enctype="multipart/form-data">

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
                        <p>Arrastra tu archivo aqui</p>
                        <p>o</p>
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
                <?php if(isset($destino_transformado)):?>
                <a href="<?php echo $destino_transformado; ?>" target="_blank"> <button type="button" class="botonNavbar">Vista Previa</button></a>
                <?php else: ?>
                <button type="button" class="botonNavbar">Vista Previa</button>
                <?php endif; ?>
                <button type = "buttom" class = "botonNavbar"> Guardar </button>
                 <?php if(isset($destino_transformado)):?>
                <a href="<?php echo $destino_transformado; ?>" download>  <button type="button" class="botonNavbar">Descargar</button></a>
                <?php else: ?>
                <button type="button" class="botonNavbar">Descargar</button>
                <?php endif; ?>
            </div>

        </div>


        <!-- HISTORIAL -->
        <div class="fondo-modal" id="modal-historial" style="display:none">
            <div class="modal">
                <h2>Historial de Transformaciones</h2>
                <br>
                <table class="tabla-historial">
                </table>
                <br>
                <button type="button" class="botonNavbar" onclick="document.getElementById('modal-historial').style.display='none'">Cerrar</button>
            </div>
        </div>


        <!-- Script para abrir y cerrar el menu de perfil -->
        <script>
            function togglePerfil() {
                let menu = document.getElementById("dropdown-perfil");
                if (menu.style.display == "none") {
                    menu.style.display = "block";
                } else {
                    menu.style.display = "none";
                }
            }
        </script>

    </body>
</html>
