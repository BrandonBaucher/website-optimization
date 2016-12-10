<?php

include 'ga_top.php';

$NUM_GEN = 100;
$SUCCESS_VAL = 2;

$fd = fopen ('out.csv', 'w');
fclose($fd);
$myGa = new ga(10);
for($i=0;$i<$NUM_GEN;$i++){
  for($popIndex=0;$popIndex<count($myGa->population->individuals);$popIndex++){
    $genes = $myGa->population->individuals[$popIndex]->getGenes();
    $count = 0;
    for($j=0;$j<3;$j++){
      $count += ($genes[$j]%$SUCCESS_VAL==0)? 1 : 0;
    }

    for($j=0;$j<2000*($count+1);$j++){
      //if(mt_rand(0,1) < .2*($count+1))//TODO why doesn't this work.......
        //$bool = True;
      //else
        //$bool = False;
      $myGa->updateClickRate($popIndex, True);
    }
    for($j=0;$j<10000-2000*($count+1);$j++){
      $myGa->updateClickRate($popIndex, False);
    }

  }
  print_r($myGa->population->individuals[0]);
  print($myGa->meanClickRate."\n");
  saveTimeStep( $myGa );
  $myGa->genNewPop();
}

function saveTimeStep( $ga )
{
  $fd = fopen( 'out.csv', 'a'); 
  $genes = [];
  foreach($ga->population->individuals as &$curr){
    $genes[] = $curr->getGenes()[0];
    $genes[] = $curr->getGenes()[1];
    $genes[] = $curr->getGenes()[2];
  }
  fprintf($fd,'%f,',$ga->meanClickRate);
  fputcsv($fd,$genes);
  fclose($fd);
}

?>
