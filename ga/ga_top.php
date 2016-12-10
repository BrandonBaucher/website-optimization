<?php

include 'population.php';

//Needed for database connection
//include './wp-load.php';

class ga {

  var $popSize;
  var $population;
  var $nextPopulation;
  var $availablePosts;
  var $currentIndividual; // an index, not an individual class instance

  // cost function variables
  var $meanClickRate;
  var $costSlope;

  var $mutationRate = 0.01;

  // init pop
  function __construct($popSize){
    $this->popSize = $popSize;
    $this->updateAvailablePosts();
    $this->population = new Population($this->availablePosts, $popSize);
    $this->nextPopulation = $this->population; // quickly init next pop with junk
  }


  public function genNewPop() {

    // select two parents
    $parent0 = $this.selectPrent();
    $parent1 = $this.selectPrent();

    // cross over
    $this->crossover($parent0, $parent1);
    $this->population = $this->nextPopulation;

    $this->mutatePopulation();
  }

  private function computeCost()
  {$this->crossover($parent0, $parent1);
    $stats;
    $individualClickRate;
    $cost;

    for ($i=0; $i<$this->popSize; $i++) {
      $stats = $this->population->individuals[$i]->getStats();
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

    $wheel = array();
    $costSum = 0;
    $upperBound;
    $parentIndex;

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
    $numGenes = count($parent0->getGenes());
    // concat parents
    $geneSet = array_unique(array_merge($parent0, $parent1));
    // pull 3 random genes
    $newGenes = array_rand($geneSet, $numGenes);
    // return new genes
    return $newGenes;
  }

  private function crossover($parent0, $parent1)
  {
    $newGenes;

    for($i=0; $i<$this->popSize; $i=$i+1)
    {
      $newGenes = singleCrossover($parent0, $parent1);
      $this->nextPopulation->individual[$i]->resetIndividual($newGenes);
    } // end for
  }

  private function mutatePopulation()
  {
    if (mt_rand() / mt_getrandmax() < $this->mutationRate)
    {
      // choose random population member
      $mutantIdx = mt_rand(0,$this->popSize-1);
      $mutantIndividual = $this->population[$mutantIdx];
      // get gene not in current individual
      // ASSUMING $availPosts IS JUST AN INDEXED ARRAY OF DB IDs
      $newGene = mt_rand(0,count($this->availabePosts)-1); // just gets the index
      $newGene = $this->availablePosts[$newGene];
      // randomly choose mutant gene index
      $geneIdx = mt_rand(0,count($this->population[$mutantIdx]->getGenes()));
      $genes = $this->population[$mutantIdx]->getGenes();
      $genes[$geneIdx] = $newGene;
      $this->population[$mutantIdx]->resetIndvidual($genes);
     } // end if
  }

  private function computeMeanClickRate($genNumber)
  {
    $success = 0;
    $total = 0;
    for($i=0;$i<count($this->population->individuals);$i++){
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
    $this->availablePosts = [];
    //$results = wp_terms('category');
    //foreach ($results as &$curr){
    //  $this->availablePosts[] = $curr->term_id;
    //}
    $this->availablePosts = array(1,2,3,4,5,6,7,8);/*MAGIC!*/;
  }

  public function unitTest()
  {
    for($i=0;$i<1000;$i++){
      $this->updateClickRate(mt_rand(0,$this->popSize-1),mt_rand(0,1));
    }
    $this->computeMeanClickRate(0);
    print("Mean click rate: ");
    print($this->meanClickRate);
    print("\n");
  }

}// end ga class
?>
