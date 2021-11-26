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
    
    <script>
        function desbloquear(numEtapas){
            var usuario = prompt("Ingresa un usuario admininistrador.");
            var clave = prompt("Ingresa la clave.");
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/verificaUsuarioAdminitrador.php?usuario=" + usuario + "&clave=" + clave,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    if (response != 0) {
                        for (let i=1;i<=numEtapas;i++) {
                            document.getElementById('opt' + i).disabled=false;                            
                        }
                        document.getElementById("btnSubmit").disabled=false;
                        document.getElementById("btnDesbloquear").disabled=true;
                        alert("Elementos desbloueados.");
                    } else {
                        alert("Datos de administrador incorrectos.");
                    }
                },
                error: function(response){
                    alert("Error");
                }
            });
        }
        
        function verificaSeleccionEtapa(numEtapas){
            var etapaSeleccionada = 0;
            for (let i=1;i<=numEtapas;i++) {
                if (document.getElementById('opt' + i).checked) {
                    etapaSeleccionada = 1;
                    break;
                }
            }
            if (etapaSeleccionada == 1) {
                return true;
            } else {
                alert("Debes selecionar una etapa.");
                return false;
            }
        }
    </script>    
<head>
</head>
<body>
    <div class="container">
        <div class="row-fluid">
            <div class="col-md-12">
                <center>
                    <img class="img-responsive" src="<?php echo base_url(); ?>/images/WEWIRELOGO.png" alt="Logo de la Empresa" />
                    <h3 style="margin-top: -30px;">Configuración inicial:</h3>
                </center>
                <br>
                <p style="font-size: 20px;color:#003366;">
                    Instrucciones.- Selecciona la habilidad correspondiente:
                </p>
                <br>
                <form onsubmit="javascript: return verificaSeleccionEtapa('<?php echo sizeof($etapas); ?>');" id="loginPrincipal" action="<?php echo site_url();?>index.php/usuarios_controller/ingresoLogin" method="post"  >
                <div class="form-group">
                    <h4 style="margin-bottom: 20px; margin-top: -15px;">Etapas:</h4>
                    <?php 
                    $i=1;
                    foreach($etapas as $fila) {
                        echo "<div class='form-check'>";
                        echo "<input disabled class='form-check-input' type='radio' name='etapa' id='opt".$i."' value='".$fila->{'idEtapa'}."'";
                        echo "<label class='form-check-label' for='chk".$fila->{'idEtapa'}."'>&nbsp;&nbsp;&nbsp;".$fila->{'numeroOperacion'}." ".$fila->{'descripcion_operacion'}."</label>";
                        echo "</div>";
                        $i++;
                    }              
                    ?>
                </div>
                    <br>
                    <input id="btnDesbloquear" onclick="desbloquear('<?php echo sizeof($etapas); ?>');" type="button" class="btn btn-primary" value="Desbloquear" />
                    <input disabled type="submit" class="btn btn-success" value="Ingresar" id="btnSubmit"/>
                </form>
                
          </div>
        </div>
    </div>
</body>
</html>
