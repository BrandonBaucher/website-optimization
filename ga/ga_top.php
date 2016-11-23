<?php

class ga {

  int $popSize;
  var $population;
  var $nextPopulation;
  
  int $currentIndividual; // an index, not an individual class instance

  // cost function variables
  int $meanClickRate;
  int $costSlope
  
  float $mutationRate = 0.01;

  function __construct($popSize) {
    // init pop 
  }

  public function genNewPop() {
    // cross over
  }

  private function computeCost() 
  {
    var $stats;
    var $individualClickRate;
    var $cost;

    for ($i=0; $i<$this->popSize; $i++) { 
      $stats = $this->population.getIndividual($i).getStats();
      $individualClickRate = floatval(stats[0])/floatval(stats[1]);
      // x = $individualClickRate - $this->meanClickRate
      // m = costSlope
      // b = 0.5, the midpoint
   
      $cost =(($individualClickRate - $this->meanClickRate) * $this->costSlope) + 0.5;
      if($cost > 1.0) 
      {
        return 1.0;
      }
      elseif ($cost < 1.0) 
      {
        return 0.0;
      }
      else 
      {
        return $cost;
      }  
    } // End for
  }

  private function selectParents() 
  {

  }

  private function crossoverParents() 
  {

  }

  private function mutatePopulation() 
  {
    if (mt_rand() / mt_getrandmax() < $this->mutationRate) 
    {
      // choose random population member
      int $mutantIdx = mt_rand(0,$this->popSize-1);
      
      // get gene not in current individual

      

      // reset individual
    }
  }

  private function computeMeanClickRate() 
  {

  }

  public function updateClickRate($genNumber, $popIndex, $success) 
  {

  }

}// end ga class
?>
