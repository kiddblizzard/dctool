<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Receiving;
use App\Constants\BauStatusOptions;
use App\Controller\Traits\HasRepositories;

class IndexController extends Controller
{
    use HasRepositories;

    /**
     * [index description]
     * @Route("/index", name="dashboard")
     * @return [type] [description]
     */
    public function index()
    {
        $session = new Session();
        $site = $this->getSiteRepository()->find($session->get('site'));
        $baus = $this->getBauRepository()->findForHome($site);
        $receivings = $this->getReceivingRepository()->findForHome($site);

        return $this->render('index/index.html.twig', array(
            'receivingPagination' => $receivings,
            'bauPagination' => $baus,
            'bauStatusOptions' => BauStatusOptions::getOptions(),
            'navbar' => 'home'
        ));
    }
}
