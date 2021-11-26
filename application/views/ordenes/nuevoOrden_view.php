<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    
    <script>
        function regresar() {
            window.history.back();
        }
        
        function verificaOrden(){
            var numeroOrden = document.getElementById("numeroOrden").value;
            var respuesta = true;
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/consultaOrden.php?numeroOrden=" + numeroOrden,
                contentType: "text/html; charset=UTF-8",
                async: false,
                success: function(response){
                    if (response.length > 4) {
                        alert("Esta orden ya se encuentra registrada en el sistema.");
                        respuesta = false;
                    }
                },
                error: function(response){
                    alert("Error");
                    respuesta = false;
                }
            });
            return respuesta;
        }
    </script>
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
            <div class="col-sm-1">		
            </div>		
            <div class="col-sm-6">
                <form onsubmit="javascript:return verificaOrden();" class="form-horizontal" role="form" action="<?php echo base_url();?>index.php/ordenes_controller/nuevoOrdenFromFormulario" method="post">
                    <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>" />
                    <input type="hidden" name="idEtapa" value="<?php echo $idEtapa; ?>" />
                    <br>
                    <h4>Nueva Orden</h4>
                    <div class="form-group">
                      <label class="control-label col-sm-3" for="numeroOrden">Número Orden*:</label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="numeroOrden" name="numeroOrden" placeholder="Número de Orden" required autofocus>
                      </div>					  
                    </div>  

                    <div class="form-group">
                        <label class="control-label col-sm-3" for="cantidad">Cantidad*:</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="cantidad" required>
                        </div>					  
                    </div>
                    
                    <div class="form-group">
                      <label class="control-label col-sm-3" for="viajera">Viajera*:</label>
                      <div class="col-sm-9">
                        <div class="input-group">
                            <select class="form-control" name="viajera" id="viajera" required>
                                <option value="">Seleccionar uno...</option>
                                <?php foreach($viajeras as $fila) {
                                    echo "<option value=".$fila->{'idViajera'}.">".$fila->{'sap'}."</option>";
                                } ?>
                            </select>
                        </div>					  
                      </div>					  
                    </div>                    
 
                    <br>
                    <div class="form-group">
                        <center>
                            <input type="submit" class='btn btn-primary' value='GUARDAR' name='submit' /> 
                            <a href="#" onclick="regresar();">
                                <button type="button" class="btn btn btn-success">REGRESAR</button>
                            </a>
                        </center>
                    </div> 
                </form>
            </div>	
            <div class="col-sm-5">		
            </div>		
        </div>
    </div>
</body>
</html>