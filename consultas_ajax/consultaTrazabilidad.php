<?php
    include 'conexionbd.php';
    
    $respuesta = "";
    $sql = "select idTrazabilidad from trazabilidad where idOrden=".$_GET['idOrden'];
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
    while( $row = mysqli_fetch_assoc($res) ) { 
        $respuesta = $row['idTrazabilidad'];
    }     
    $conn->close();
    echo json_encode($respuesta);
?>

