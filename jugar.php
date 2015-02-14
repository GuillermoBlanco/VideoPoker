<?php
    session_start();
    
    //empiezo sesion y cargo ficheros
    include './BarajaNaipes.php';

    $jugador=array();
    
    //Si la sesion no está creada la creo con todos las variables necesarias.
    if (!isset($_SESSION['jugador'])) {
        $jugador['ronda']=1;
        
        //cargo la baraja de ./BarajaNaipes.php y la barajo
        $baraja_partida=$baraja;
        barajar($baraja_partida);
        
        //La cargo en la sesión
        $jugador['baraja']=$baraja_partida;
        
        //Verifico si las varibles solicitadas se han pasado y las guardo
        if (isset($_GET['nombre'])) {
            $jugador['nombre']=$_GET['nombre'];

        }
        if (isset($_GET['dinero'])) {
            $jugador['dinero']=$_GET['dinero'];
        }
        //Guardo en sesión
        $_SESSION['jugador']=$jugador;
    }
     else {
         //La sesión está creada y la recojo
        $jugador=$_SESSION['jugador'];
    }

    //Muestro por pantalla datos del jugador
    echo '<h1>'.$jugador['nombre'].'</h1>
    <p>Rondas: '.$jugador['ronda'].'</p>';
    echo '<div>
        <form action="jugar.php" method="get" enctype="text/plain" >
        <p># cartas <input style="" type="select" min=5 max=10 name="cartas"></p>
        <input type="submit" value="enviar">
        </form>
        </div>';
    
    //Si me ha indicado las cartas, las recojo y lanzo la partida
    if (isset($_GET['cartas'])) {
        repartir($jugador, $_GET['cartas']);
        update($jugador);
    }
    
    //Muestro el dinero
    echo '<p> Bolsa: '.$jugador['dinero'].'</p>';
    
    //Guardo los datos en la sesión
    $_SESSION['jugador']=$jugador;
    
    
    //Entra la variable jugador con el número de cartas a repartirle
    function repartir (&$jugador, $num) {
        $jugador['mano']=  array_splice($jugador['baraja'], 0, $num);
        
        foreach ($jugador['mano'] as $carta) {
                      echo '<img src="naipes/'.$carta['palo'].'-'.$carta['numero'].'.gif"'.' WIDTH="40" HEIGHT="64">';
//                      echo '</br>';
                }
    }
    
    //Comprubeo si hay número siguales
    function equals ($mano) {
        $parejas=0;
        $trios=0;
        $poker=0;
        $array_keys=array();
        
        foreach ($mano as $carta) {
            array_push($array_keys, $carta['numero']);
        }
        
        $repeticiones = array_count_values($array_keys);
        
        foreach ($repeticiones as $key => $value) {
            echo '<p>'.$key.' Se repite '.$value.' veces</p>';
            
            switch ($value) {
                case 4:
                $poker++;
                    break;
                case 3:
                $trios++;
                    break;
                case 2:
                $parejas++;
                    break;

                default:
                    break;
            }
        }
        return array('parejas'=>$parejas, 'trios'=>$trios, 'poker'=>$poker);
    }
    
    //Comprubeo si hay palos siguales
    function color ($mano) {
        $array_values=array();
        $x=0;
        
        foreach ($mano as $carta) {
            array_push($array_values, $carta['palo']);
        }
        
        $repeticiones = array_count_values($array_values);
        
        foreach ($repeticiones as $key => $value) {
            echo '<p>'.$key.' Se repite '.$value.' veces</p>';
            
            if ($value>=3) $x=1;
        }
        
        return $x;        
    }
    
    function escalera ($mano) {
        $array_keys=array();
        $num=0;
        
        foreach ($mano as $carta) {
            array_push($array_keys, $carta['numero']);
        }
        
        for ($x=0; $x<count($array_keys); $x++) {
            
            for  ($y=1; $y<  count($array_keys)+1 || ($array_keys[$x]+1==$array_keys[$x+$y]); $y++){
                if ($y==5){
                    $num=$array_keys[$x];
                    
                    break;
                }
            }
        }
        return $num;;
    }
    
    //Actualizo los permios y la ronda
    function update (&$jugador ) {
        $inicio=$jugador['dinero'];
        $equals=  equals($jugador['mano']);
        $colors= color($jugador['mano']);
        $escalera= escalera($jugador['mano']);
        if ($escalera!=0) {$escalera=4;}
            
        $jugador['dinero']=$jugador['dinero']
                +$equals['parejas']+($equals['trios']*2)
                +($equals['poker']*5)+($colors*3)+$escalera-1;
        
        if ($jugador['dinero']==$inicio) {$jugador['dinero']--;}
        $jugador['ronda']++;
        
        print_r($escalera);
    }
?>
