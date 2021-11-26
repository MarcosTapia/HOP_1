<?php
    include 'conexionbd.php';
    
    $idOrden = $_GET['idOrden'];
    $idEtapa = $_GET['idEtapa'];
    $ordenEtapa = -1;
    $idEtapaR = -1;
   
    //Verifica numero de etapa
    $sql = "select orden from viajeras_etapas where idEtapa=".$idEtapa
            ." and idViajera = (select idViajera from ordenes "
            ."where idOrden=".$idOrden.")";
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
    while( $row = mysqli_fetch_assoc($res) ) { 
        $ordenEtapa = $row['orden'];
    }
    
    if ($ordenEtapa != -1) {
        if ($ordenEtapa != 1) {
            $ordenEtapa = $ordenEtapa - 1;
            $sql = "select idEtapa from viajeras_etapas where orden=".$ordenEtapa;
            $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
            while( $row = mysqli_fetch_assoc($res) ) { 
                $idEtapaR = $row['idEtapa'];
            }            
        }
    }    
    $conn->close();
    echo json_encode($idEtapaR);
?>

