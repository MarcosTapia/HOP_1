<!DOCTYPE html>
<head>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
    
</head>
<body onload="mensaje();">
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
                <h4 style="text-align: center;">Registro Materia Prima.</h4>
                <h4 style="text-align: center;">Orden: <?php echo $orden->{'numeroOrden'}; ?>. Etapa: <?php echo $etapa->{'numeroOperacion'}." ".$etapa->{'descripcion_operacion'}; ?>.</h4>
                <br>
            </div>
        </div>
        <input type="hidden" id="idOrden" value="<?php echo $orden->{'idOrden'}; ?>" />
        <input type="hidden" id="noOrden" value="<?php echo $orden->{'numeroOrden'}; ?>" />
        <input type="hidden" id="sap" value="<?php echo $orden->{'sap'}; ?>" />
        <input type="hidden" id="standard" value="<?php echo $orden->{'standard'}; ?>" />
        <input type="hidden" id="cantidad" value="<?php echo $orden->{'cantidad'}; ?>" />
        <input type="hidden" id="idEtapa" value="<?php echo $etapa->{'idEtapa'}; ?>" />
        <input type="hidden" id="etapa" value="<?php echo $etapa->{'numeroOperacion'}." ".$etapa->{'descripcion_operacion'}; ?>" />
        
        <div class="row-fluid">
            <div class="col-sm-12">
                <div class="col-sm-9">
                        <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>" />
                        <br>
                        <div class="form-group">
                            <?php 
                            $i=1;
                            foreach($materiasprimas as $fila) {
                                echo "<div class='col-sm-7'>";
                                echo "<input type='hidden' name='hdn".$i."' id='hdn".$i."' value='".$fila->{'idMateriaPrima'}."' /";
                                echo "<p style='font-size:40px;'>".$fila->{'descripcion_materiaprima'}." - SAP: ".$fila->{'nosap'};
                                echo "</div><div class='col-sm-5'>Fecha:&nbsp;&nbsp;";
                                echo "<input type='date' style='height:25px;' id='fecha".$i."' name='fecha".$i."' value='".$fecha."'></p>";
                                echo "</div>";                       
                                $i++;
                            }
                            ?>
                        </div> 
                        
                        <div class="form-group">
                          <br><br><br>
                          <label class="control-label col-sm-1" for="maquina">Máquina*:</label>
                          <div class="col-sm-11">
                            <div class="input-group">
                                <select style="margin-left: 15px;" class="form-control" name="maquina" id="maquina" required>
                                    <option value="">Seleccionar uno...</option>
                                    <option value="596">General 001</option>
                                    <?php foreach($maquinas as $fila) {
                                        if ($fila->{'idMaquina'} != 596) {
                                            echo "<option value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}." ".$fila->{'numero_maquina'}."</option>";
                                        }
                                    } ?>
                                </select>
                            </div>					  
                          </div>					  
                        </div>                         
                        
                        <input type="hidden" name="cantidadMateriaPrimaHdn" id="cantidadMateriaPrimaHdn" value='<?php echo sizeof($materiasprimas); ?>'/>
                        <input type="hidden" name="idUsuarioHdn" id="idUsuarioHdn" value='<?php echo $idUsuario; ?>' />
                        
                        <div class="form-group">
                            <center>
                                <table> 
                                    <tr>
                                        <td>
                                            <input id="btnSumar" onclick="sumar();" style="margin-top: 40px;" type="button" class='btn btn-primary' value='Imprimir Etiqueta Caja' />
                                        </td>
                                        <td>
                                            <a style="margin-left: 20px; margin-top: 40px;" href="<?php echo base_url();?>index.php/usuarios_controller/ingresoLoginSalir/<?php echo $idEtapa; ?>" class="btn btn-success">Salir del Sistema.</a>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </div> 
                </div>
                <div class="col-sm-3">
                    
                    <p style="font-size: 30px;color: blue;">Piezas: </p>
                    <p style="margin-top: -30px; font-size: 100px;color: blue;" id="cantPzas"><?php echo $cantidad; ?></p>
                </div>
            </div>	
        </div>
    </div>
    
    
    <script>       
        function sumar() {
            var continuarProceso = 0;
            //valida si hay máquina seleccionada
            if (document.getElementById("maquina").value == "") {
                alert("Debes seleccionar una máquina y si el proceso no lleva, selecciona la máquina General 001.");
                return;
            }
            
            //nuevo consulta cantidad registrada
            consultaCantidadActualEtapa();
            let cantidad = parseInt(document.getElementById("cantidad").value);
            if (parseInt(document.getElementById("cantPzas").innerHTML) >= cantidad) {
                document.getElementById("btnSumar").disabled = true;
                alert("Standard alcanzado.");
                return;
            }
            //fin nuevo
            
            //valida si tiene que pedir los scaneos de la etapa anterior
            if (document.getElementById('idEtapa').value != 3) {
                alert("Escanear las etiquetas anteriores.");
                var idTrazabilidadMateriaPrima = prompt("Escanear codigo QR.");
                
                //nuevo
                if (!isCurrentLabel(idTrazabilidadMateriaPrima,document.getElementById('idEtapa').value)) {
                    return false;
                }
                //fin nuevo
                
                //verificar si existe la etiqueta anterior con su idTrazabilidadMateriaPrima
                jQuery.ajax({
                    async:false,
                    url: "<?php echo base_url(); ?>/consultas_ajax/existeEtiqueta.php?idTrazabilidadMateriaPrima=" + idTrazabilidadMateriaPrima,
                    cache: false,
                    contentType: "text/html; charset=UTF-8",
                    success: function(response){
                        if (response == 0) {
                            alert("Esta etiqueta ya existe o previamente fue escaneada.");
                            return false;
                        } else {
                            //cambia status de la etiqueta anterior
                            jQuery.ajax({
                                url: "<?php echo base_url(); ?>/consultas_ajax/actualizaEstadoAEscaneadoEtiqueta.php?idTrazabilidadMateriaPrima=" + idTrazabilidadMateriaPrima,
                                cache: false,
                                contentType: "text/html; charset=UTF-8",
                                success: function(response){
                                    if (response == 1) {                                    
                                        alert("Etiqueta escaneada");
                                        continuaProceso();
                                    } else {
                                        alert("Error al escanear.");
                                        return false;
                                    }
                                },
                                error: function(response){
                                    alert("Error al escanear.");
                                    return false;
                                }
                            });                             
                        }
                    },
                    error: function(response){
                        alert("Error 3");
                    }
                });            
            } else {
                continuaProceso();
            }
        }
        
        function continuaProceso() {
            //obtiene fecha de la maquina y ajusta a dos digitos cuando vengan con uno
            var hoy = new Date();	
            var mes = "" + (hoy.getMonth() + 1);
            if (mes.length == 1) {
                mes = "0" + mes;
            }
            var dia = "" + hoy.getDate();
            if (dia.length == 1) {
                dia = "0" + dia;
            }
            var fecha = hoy.getFullYear() + '-' + mes + '-' + dia;
            var hr = "" + hoy.getHours()
            if (hr.length == 1) {
                hr = "0" + hr;
            }
            var min = "" + hoy.getMinutes();
            if (min.length == 1) {
                min = "0" + min;
            }
            var seg = "" + hoy.getSeconds();
            if (seg.length == 1) {
                seg = "0" + seg;
            }
            var hora = hr + ':' + min + ':' + seg;
            var fechaYHora = fecha + ' ' + hora;
            
            let idOrden = parseInt(document.getElementById("idOrden").value);
            let standard = parseInt(document.getElementById("standard").value);
            let cantidad = parseInt(document.getElementById("cantidad").value);
            let cantidadActual = parseInt(document.getElementById("cantPzas").innerHTML);
            cantidadActual = cantidadActual + standard;
            document.getElementById("cantPzas").innerHTML = "" + cantidadActual; 
            var idMaquina = document.getElementById("maquina").value;
            
            //arma cadena de materia prima
            var cadenaMateriaPrima = "";
            var cantidadMateriaPrima = document.getElementById('cantidadMateriaPrimaHdn').value;
            
            for (i=1; i<= cantidadMateriaPrima; i++) {
                cadenaMateriaPrima = cadenaMateriaPrima + document.getElementById("hdn" + i).value
                    + "|" + document.getElementById("fecha" + i).value + "|" + document.getElementById('idEtapa').value + "|" + document.getElementById('idOrden').value + "|" 
                    + standard + "|" + document.getElementById("idUsuarioHdn").value + "|" + idMaquina + "|" + "@@@";
            }
            
            //Registro materia prima (seguimiento)
            var idQr;
            jQuery.ajax({
                async: false,
                url: "<?php echo base_url(); ?>/consultas_ajax/insertaTrazabilidad.php?parametros=" + cadenaMateriaPrima,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    idQr = response;
                },
                error: function(response){
                    alert("Error");
                }
            });
            
            //simulacion de impresion de etiqueta
            alert("Imprime etiqueta.");
            let etiqueta = "---- Etiqueta:\n" 
                    + "Orden: " + document.getElementById("noOrden").value + "\n"
                    + "SAP: " + document.getElementById("sap").value + "\n"
                    + "Standard: " + document.getElementById("standard").value + "\n"
                    + "Operación: " + document.getElementById("etapa").value + "\n"
                    + "Fecha: " + fechaYHora + "\n"
                    + "Dato a Escanear: " + idQr + "\n";
            alert(etiqueta); 

            if (cantidadActual >= cantidad) {
                document.getElementById("btnSumar").disabled = true;
                alert("Standard alcanzado.");
            }
        }
        
        function consultaCantidadActualEtapa() {
            //aqui debo validar si se continua con el proceso o no con la variable continuarProceso = 1
            jQuery.ajax({
                async: false,
                url: "<?php echo base_url(); ?>/consultas_ajax/consultaCantidadPorEtapa.php?idOrden=" + document.getElementById("idOrden").value + "&idEtapa=" + document.getElementById('idEtapa').value,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    document.getElementById("cantPzas").innerHTML = response.replace("\"","");
                    document.getElementById("cantPzas").innerHTML = document.getElementById("cantPzas").innerHTML.replace("\"","");
                },
                error: function(response){
                    alert("Error");
                }
            });            
        }
        
        function isCurrentLabel(idTrazabilidadMateriaPrima,idEtapa) { 
            var resppuestaAjax = true;
            //valida si es etiqueta actual
            jQuery.ajax({
                async: false,
                url: "<?php echo base_url(); ?>/consultas_ajax/esEtiquetaActual.php?idTrazabilidadMateriaPrima=" + idTrazabilidadMateriaPrima + "&idEtapa=" + idEtapa,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    if (response == 1) {                                    
                        alert("No puedes escanear esta etiqueta en esta etapa.");
                        resppuestaAjax = false;
                    }  
                },
                error: function(response){
                    alert("Error");
                }
            });            
            return resppuestaAjax;
        }
        
        function mensaje() {
            consultaCantidadActualEtapa();
        }
    </script>
    
</body>
</html>