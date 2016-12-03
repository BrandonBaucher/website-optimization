<?php

class ga {

  var $popSize;
  var $population;
  var $nextPopulation;
  var $availablePosts; 
  var $currentIndividual; // an index, not an individual class instance

  // cost function variables
  var $meanClickRate;
  var $costSlope

  float $mutationRate = 0.01;

  // init pop 
  function __construct($popSize{ 
    $this->updateAvailablePosts();
    $this->population = new Population($this->availablePosts, $popSize);
    $this->nextPopulation = $this->population; // quickly init next pop with junk
  }


  public function genNewPop() {

    // select two parents
    var $parent0 = $this.selectPrent();
    var $parent1 = $this.selectPrent();

    // cross over
    $this->nextPopulation = $this->crossover($parent0, $parent1);
    $this->population = $this->nextPopulation;

    $this->mutatePopulation();
  }

  private function computeCost() 
  {
    var $stats;
    var $individualClickRate;
    var $cost;

    for ($i=0; $i<$this->popSize; $i++) { 
      $stats = $this->population->getIndividual($i).getStats();
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

  private function selectParent() 
  {
    // roulet wheel selection for a single parent, will be called twice for crossover
    // returns the index of the selected parent

    var $wheel = array();
    var $costSum = 0;
    var $upperBound;
    var $parentIndex;

    // build 'roulet wheel' by adding fitnesses 
    for($i=0; $i<$this->popSize; $i++) {
      $wheel[$i] = costSum + $this->population->individuals[$i]->fitness;
      $costSum = costSum + $this->population->individuals[$i]->fitness; 
    } // end for

    // randomly select povar on wheel
    $povar = ( mt_rand() / mt_getrandmax() ) * $costSum;

    // compute index of the selected point
    $upperBound = $wheel[0];
    $parentIndex = 0;
    while($povar > $upperBound) {
      $parentIndex = $parentIndex + 1;
      $upperBound = $wheel[$parentIndex];
    } // end while
    return $parentIndex;
  }

  private function singleCrossover($parent0, $parent1) 
  {
    var $numGenes = count($parent0->getGenes());
    // concat parents 
    var $geneSet = array_unique(array_merge($parent0, $parent1));
    // pull 3 random genes
    var $newGenes = array_rand($geneSet, $numGenes);
    // return new genes
    return $newGenes
  } 

  private function crossover($parent0, $parent1) 
  {
    for($i=0; $i<$this->popSize; $i=$i+1)
    {



    } 
  }

  private function mutatePopulation() 
  {
    if (mt_rand() / mt_getrandmax() < $this->mutationRate) 
    {
      // choose random population member
      var $mutantIdx = mt_rand(0,$this->popSize-1);

      // get gene not in current individual
      // ASSUMING $availPosts IS JUST AN INDEXED ARRAY OF DB IDs  
      var $newGene = mt_rand(0,count($this->availabePosts)-1);

      // TODO decide if individual should be reset on mutate
    }
  }

  private function computeMeanClickRate($genNumber) 
  {
    $success = 0;
    $total = 0;
    for($i=0;i<count($this->population->individuals);$i++){
      $success += $this->population->individuals[$i]->getStats()[0];
      $total += $this->population->individuals[$i]->getStats()[1];
    }
    $this->meanClickRate = $success/$total;
  }

  public function updateClickRate($popIndex, $success)
  {
    $this->population->individuals[$popIndex]->increment($success);
  }

  private function updateAvailablePosts() {
    //    $this->availablePosts = /*MAGIC!*/;
  } 

}// end ga class
?>
