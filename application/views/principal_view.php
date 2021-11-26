<!DOCTYPE html>
<html lang="es">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <link rel="shortcut icon" href="favicon.png">
    <!-- Para favicon -->
    <link href="<?php echo base_url(); ?>/images/favicon.ico" rel="icon" type="image/x-icon" />    
    <title>HOP</title>
    <link href="<?php echo base_url(); ?>/css/bootstrap.css" rel="stylesheet">
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
<head>
</head>
<body>
    <div class="container">                    
        <div class="row-fluid">
            <div class="col-md-12">
                <center>
                    <img style="margin-top: -40px;margin-bottom: -30px;" class="img-responsive" src="<?php echo base_url(); ?>/images/WEWIRELOGO.png" alt="Logo de la Empresa" />
                </center>
          </div>
        </div>
        
        <div class="row-fluid">
            <div class="col-md-6">
                <p style="font-size: 18px;color: #006666">Bienvenido: <?php echo $usuarioDatos; ?></p>
            </div>
            <div class="col-md-6">
                <div class="col-md-6">
                </div>
                <div class="col-md-6">
                    <p style="font-size: 18px;color: #006666">Fecha: <?php echo $fecha; ?> </p>
                </div>
            </div>
        </div>
        
        <div class="row-fluid">
            <div class="col-md-12">
                <h2 style="margin-top: 10px;text-align: center;">Selecciona una opción:</h2>
                <br><br><br>
            </div>
        </div>
        
        <?php if ($idEtapa == 3) { ?>
        <div class="row-fluid">
            <div class="col-md-12">
                <div class="col-md-2">
                </div>
                <div class="col-md-3">
                    <div class="card" style="width: 18rem;">
                        <a href="<?php echo site_url();?>index.php/ordenes_controller/nuevoOrden/<?php echo $idUsuario;?>/<?php echo $idEtapa;?>">
                            <img src="<?php echo base_url(); ?>/images/orden1.jpg" class="card-img-top" alt="Nueva Orden" title="Nueva Orden" />
                        </a>
                      <div class="card-body">
                          <h3 style="text-align: center;">Nueva Orden</h3>
                      </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card" style="width: 18rem;">
                        <a href="#" id="continuarOrden">
                          <img onclick="preguntaOrden('<?php echo $idEtapa; ?>');" src="<?php echo base_url(); ?>/images/orden3.png" class="card-img-top" alt="Continuar Orden" title="Continuar Orden" />
                        </a>
                      <div class="card-body">
                          <h3 style="text-align: center;">Continuar Orden</h3>
                      </div>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="card" style="width: 18rem;">
                        <a href="<?php echo base_url();?>index.php/usuarios_controller/ingresoLoginSalir/<?php echo $idEtapa; ?>">
                          <img src="<?php echo base_url(); ?>/images/cerrar_sesion.jpg" class="card-img-top" alt="Cerrar Sesióm" title="Cerrar Sesión" />
                        </a>
                      <div class="card-body">
                          <h3 style="text-align: center;">Cerrar Sesión</h3>
                      </div>
                    </div>        
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
        <?php } else { ?>
        <div class="row-fluid">
            <div class="col-md-12">
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                    <div class="card" style="width: 18rem;">
                        <a href="#" id="continuarOrden">
                            <img onclick="preguntaOrden('<?php echo $idEtapa; ?>');" src="<?php echo base_url(); ?>/images/orden3.png" class="card-img-top" alt="Continuar Orden" title="Continuar Orden" />
                        </a>
                      <div class="card-body">
                          <h3 style="text-align: center;">Continuar Orden</h3>
                      </div>
                    </div>        
                </div>
                <div class="col-md-3">
                    <div class="card" style="width: 18rem;">
                        <a href="<?php echo base_url();?>index.php/usuarios_controller/ingresoLoginSalir/<?php echo $idEtapa; ?>">
                          <img src="<?php echo base_url(); ?>/images/cerrar_sesion.jpg" class="card-img-top" alt="Cerrar Sesióm" title="Cerrar Sesión" />
                        </a>
                      <div class="card-body">
                          <h3 style="text-align: center;">Cerrar Sesión</h3>
                      </div>
                    </div>        
                </div>
                <div class="col-md-3">
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <script>
        function preguntaOrden(idEtapa){
            var numeroOrden = prompt("Ingrese el número de orden.");
            if ((numeroOrden == null) || (numeroOrden == undefined) || (numeroOrden.trim() == "")) {
                alert("Número de orden no válido");
                return;                
            }
            
            //valida etapa
            if (idEtapa != 3) {
                jQuery.ajax({
                    //trae el idOrden por su numero de orden
                    url: "<?php echo base_url(); ?>/consultas_ajax/consultaOrden.php?numeroOrden=" + numeroOrden + "&idEtapa=" + idEtapa,
                    cache: false,
                    contentType: "text/html; charset=UTF-8",
                    success: function(response){
                        if (response.length > 4) {
                            let ordenElementArray = response.split("|");
                            let idOrden = ordenElementArray[0].replace("\"", "");
                            //verifica orden en la etapa y si hay registros 
                            jQuery.ajax({
                                //Trae la etapa anterior
                                url: "<?php echo base_url(); ?>/consultas_ajax/consultaOrden2.php?idOrden=" + idOrden + "&idEtapa=" + idEtapa,
                                cache: false,
                                contentType: "text/html; charset=UTF-8",
                                success: function(response){
                                    jQuery.ajax({
                                        //Trae la cantidad de la etapa anterior
                                        url: "<?php echo base_url(); ?>/consultas_ajax/consultaCantidadPorEtapa.php?idOrden=" + idOrden + "&idEtapa=" + response,
                                        cache: false,
                                        contentType: "text/html; charset=UTF-8",
                                        success: function(response){
                                            if (response.length > 0) {                                    
                                                window.location.href = "<?php echo site_url();?>index.php/ordenes_controller/registroMateriaPrima/<?php echo $idUsuario;?>/<?php echo $idEtapa;?>/" + numeroOrden + "/" + response;
                                            } else {
                                                alert("Error.");
                                            }
                                        },
                                        error: function(response){
                                            alert("Error");
                                        }
                                    }); 
                                },
                                error: function(response){
                                    alert("Error");
                                }
                            });                            
                        } else {
                            alert("No existe este umero de orden.");
                        }
                    },
                    error: function(response){
                        alert("Error");
                    }
                });
            } else {
                jQuery.ajax({
                    //trae el idOrden por su numero de orden
                    url: "<?php echo base_url(); ?>/consultas_ajax/consultaOrden.php?numeroOrden=" + numeroOrden + "&idEtapa=" + idEtapa,
                    cache: false,
                    contentType: "text/html; charset=UTF-8",
                    success: function(response){
                        if (response.length > 4) {
                            let ordenElementArray = response.split("|");
                            let idOrden = ordenElementArray[0].replace("\"", "");
                            //verifica orden en la etapa y si hay registros 
                            jQuery.ajax({
                                //Trae la cantidad de la etapa anterior
                                url: "<?php echo base_url(); ?>/consultas_ajax/consultaCantidadPorEtapa.php?idOrden=" + idOrden + "&idEtapa=" + idEtapa,
                                cache: false,
                                contentType: "text/html; charset=UTF-8",
                                success: function(response){
                                    if (response.length > 0) {                                    
                                        window.location.href = "<?php echo site_url();?>index.php/ordenes_controller/registroMateriaPrima/<?php echo $idUsuario;?>/<?php echo $idEtapa;?>/" + numeroOrden + "/" + response;
                                    } else {
                                        alert("Error.");
                                    }
                                },
                                error: function(response){
                                    alert("Error");
                                }
                            });                            
                            //window.location.href = "<?php echo site_url();?>index.php/ordenes_controller/registroMateriaPrima/<?php echo $idUsuario;?>/<?php echo $idEtapa;?>/" + numeroOrden;
                        } else {
                            alert("No existe este umero de orden.");
                        }
                    },
                    error: function(response){
                        alert("Error");
                    }
                });
            }
        }
        
    </script>
    
</body>
</html>
