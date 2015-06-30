<?php

abstract class ParseDataFromLinks{
    abstract protected function processingReferences($linksToJobsArray);
    public function getProcessingReferences($linksToJobsArray){
        return $this->processingReferences($linksToJobsArray);
    }

}