<?php
    include 'conexionbd.php';
    
    $respuesta;
    $sql = "select * from usuarios where usuario='".$_GET['usuario']."' and "
        ."clave='".$_GET['clave']."'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $respuesta = 1;
    } else {
        $respuesta = 0;
    }
    $conn->close();
    echo json_encode($respuesta);
?>

