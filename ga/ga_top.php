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
  var $costSlope = 2; 
  var $mutationRate = 0.01;

  // init pop
  function __construct($popSize){
    $this->popSize = $popSize;
    $this->updateAvailablePosts();
    $this->population = new Population($this->availablePosts, $popSize);
    $this->nextPopulation = new Population($this->availablePosts, $popSize);
  }


  public function genNewPop() {
    
    // compute cost of current pop
    $this->computeCost();

    // cross over
    $this->crossover();
    $this->population = $this->nextPopulation;
    $this->nextPopulation = new Population($this->availablePosts, $this->popSize);

    $this->mutatePopulation();
  }

  private function computeCost() {
    $stats;
    $individualClickRate;
    $cost;
    
    // update mean click rate
    $this->computeMeanClickRate();
    
    for ($i=0; $i<$this->popSize; $i++) {
      $stats = $this->population->individuals[$i]->getStats();
      $individualClickRate = floatval($stats[0])/floatval($stats[1]);
      // x = $individualClickRate - $this->meanClickRate
      // m = costSlope
      // b = 0.5, the midpoint
      $cost =($individualClickRate * $this->costSlope) - $this->meanClickRate;
      if ($cost <= 0.01)
      {
        //return 0.0;
        $this->population->individuals[$i]->fitness = 0.01;
      }
      else
      {
        //return $cost;
        $this->population->individuals[$i]->fitness = $cost; 
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
      $wheel[$i] = $costSum + $this->population->individuals[$i]->fitness;
      $costSum = $costSum + $this->population->individuals[$i]->fitness;
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
    return $this->population->individuals[$parentIndex];
  }

  private function singleCrossover($parent0, $parent1)
  {
    $numGenes = count($parent0->getGenes());
    // concat parents
    $geneSet = array_unique(array_merge($parent0->getGenes(), $parent1->getGenes()));
    // pull 3 random genes
    $newGenes = array_rand(array_flip($geneSet), $numGenes);
    // return new genes
    return $newGenes;
  }

  private function crossover()
  {
    $newGenes;

    for($i=0; $i<$this->popSize; $i=$i+1)
    {
      // select two parents
      $parent0 = $this->selectParent();
      $parent1 = $this->selectParent();
      while($parent0->getGenes()==$parent1->getGenes()) {
        $parent1 = $this->selectParent();
      }
      $newGenes = $this->singleCrossover($parent0, $parent1);
      $this->nextPopulation->individuals[$i]->resetIndividual($newGenes);
    } // end for
  }

  private function mutatePopulation()
  {
    if (mt_rand() / mt_getrandmax() < $this->mutationRate)
    {
      // choose random population member
      $mutantIdx = mt_rand(0,$this->popSize-1);
      $mutantIndividual = $this->population->individuals[$mutantIdx];
      // get gene not in current individual
      // ASSUMING $availPosts IS JUST AN INDEXED ARRAY OF DB IDs
      $newGene = mt_rand(0,count($this->availablePosts)-1); // just gets the index
      $newGene = $this->availablePosts[$newGene];
      // randomly choose mutant gene index
      $geneIdx = mt_rand(0,count($this->population->individuals[$mutantIdx]->getGenes())-1);
      $genes = $this->population->individuals[$mutantIdx]->getGenes();
      $genes[$geneIdx] = $newGene;
      $this->population->individuals[$mutantIdx]->resetIndividual($genes);
     } // end if
  }

  private function computeMeanClickRate()
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
    $this->availablePosts = range(1,100);/*MAGIC!*/;
  }

  public function unitTest()
  {
    // test constructor
    print("Testing Constructor\n");
    print("popSize (should be 5): ");
    print_r($this->popSize."\n");
    print("avaliable posts (should be 1-8): \n");
    print_r($this->availablePosts);
    print("population\n");
    print_r($this->population->individuals);
    
    // test compute cost
    print("Testing Compute Cost\n");
    // set arbitrary click rate on individuals
    $this->population->individuals[0]->totalClicks = 100;
    $this->population->individuals[1]->totalClicks = 100;
    $this->population->individuals[2]->totalClicks = 100;
    $this->population->individuals[3]->totalClicks = 100;
    $this->population->individuals[4]->totalClicks = 100;

    $this->population->individuals[0]->successClicks = 10;
    $this->population->individuals[1]->successClicks = 55;
    $this->population->individuals[2]->successClicks = 99;
    $this->population->individuals[3]->successClicks = 10;
    $this->population->individuals[4]->successClicks = 10;
    // chose arbitrart mean click rate
    //$this->meanClickRate = 0.5;
    // run compute cost
    $this->computeCost();
    //output results
    print("meanClickRate (~0.5):\n");
    print_r($this->meanClickRate."\n");
    print("costSlope (2):\n");
    print_r($this->costSlope."\n");
    print("Indiv 0 Cost (should be 0.01): ");
    print_r($this->population->individuals[0]->fitness."\n");
    print("Indiv 1 Cost (should be ~0.6): ");
    print_r($this->population->individuals[1]->fitness."\n");       
    print("Indiv 2 Cost (should be ~1.48): ");
    print_r($this->population->individuals[2]->fitness."\n");  
    print_r($this->population->individuals[3]->fitness."\n");  
    print_r($this->population->individuals[4]->fitness."\n");  

    // test select parent
    print("\n\nTesting Select Parent\n");
    $hist = array();
    for($i=0; $i<$this->popSize; $i=$i+1){
      $hist[strval($this->population->individuals[$i]->fitness)] = 0;
    }

    for($i=0; $i<1000; $i+=1) {
      $parent = $this->selectParent();
      $hist[strval($parent->fitness)] += 1;
    }

    print_r($hist); 
    // test crossover
    print("Testing Single Crossover\n");
    $newGenes = $this->singleCrossover($this->population->individuals[0],$this->population->individuals[1]);
    print_r($this->population->individuals[0]);
    print_r($this->population->individuals[1]);
    print_r($newGenes);

    print("\n\nTesting Crossover\n");
    $this->crossover($this->population->individuals[0],$this->population->individuals[1]);
    print_r($this->population->individuals[0]->getGenes());
    print_r($this->population->individuals[1]->getGenes());
    print_r($this->population->individuals[2]->getGenes());
    print_r($this->nextPopulation->individuals[0]->getGenes());
    print_r($this->nextPopulation->individuals[1]->getGenes());
    print_r($this->nextPopulation->individuals[2]->getGenes());

    // test mutate
    print("\n\nTesting Mutate\n");
    print_r($this->population->individuals);
    $this->mutationRate = 2;
    $this->mutatePopulation();
    print_r($this->population->individuals);

    // test compute mean click rate
    print("\n\nTesting Compute Mean Click Rate\n");
     for($i=0;$i<1000;$i++){
      $this->updateClickRate(mt_rand(0,$this->popSize-1),mt_rand(0,1));
    }
    $this->computeMeanClickRate(0);
    print("Mean click rate: ");
    print($this->meanClickRate);
    print("\n");
    // test update click rate 
    print("testing update click rate\n");
    print("before: ");
    print_r($this->population->individuals[0]->getStats());
    $this->updateClickRate(0,1);
    $this->updateClickRate(0,0);
    print("after: ");
    print_r($this->population->individuals[0]->getStats());

    // test update available posts
    $this->updateAvailablePosts();
    // test gen new pop
    $this->genNewPop();

  }

}// end ga class
?>
