<?php
/**
 * Created by PhpStorm.
 * User: a.verilhac
 * Date: 16/07/2015
 * Time: 11:34
 */

namespace PointWeb\AppBundle\Twig;

use PointWeb\AdminBundle\Entity\File;
use PointWeb\AdminBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class AppExtension extends \Twig_Extension
{
    private $em;
    private $conn;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        $this->conn = $em->getConnection();
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('displayMenu', array($this, 'displayMenu')),
            new \Twig_SimpleFilter('getRef', array($this, 'getRef')),
        );
    }

    public function displayMenu($menus, $displayPictures = false)
    {
        $menu_twig = "<ul>";
        /** @var Menu $menu */
        foreach ($menus as $menu) {
            $menu_twig .= "<li>";
            if ($displayPictures && count($menu->getPictures()) > 0) {
                /** @var File $picture */
                foreach ($menu->getPictures() as $picture) {
                    $menu_twig .= "<img src='" . $picture->getWebPath($this->container->getParameter('base')) . "' alt='" . $picture->getName() . "' />";
                }
            }
            $menu_twig .= "<a href='" . $menu->getPath() . "'>" . $menu->getTitle() . "</a>";
            if (count($menu->getChildrens()) > 0) {
                $menu_twig .= $this->displayMenu($menu->getChildrens(), $displayPictures);
            }
            $menu_twig .= "</li>";

        }
        $menu_twig = "</ul>";

        return $menu_twig;
    }

    public function getRef($path, $type)
    {
        $response = "";
        if ($type != 'keywords') {
            $entityBase = $this->em->getRepository('PointWebReferencingBundle:Referencing')->findOneBy(array('path' => '/', 'online' => '1'));
            if ($entityBase) {
                $response = strip_tags($entityBase->{'get' . ucfirst($type)}());
            }
            $entity = $this->em->getRepository('PointWebReferencingBundle:Referencing')->findOneBy(array('path' => $path, 'online' => '1'));
            if ($entity) {
                $response = strip_tags($entity->{'get' . ucfirst($type)}());
                if ($type == 'description') {
                    if ($response == "") {
                        $response = strip_tags($entity->{'get' . ucfirst($type)}());
                    }
                }
            }
        } else {
            $entity = $this->em->getRepository('PointWebReferencingBundle:Keywords')->findAll();
            if (count($entity) > 0) {
                $response = strip_tags($entity[0]->{'get' . ucfirst($type)}());
            }
        }
        return $response;
    }


    public function getName()
    {
        return 'app_extension';
    }
}