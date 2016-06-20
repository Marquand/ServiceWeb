<?php

namespace PointWeb\AppBundle\Sitemap;

class Url{

    /**
     * Url complète de la page
     *
     * @var string
     */
    protected $loc ;

    /**
     * Date de dernière modification de la page (format Y-m-d)
     *
     * @var string
     */
    protected $lastmod;

    /**
     * Priorité de la page
     *
     * @var float
     */
    protected $priority;


    /**
     * @return string
     */
    public function getLoc(){
        return $this->loc;
    }

    /**
     * @return string
     */
    public function getLastmod(){
        return $this->lastmod;
    }

    /**
     * @return float
     */
    public function getPriority(){
        return $this->priority;
    }

    /**
     * @param $loc
     */
    public function setLoc($loc){
        $this->loc = $loc;
    }

    /**
     * @param $lastmod
     */
    public function setLastmod($lastmod){
        $this->lastmod = $lastmod;
    }

    /**
     * @param $priority
     */
    public function setPriority($priority){
        $this->priority = $priority;
    }
}