<?php 
   
   require_once "Conexion.php"; // incluimos el archivo que contiene la clase conexion


     /************************************************
     Creamos la clase hotel para obtener las tarifas de los obteles y comparar cual sale mas barato
     ************************************************/
class hotelesModelo extends Conexion 
{     
    public function __construct() 
    { 
        parent::__construct(); 
    } 

    public function get_hotel()  //función para probar la conexion 
    { 
        $result = $this->_db->query('SELECT *  FROM hotel '); 
         
        $hotel= $result->fetch_all(MYSQLI_ASSOC); 
         
        return $hotel; 
    } 
    /************************************************
     metodo para obtener las tarifas de los obteles y comparar cual sale mas barato
     ************************************************/
    public function get_precios($totalhabil,$totalfines,$clientevip) 
    { 
        $sql= "SELECT *  FROM hotel ";
        $result = $this->_db->query($sql); 
        $reservas= $result->fetch_all(MYSQLI_ASSOC); 

        $con = 0; 
           foreach ($reservas as $row)
           { 
             if ($clientevip == 'SI')
             {  
               $preciofinsemana= $row['preciovipsd'] *$totalfines; 
               $preciolunvin= $row['precioviplv'] * $totalhabil;
               $preciototal = $preciofinsemana + $preciolunvin;
             
             }else{
               $preciofinsemana= $row['precioregularsd'] *$totalfines; 
               $preciolunvin= $row['precioregularlv'] * $totalhabil;
               $preciototal = $preciofinsemana + $preciolunvin;
             }
               $con = $con +1; 
             if ($con == 1)
             {  
                $preciomenor =   $preciototal ;
                $array = array($row['id_hotel'],$row['nombre'],$preciomenor);
             }  
             if ($preciomenor >= $preciototal)
             {  
                $preciomenor =   $preciototal ;
                $array = array($row['id_hotel'],$row['nombre'],$preciomenor);
             }
             
           } 

           return $array;
    } 
     /************************************************
     metodo para obtener cual cliente es Frecuente
     ************************************************/
  public function get_cliente($pasaporte) 
    { 
        $sql= "SELECT vip FROM cliente WHERE nropasaporte = '".$pasaporte."'";
        $result = $this->_db->query($sql); 
         
        $cliente= $result->fetch_all(MYSQLI_ASSOC);

        if ($cliente != null){
         foreach ($cliente as $row)
           { 
              $clientevip = $row['vip'];
           } 
          
        }else{
              $clientevip ='NO';
           
        } 
        return $clientevip;
    }  

} 
?> 