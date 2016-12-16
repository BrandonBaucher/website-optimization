<?php

include 'population.php';

function printIndividual( $curr )
{
    print("Genes:\n");
    $genes = $curr->getGenes();
    print_r($genes);
    print("Stats:\n");
    print_r($curr->getStats());
}

function printPopulation( $curr )
{
    print("Size:\n");
    $size = $curr->getSize();
    print_r($size."\n");
    print("First Individual:\n");
    printIndividual($curr->individuals[0]);
    print("Last Individual:\n");
    printIndividual(end($curr->individuals));
}

print("Testing Individual class:\n");
print("New Individual:\n");
$test = new Individual;
$test->createIndividual( array(1,2,3,4,5) );
printIndividual($test);

print("Increment 1 success and 1 fail:\n");
$test->increment(true);
$test->increment(false);
printIndividual($test);

print("Resetting Individual:\n");
$test->resetIndividual(array(5,6,7));
printIndividual($test);

print("Testing Population class:\n");
print("New Population Size 10:\n");
$test2 = new Population(array(1,2,3,4,5),10);
printPopulation($test2);

print("Increment 1 success and 1 fail on first individual:\n");
$test2->individuals[0]->increment(true);
$test2->individuals[0]->increment(false);
printPopulation($test2);

?>
