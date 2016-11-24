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

  private function selectParent( $population ) 
  {
    // roulet wheel selection for a single parent, will be called twice for crossover
    // returns the index of the selected parent
    
    var $wheel = array();
    var $costSum = 0;
    var $upperBound;
    var $parentIndex;

    // build 'roulet wheel' by adding fitnesses 
    for($i=0; $i<$this->popSize; $i++) {
      $wheel[$i] = costSum + $population->individuals[$i]->fitness;
      $costSum = costSum + $population->individuals[$i]->fitness; 
    } // end for

    // randomly select point on wheel
    $point = ( mt_rand() / mt_getrandmax() ) * $costSum;

    // compute index of the selected point
    $upperBound = $wheel[0];
    $parentIndex = 0;
    while($point > $upperBound) {
      $parentIndex = $parentIndex + 1;
      $upperBound = $wheel[$parentIndex];
    } // end while
    return $parentIndex;
  }

  private function crossoverParents($parent0, $parent1) 
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
