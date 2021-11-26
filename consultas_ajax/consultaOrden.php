<?php
    include 'conexionbd.php';
    
    $respuesta = "";
    $sql = "select * from ordenes where numeroOrden='".$_GET['numeroOrden']."'";
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
    while( $row = mysqli_fetch_assoc($res) ) { 
        /* idOrden,numeroOrden,fecha,cantidad,idViajera,idUsuario */
        $respuesta .= $row['idOrden']."|".$row['numeroOrden']."|".$row['fecha']."|".$row['cantidad']."|".$row['idViajera']."|".$row['idUsuario']."|";
    }     
    $conn->close();
    echo json_encode($respuesta);
?>

