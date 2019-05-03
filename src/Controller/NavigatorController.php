<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Rack;
use App\Controller\Traits\HasRepositories;

class NavigatorController extends Controller
{
    use HasRepositories;

    /**
     * show the left nav of devices
     * @Route("/nav/racks", name="nav_racks")
     * @param  Request $request [description]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navRacks(Request $request)
    {
        $racks = $this->getRackRepository()->findAll();

        return $this->render('nav/racks.html.twig', array(
            'racks' => $racks,
        ));
    }
}
