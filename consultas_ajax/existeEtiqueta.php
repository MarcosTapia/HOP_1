<?php
    include 'conexionbd.php';
    
    $respuesta = 0;
    $sql = "select idTrazabilidadMateriaPrima from trazabilidad_materiaprima where idTrazabilidadMateriaPrima=".$_GET['idTrazabilidadMateriaPrima']." and scaneado=0";
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
    while( $row = mysqli_fetch_assoc($res) ) { 
        $respuesta = $row['idTrazabilidadMateriaPrima'];
    } 
    
    $conn->close();
    echo json_encode($respuesta);
?>

