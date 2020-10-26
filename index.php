<?php


function saveFile($aLineas){
  

    // Abrir el archivo:
    $archivo = fopen("xml1.xml", "w+b");
    // Guardar los cambios en el archivo:
     foreach( $aLineas as $linea )
         fwrite($archivo, $linea);

fclose($archivo);
 
}

function contar_palabras($cadena)
{
    return count(explode("", $cadena));
}

$archivo = fopen('xml.xml', 'r');

$cadena_buscada = '<div name="Activity">';
$cadena_buscada2 = '</div>';

$numlinea = 0;
$aux = array();
$apertura = FALSE;

function limpioCaracteresXML($cadena){
    $search  = array("<", ">", "&", "'",'"');
    $replace = array("(menor)", "(mayor)", "(ampersand)", "(comilla)","(doble comilla)");
    $final = str_replace($search, $replace, $cadena);
    return $final;
}

$aLineas = file("xml.xml");

foreach ($aLineas as $linea) {

  
    echo 'Revisando la linea ' . $numlinea . '<br/>';
    //echo $linea.'<br/>';
    $posicion_coincidencia = strripos($linea, $cadena_buscada);

    if ($apertura) {


        $posicion_coincidencia2 = strripos($linea, $cadena_buscada2);
        if ($posicion_coincidencia2  !== FALSE) {

          $rest =str_replace($cadena_buscada2,"", $linea);  
          $aux[] = limpioCaracteresXML($rest);
          $aLineas[$numlinea] = limpioCaracteresXML($rest) . $cadena_buscada2 ;
            $apertura = FALSE;
        } else {
            $aLineas[$numlinea] = limpioCaracteresXML($rest);
            $aux[] = limpioCaracteresXML($linea);
        }
    }

    if ($posicion_coincidencia !== FALSE) {
        
        // echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: " . $posicion_coincidencia;
        $rest = substr($linea, ($posicion_coincidencia + strlen($cadena_buscada)), (strlen($linea) - ($posicion_coincidencia + strlen($cadena_buscada))));
        /*  echo '<pre>';
        echo '('. strlen($linea).')'.'[' .htmlspecialchars ($linea) . ']'.'{'.htmlspecialchars ($rest).'}';
        echo '</pre>';*/

        $apertura = TRUE;
        $posicion_coincidencia2 = strripos($linea, $cadena_buscada2);
        if ($posicion_coincidencia2  !== FALSE) {
            
          $rest =str_replace($cadena_buscada2,"", $rest);  
          $aux[] = limpioCaracteresXML($rest);
          $aLineas[$numlinea] =$cadena_buscada.limpioCaracteresXML($rest) . $cadena_buscada2 ;

            $apertura = FALSE;
        }else{
            $aLineas[$numlinea] =$cadena_buscada.limpioCaracteresXML($rest);

        $aux[] = limpioCaracteresXML($rest);
        }
    }




    $numlinea++;
}

fclose($archivo);
echo '<pre>';
print_r($aux);
echo '</pre>';
saveFile($aLineas);