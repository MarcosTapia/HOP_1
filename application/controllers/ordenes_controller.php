<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ordenes_controller extends CI_Controller {
    private $datosEmpresaGlobal;
    private $nombreEmpresaGlobal;
    private $usuariosGlobal;
    private $areasGlobal;
    
    private $gatewayRest;

    function __construct(){
        parent::__construct();
        $this->load->model('sistema_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->error = "";
        
        //para subir imagenes
        $this->load->helper("URL", "DATE", "URI", "FORM");
        $this->load->library('upload');
        //$this->load->model('mupload_model');    
        
        //$this->sucursalesGlobal = $this->cargaDatosSucursales();
        
        //llamado a controlador util global
        $this->load->library('../controllers/util_controller');
        $this->load->library('../controllers/usuarios_controller');
        
        $this->datosEmpresaGlobal = $this->util_controller->cargaDatosEmpresa();
        $this->nombreEmpresaGlobal = $this->datosEmpresaGlobal[0]->{'nombreEmpresa'};
        $this->usuariosGlobal = $this->util_controller->cargaDatosUsuarios();
        $this->areasGlobal = $this->util_controller->cargaDatosAreas();
        
    }
    
    function index(){
        $this->load->view('login_view');
    }
    
    function inicio() {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            $data = array(
                    'permisos'=>$this->session->userdata('permisos'),
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'sucursal' => $this->session->userdata('sucursal'),
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'opcionClickeada' => '0'
                );
            $this->load->view('layouts/headerAdministrador_view',$data);
            $this->load->view('principal_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function mostrarOrdenes() {
        if ($this->is_logged_in()){
            $url = RUTAWS.'ordenes/obtener_ordenes.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            $data = array('nombre_Empresa'=>$this->nombreEmpresaGlobal);
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            if ($datos->{'estado'}==1) {
                $data = array(
                    'ordenes'=>$datos->{'ordenes'},
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('ordenes/adminOrdenes_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } else {
                $data = array(
                    'ordenes'=>null,
                    'usuarioDatos' => $this->session->userdata('nombre'),
                    'fecha' => $fechaIngreso,
                    'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                    'permisos' => $this->session->userdata('permisos'),
                    'opcionClickeada' => '7'
                        );
                $this->load->view('layouts/headerAdministrador_view',$data);
                $this->load->view('ordenes/adminOrdenes_view',$data);
                $this->load->view('layouts/pie_view',$data);
            } 
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoOrden($idUsuario,$idEtapa) {
        if ($this->is_logged_in()){
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s");
            
            //obtiene viajeras
            $url = RUTAWS.'viajeras/obtener_viajeras.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            
            $data = array(
                'idUsuario' => $idUsuario,
                'idEtapa' => $idEtapa,
                'viajeras'=>$datos->{'viajeras'},
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $fechaIngreso,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '7'
                );
            $this->load->view('ordenes/nuevoOrden_view',$data);
            $this->load->view('layouts/pie_view',$data);
        } else {
            redirect($this->cerrarSesion());
        }
    }

    function nuevoOrdenFromFormulario() {
        if ($this->is_logged_in()){
            if ($this->input->post('submit')){
                //REGISTRA LA ORDEN
                $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                $fecha = $dt->format("Y-m-d H:i:s");
                $hoy = $dt->format("Y-m-d");
                
                $numeroOrden = $this->input->post("numeroOrden");
                $cantidad = $this->input->post("cantidad");
                $viajera = $this->input->post("viajera");
                $idUsuario = $this->input->post("idUsuario");
                $idEtapa = $this->input->post("idEtapa");
                $data = array(
                    "numeroOrden" => $numeroOrden,
                    "fecha" => $fecha,
                    "cantidad" => $cantidad,
                    "viajera" => $viajera,
                    "idUsuario" => $idUsuario
                    );
                $data_string = json_encode($data);
                $ch = curl_init(RUTAWS.'ordenes/insertar_orden.php');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                $result = curl_exec($ch);
                curl_close($ch);
                $resultado = json_decode($result, true);
                if ($resultado['estado'] == 1) {
                    echo "<script>alert('Orden registrada correctamente.');</script>";
                    $this->registroMateriaPrima($idUsuario,$idEtapa,$numeroOrden,0);
                } else {
                    echo "<script>alert('Esta orden ya existe. Continuando orden...');</script>";
                    $this->registroMateriaPrima($idUsuario,$idEtapa,$numeroOrden,0);
                } 
            }
        } else {
            redirect($this->cerrarSesion());
        }
    }
    
    function registroMateriaPrima($idUsuario,$idEtapa,$numeroOrden,$cantidad) {
        if ($this->is_logged_in()) {
            //valida si hay captura de la etapa anterior
            $cantidad = str_replace("%22", "", $cantidad);
            if (($idEtapa != 3) && (($cantidad==0) || ($cantidad==null))){
                echo "<script>alert('Debes registrar la etapa anterior.');window.history.back();</script>";
            }            
            
            if ($idEtapa != 3) {
                $cantidad = 0;
            }             
            
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
            
            //obtiene maquinas
            $url = RUTAWS.'maquinaria/obtener_maquinas.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataMaquinas = curl_exec($ch);
            $datosMaquinas = json_decode($dataMaquinas);
            curl_close($ch);
            
            
            //obtiene la ultima orden registrada
            $url = RUTAWS.'ordenes/obtener_orden_por_numeroOrden.php?numeroOrden='.$numeroOrden;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //echo $data;
            
            $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
            $fecha = $dt->format("Y-m-d H:i:s");
            $hoy = $dt->format("Y-m-d");
            $url = RUTAWS.'materiasprimas/obtener_materiaprima_por_idEtapa.php?idEtapa='.$idEtapa;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataMateriaPrima = curl_exec($ch);
            $datosMateriaPrima = json_decode($dataMateriaPrima);
            curl_close($ch);
            //echo $dataMateriaPrima;
            
            
            //obtiene la ultima orden registrada
            $url = RUTAWS.'etapas/obtener_etapa_por_id.php?idEtapa='.$idEtapa;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataEtapa = curl_exec($ch);
            $datosEtapa = json_decode($dataEtapa);
            curl_close($ch);
            
            $data = array(
                'etapa'=>$datosEtapa->{'etapa'},
                'orden'=>$datos->{'orden'},
                'maquinas'=>$datosMaquinas->{'maquinas'},
                'materiasprimas'=>$datosMateriaPrima->{'materiasprimas'},
                'idUsuario'=> $idUsuario,
                'idEtapa'=> $idEtapa,
                'usuarioDatos' => $this->session->userdata('nombre'),
                'fecha' => $hoy,
                'cantidad' => $cantidad,
                'nombre_Empresa'=>$this->nombreEmpresaGlobal,
                'permisos' => $this->session->userdata('permisos'),
                'opcionClickeada' => '7'
                    );
            $this->load->view('registroMateriaPrima_view',$data);
        }
    }

    /*    
    //Exportar datos a Excel
    public function exportarExcel(){
        if ($this->is_logged_in()){
            //llamadod de ws
            # An HTTP GET request example
            $url = RUTAWS.'usuarios/obtener_usuarios.php';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $datos = json_decode($data);
            curl_close($ch);
            //fin llamado de ws
            //$id=$this->uri->segment(3);
            $nilai=$datos->{'usuarios'};
            $totn = 0;
            foreach($nilai as $h){
                $totn = $totn + 1;
            }
            $heading=array('USUARIO','CLAVE','PERMISOS','NOMBRE','AP.PATERNO','AP.MATERNO','TEL.CASA','CELULAR','SUCURSAL');
            $this->load->library('excel');
            //Create a new Object
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle("Empleados");
            //Loop Heading
            $rowNumberH = 1;
            $colH = 'A';
            foreach($heading as $h){
                $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
                $colH++;    
            }
            //Loop Result
            //$totn=$nilai->num_rows();
            $maxrow=$totn+1;
            $row = 2;
            $no = 1;
            foreach($nilai as $n){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$n->{'usuario'});
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,"1");
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$n->{'permisos'});
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$n->{'nombre'});
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$n->{'apellido_paterno'});
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$n->{'apellido_materno'});
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$n->{'telefono_casa'});
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,$n->{'telefono_celular'});
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$n->{'descripcionSucursal'});
                $row++;
                $no++;
            }
            //Freeze pane
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            //Cell Style
            $styleArray = array(
                    'borders' => array(
                            'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                    )
            );
            $objPHPExcel->getActiveSheet()->getStyle('A1:I'.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Empleados.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit();
        } else {
            redirect($this->cerrarSesion());
        }
    }	
    //fin exportar a excel
    */
    
    //**  Manejo de Sesiones
    function cerrarSesion() {
        $this->session->set_userdata('logueado',FALSE);
        $this->session->sess_destroy();
        $this->load->view('login_view');
    }

    function is_logged_in() {
        return $this->session->userdata('logueado');
    }
    //**  Fin Manejo de Sesiones
    
    
}

