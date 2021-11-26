<?php
    $conexion = mysqli_connect("localhost","root","kikinalba","bmw");
    mysqli_autocommit($conexion,FALSE);
    $trazabilidad = explode("@@@",$_GET['parametros']);
    $retorno;
    for ($i=0; $i<sizeof($trazabilidad)-1; $i++) {
        $partesMatPrima = explode("|",$trazabilidad[$i]);
        $comando = "INSERT INTO trazabilidad_materiaprima ( " .
                "idMateriaPrima," .
                "fechaMateriaPrima," .
                "idEtapa," .
                "idOrden," .
                "cantidad," .
                "idUsuario,idMaquina)" .
                " VALUES(".$partesMatPrima[0].",'".$partesMatPrima[1]."',".$partesMatPrima[2].","
                .$partesMatPrima[3].",".$partesMatPrima[4].",".$partesMatPrima[5].",".$partesMatPrima[6].");";
        $retorno = mysqli_query($conexion,$comando);
        if ($retorno == FALSE) {
            mysqli_rollback($conexion);
            break;
        }
    }	
    if ($retorno != FALSE) {
        mysqli_autocommit($conexion,TRUE);
        mysqli_close($conexion);
        $retorno = true;
    }
    
    //obtiene el idinsertado
    include 'conexionbd.php';    
    $respuesta = 0;
    $sql = "select max(idTrazabilidadMateriaPrima) as id from trazabilidad_materiaprima";
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
    while( $row = mysqli_fetch_assoc($res) ) { 
        $respuesta = $row['id'];
    }     
    $conn->close();
    echo json_encode($respuesta);    
?>

