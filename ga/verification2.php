<?php

include 'ga_top.php';

$NUM_GEN = 100;
$SUCCESS_VAL = 500;

$fd = fopen ('out.csv', 'w');
fclose($fd);
$myGa = new ga(20);
for($i=0;$i<$NUM_GEN;$i++){
  for($popIndex=0;$popIndex<count($myGa->population->individuals);$popIndex++){
    $genes = $myGa->population->individuals[$popIndex]->getGenes();
    $dist = 0;
    for($j=0;$j<3;$j++){
      $dist += abs($genes[$j] - $SUCCESS_VAL);
    }
    $dist /= 3;

    for($j=0;$j<10000;$j++){
      if(mt_rand()/mt_getrandmax() < (0.9 - 0.8*$dist/1000.0))//TODO why doesn't this work.......
        $bool = True;
      else
        $bool = False;
      $myGa->updateClickRate($popIndex, $bool);
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
