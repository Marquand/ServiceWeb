<?php

namespace PointWeb\AppBundle\Controller;

use Doctrine\Common\Persistence\Mapping\MappingException;
use PointWeb\AppBundle\Entity\Contact;
use PointWeb\AppBundle\Form\ContactType;
use PointWeb\AppBundle\Sitemap\Url;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PointWebAppBundle:Default:index.html.twig');
    }

    public function introductionAction()
    {
//        $this->execute();
        return $this->render('PointWebAppBundle:Default:intro.html.twig');
    }

    public function execute()
    {
        exec('MediaDev-Start.sh');
    }

    public function pageNotFoundAction()
    {
        return $this->render('PointWebAppBundle:Default:index.html.twig');
    }

    public function legalAction()
    {
        return $this->render('PointWebAppBundle:Default:legal.html.twig');
    }

    public function footerAction()
    {
        $date = new \DateTime();
        return $this->render('PointWebAppBundle::_footer.html.twig', array('date' => $date));
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $menus = $em->getRepository('PointWebAdminBundle:Menu')->findBy(
            array('parent' => null),
            array('position' => 'ASC')
        );
        return $this->render('PointWebAppBundle::_menu.html.twig', array(
            'menus' => $menus
        ));
    }


    /**
     * Page Contact
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function contactAction(Request $request)
    {
        $captchaKey = $this->container->getParameter('captchaKey');
        $entity = new Contact();
        $form = $this->createForm(new ContactType(), $entity, array(
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Envoyer'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
            }
            if ($captcha) {
                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$captchaKey."&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
                if ($response == false) {
                    return $this->redirectToRoute('point_web_app_contact');
                } else {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $message = \Swift_Message::newInstance()
                        ->setSubject('Contact site')
                        ->setFrom($entity->getEmail())
                        ->setTo($this->container->getParameter('mail_to'))
                        ->setBody($this->renderView('PointWebAppBundle:Mail:contact.html.twig', array(
                            'entity' => $entity
                        )))
                        ->setContentType('text/html');
                    $this->get('mailer')->send($message);

                    return $this->redirect($this->generateUrl('point_web_app_thanks'));
                }
            }

        }
        return $this->render('PointWebAppBundle:Default:contact.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'captchaKey' => $captchaKey
        ));

    }

    public
    function thanksAction()
    {
        return $this->render('PointWebAppBundle:Default:thanks.html.twig');
    }

    public
    function cookiesAction()
    {
        return $this->render('PointWebAppBundle:Default:cookies.html.twig');
    }

    /**
     * return the sitemeap with out xml encoding
     */
    public
    function sitemapAction()
    {
        $list = $this->getUrls();
        return $this->render('PointWebAppBundle:Default:sitemap.html.twig', array('urls' => $list));
    }

    /**
     * return sitemap with xml encoding
     */

    public
    function sitemapXmlAction()
    {
        /** @var XmlEncoder $encoders */
        $lists = $this->getUrls();
        $rootNode = new \SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' standalone='yes'?><urlset></urlset>");
        $rootNode->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        foreach ($lists as $list) {
            $url = $rootNode->addChild('url');
            $url->addChild('loc', $list->getLoc());
            $url->addChild('lastmod', $list->getLastmod());
            $url->addChild('priority', $list->getPriority());
        }

        $response = new Response();
        $response->setContent($rootNode->asXML());
        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }

    public
    function getUrls()
    {
        $em = $this->getDoctrine()->getManager();
        $now = new \DateTime();
        $finalRoutes = array();
        $routesToAvoid = array(
            'pageNotFound',
            'point_web_app_sitemap',
            'point_web_app_sitemapXml',
            'point_web_app_awsindex',
            'point_web_app_stats',
            'point_web_app_cookies',
            'point_web_app_thanks',
        );
        $bundlesToAvoid = array('FOSUserBundle');
        /** @var Router $router */
        $router = $this->container->get('router');
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();
        /** @var Route $route */
        foreach ($allRoutes as $route) {
            $routeName = $router->match($route->getPath());
            $routeName = $routeName['_route'];
            $bundle = explode('\\', $route->getDefault('_controller'));
            if (count($bundle) > 1) {
                $bundle = $bundle[0] . $bundle[1];
                if (
                    preg_match('/^\/admin/', $route->getPath()) == 0
                    && !in_array($routeName, $routesToAvoid)
                    && !in_array($bundle, $bundlesToAvoid)
                ) {
                    if (preg_match_all('/\{[a-z]+\}/', $route->getPath(), $slugs) > 0) {
                        $parameters = array();
                        $entityName = explode('_', $routeName);
                        $entityName = ucfirst($entityName[0]);
                        try {
                            $repository = $em->getRepository($bundle . ':' . $entityName);
                        } catch (MappingException $e) {
                            syslog(LOG_INFO, $e->getMessage());
                        }
                        if (isset($repository)) {
                            $entities = $repository->findAll();

                            foreach ($entities as $entity) {
                                foreach ($slugs[0] as $slug) {
                                    $start = 1;
                                    $length = strlen($slug) - 2;
                                    $slug = substr($slug, $start, $length);
                                    $parameters[$slug] = $entity->{'get' . ucfirst($slug)}();
                                }
                                $url = new Url();
                                $url->setPriority('0.5');
                                $url->setLastmod($now->format('d/m/Y'));
                                $url->setLoc(substr($this->container->getParameter('base'), 0, -1).$router->generate($routeName, $parameters));
                                $finalRoutes[] = $url;
                            }
                        }
                    } else {
                        $url = new Url();
                        $url->setPriority('0.5');
                        $url->setLastmod($now->format('d/m/Y'));
                        $url->setLoc(substr($this->container->getParameter('base'), 0, -1).$router->generate($routeName));
                        $finalRoutes[] = $url;
                    }
                }
            }
        }
        return $finalRoutes;
    }

    public
    function statsAction()
    {
        $response = new Response();
        $html = include(__DIR__ . '/../../../../stats/index.php');
        $response->setContent($html);
        return $response;
    }

    public
    function awsindexAction()
    {
        $response = new Response();
        $html = include(__DIR__ . '/../../../../stats/awsindex.html');
        $response->setContent($html);
        return $response;
    }


}
