<?php 
     /*************************************************************/
     /*Creando un WEB Service para Consultar el  hotel mas barato**/
     /**************Creando por Carlos Rivera**********************/
     /*************************************************************/
    include_once "nusoap/lib/nusoap.php"; 
    $servicio = new soap_server();   
    $ns= "urn:serviciowsdl";
    $servicio->configureWSDL("ServicioWeb",$ns);
    $servicio->schemaTargetNamespace = $ns;
    /* la funciòn register es el encargado de recibir las entradas procesarlas y emitir las salidas de nuestro webservice */
    $servicio->register("MiFuncion",array('pasaporte' => 'xsd:string','fechaini' => 'xsd:date','fechafin' => 'xsd:date'),array('return'=>'xsd:string'),$ns);
    /************************************************
     función que procesara las entradas del WEBService
     ************************************************/
   function MiFuncion($pasaporte,$fechaini,$fechafin){    
     include_once  "hotel.php"; 
     $fecha1 = strtotime($fechaini); // 
     $fecha2 = strtotime($fechafin);
     $cont = 0; // variable para contar sabados y domingo
     $total= 0;// variable para contar fines de semana
     // ciclo para contar la cantidad de días entre las 2 fechas
      for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){ 
         if((date('D',$fecha1)== 'Sun') or (date('D',$fecha1)== 'Sat') ) {
            $cont =$cont+1;
         } 
           $total =$total+1;
       }
         $totalhabil = $total -  $cont;  //dias de semana  
         $totalfines =$cont;             // sabados y domingo
            
         $hotelesModelo = new hotelesModelo(); //instanciamos la clase hotelesModelo
       
         $cliente = $hotelesModelo->get_cliente($pasaporte); //utilizamos el metodo get_cliente para verificar que el usuario sea regular
         $clientevip = $cliente ;

         $hotelPrecio = new hotelesModelo(); // utilizamos el metodo getprecios para obtener el hotel mas barato
         $array = $hotelPrecio->get_precios($totalhabil,$totalfines,$clientevip);
  
      /*******Resultado que retornara el Web Service****************/
      $resultado = "El hotel mas economico para este rango de fechas es el: ".$array[1]." por un monto de: ".$array[2];
      return  $resultado;
   }

   $HTTP_RAW_POST_DATA =isset( $HTTP_RAW_POST_DATA ) ? $HTTP_RAW_POST_DATA :'';
   $servicio-> service($HTTP_RAW_POST_DATA);  
?>