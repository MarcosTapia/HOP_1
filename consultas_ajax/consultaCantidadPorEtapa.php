<?php
    include 'conexionbd.php';
    
    $respuesta = 0;
    $sql = "select sum(cantidad) as total from trazabilidad_materiaprima where idOrden=".$_GET['idOrden']." and idEtapa=".$_GET['idEtapa'];
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
    /*
    if ($conn->query($sql) === TRUE) {
        while( $row = mysqli_fetch_assoc($res) ) { 
            $respuesta = $row['total'];
        }     
    } else {
        $respuesta = 0;
    }
     * 
     */
    
    
    while( $row = mysqli_fetch_assoc($res) ) { 
        $respuesta = $row['total'];
    }     
    
    if ($respuesta == null) {
        $respuesta = 0;
    }
    
    $conn->close();
    echo json_encode($respuesta);
?>

