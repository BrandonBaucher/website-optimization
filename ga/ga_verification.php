<?php

include 'ga_top.php';

$NUM_GEN = 100;
$SUCCESS_VAL = 2;

$fd = fopen ('out.csv', 'w');
fclose($fd);
$myGa = new ga(10);
for($i=0;$i<$NUM_GEN;$i++){
  for($popIndex=0;$popIndex<count($myGa->population);$popIndex++){
    $genes = $myGa->population->individuals[$popIndex]->getGenes();
    $count = 0;
    for($j=0;$j<3;$j++){
      $count += ($genes[$j]%$SUCCESS_VAL==0)? 1 : 0;
    }

    for($j=0;$j<1000;$j++){
      $myGa->updateClickRate($popIndex, (mt_rand(0,1)<.2*($count+1)));
    }

  }
  print($myGa->meanClickRate."\n");
  saveTimeStep( $ga );
  $myGa->genNewPop();
}

function saveTimeStep( $ga )
{
  $fd = fopen( 'out.csv', 'a'); 
  $genes = [];
  foreach($ga->population->individuals as &$curr){
    $genes[] = $curr->getGenes();
  }
  fprinf($fd,'%d',$ga->meanClickRate);
  fclose($fd);
}

?>
