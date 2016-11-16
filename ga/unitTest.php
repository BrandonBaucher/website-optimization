<?php

include 'individual.php';
include 'population.php';

function printIndividual( $curr )
{
    print("Genes:\n");
    $genes = $curr->getGenes();
    print_r($genes);
    print("Stats:\n");
    print_r($curr->getStats());
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

?>
