<?php
    include 'conexionbd.php';
    
    $respuesta = 0;
    $sql = "select sum(cantidad) as total from trazabilidad_materiaprima where idOrden=".$_GET['idOrden']." and idEtapa=".$_GET['idEtapa'];
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
    while( $row = mysqli_fetch_assoc($res) ) { 
        $respuesta = $row['total'];
    }     
    $conn->close();
    echo json_encode($respuesta);
?>

