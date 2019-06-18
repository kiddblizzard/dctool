<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Rack;
use App\Controller\Traits\HasRepositories;

class NavigatorController extends Controller
{
    use HasRepositories;

    /**
     * show the left nav of devices
     * @Route("/nav/racks/{rack}", name="nav_racks")
     * @param  Request $request [description]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navRacks(Request $request, Rack $rack = null)
    {
        $racks = $this->getRackRepository()->findAll();

        return $this->render('nav/racks.html.twig', array(
            'racks' => $racks,
            'currentRack' => $rack
        ));
    }

    /**
     * [index description]
     * @Route("/nav/sites", name="nav_sites")
     * @return [type] [description]
     */
    public function navSites()
    {
        $session = new Session();

        if (is_null($session->get('site'))) {
            $sites = $this->getUser()->getSites();
            $site = $site[0];
        } else {
            $site = $session->get('site');

        }
        $sites = $this->getSiteRepository()->findall();

        return $this->render('nav/site.html.twig', array(
            'sites' => $sites,
            'sessionSite' => $site
        ));
    }
}
