<?php
    include 'conexionbd.php';
    
    $respuesta = 0;
    $sql = "select * from trazabilidad_materiaprima where idTrazabilidadMateriaPrima=".$_GET['idTrazabilidadMateriaPrima']." and idEtapa=".$_GET['idEtapa'];
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
    while( $row = mysqli_fetch_assoc($res) ) { 
        $respuesta = 1;
    }     
    $conn->close();
    echo json_encode($respuesta);
?>

