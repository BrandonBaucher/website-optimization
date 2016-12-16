<?php

class Individual {

    var $genes;
    var $fitness;
    var $successClicks;
    var $totalClicks;

    function createIndividual ( $availPosts )
    {
        $this->fitness = 0;
        $this->successClicks = 0;
        $this->totalClicks = 0;
        $this->genes = array_rand( array_flip($availPosts), 3);
    }

    function getGenes ()
    {
        return $this->genes;
    }

    function getStats ()
    {
        return array($this->successClicks,$this->totalClicks);
    }

    function increment ( $success )
    {
        if ( $success ){
            $this->successClicks++;
            $this->totalClicks++;
        }
        else
            $this->totalClicks++;
    }

    function resetIndividual ( $newGenes )
    {
        $this->genes = $newGenes;
        $this->totalClicks = 0;
        $this->successClicks = 0;
        $this->fitness = 0;

    }
} // End class Individual

?>
