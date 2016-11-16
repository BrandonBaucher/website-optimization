<?php

include 'individual.php';

class Population {

    public $individuals = array();
    public $size;

    function __construct ( $availablePosts, $size )
    {
        $this->size = $size;
        $this->individuals = array_pad($this->individuals,$size, new Individual);
        for ($i=0; $i<$size; ++$i){
            $this->individuals[$i]->createIndividual( $availablePosts );
        }
    }

    function getSize ()
    {
        return $this->size;
    }

}//End class Population

?>
