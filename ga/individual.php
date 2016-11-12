<?php

class Individual {

    var $genes;
    float fitness;
    int successClicks;
    int totalClicks;

    function createIndividual ( $availPosts )
    {

    }

    function getGenes ()
    {
        return $genes;
    }

    function increment ( $success )
    {
        if ( $success ){
            successClicks++;
            totalClicks++;
        }
        else
            totalClicks++;
    }
} // End class Individual


?>
