<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $baus = $this->getBauRepository()->findForHome();
        $receivings = $this->getReceivingRepository()->findByStatus('new');

        return $this->render('index/index.html.twig', array(
            'receivingPagination' => $receivings,
            'bauPagination' => $baus,
            'bauStatusOptions' => BauStatusOptions::getOptions(),
            'navbar' => 'home'
        ));
    }
}
