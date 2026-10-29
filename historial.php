<?php
session_start();
include "conexion.php";

if(!isset($_SESSION['Id_usuario'])){
    echo "Primero de be de iniciar sesión";
    exit();
}

$id_usuario = $_SESSION['Id_usuario'];

$sql = "select h.Ruta_Archivo_Subido, h.Ruta_Archivo_convertido, h.Tamano_Archivo_Original, h.tamano_archivo_destino,
        h.Fecha_Conversion, t.Formato_Origen, t.Formato_Destino from historial_conversion h
        join tipo_conversion t on h.Id_tipo_conversion = t.Id_tipo_conversion
          where h.Id_usuario = '$id_usuario' order by h.Fecha_Conversion desc";

 $consulta = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Historial de conversiones</title>
        <link rel="stylesheet" href="Estilo.css">
        <link rel="icon" href="Databridge.jpeg" type="jpeg">
    </head>
    <body class = "premium">
        <div class= "caja"> 
            <h2 class = "titulo">Historial de Transformaciones</h2>
        <br>
       <?php if($consulta && mysqli_num_rows($consulta) > 0): ?>
            <table class="tabla-historial">
                <tr>
                    <th>Archivo Original</th>
                    <th>Archivo Convertido</th>
                    <th>Tamaño Orig.</th>
                    <th>Tamaño Dest.</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            <?php
            /* Usamos el while para mostrar tantas filas como registros tenga el usuario */
            while($fila = $consulta ->fetch_assoc()):?>
            <tr>
                <td><?php echo basename($fila['Ruta_Archivo_Subido']); ?></td>
                        <td><?php echo basename($fila['Ruta_Archivo_convertido']); ?></td>
                        <td><?php echo $fila['Tamano_Archivo_Original']; ?> Mb</td>
                        <td><?php echo $fila['tamano_archivo_destino']; ?> Mb</td>
                        <td><?php echo $fila['Fecha_Conversion']; ?></td>
                        <td>
                            <a href="<?php echo $fila['Ruta_Archivo_convertido']; ?>" download>
                                <button type="button" class="aceptar">Descargar</button></a>
                            </a>
                        </td>
                    </tr>
            <?php endwhile; ?>
        </table>
<?php else: ?>
            <p>No hay registros en el historial de conversiones.</p>
        <?php endif; ?>

        <br>
        <a href="Databridge_Premium.php"><button type="button" class="botonNavbar">Volver</button></a>
    </div>
</body>
</html>