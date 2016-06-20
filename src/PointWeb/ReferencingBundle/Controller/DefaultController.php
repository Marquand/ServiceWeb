<?php

namespace PointWeb\ReferencingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PointWebReferencingBundle:Default:index.html.twig');
    }
}
