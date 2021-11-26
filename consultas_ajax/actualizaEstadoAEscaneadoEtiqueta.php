<?php
    include 'conexionbd.php';
    
    $sql = "update trazabilidad_materiaprima set scaneado=1 where idTrazabilidadMateriaPrima=".$_GET['idTrazabilidadMateriaPrima'];
    $respuesta = 0;
    if ($conn->query($sql) === TRUE) {
        $respuesta = 1;
    } else {
        $respuesta = 0;
    }
    $conn->close();
    echo json_encode($respuesta);
?>

