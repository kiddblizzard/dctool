<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Task;


class LoginController extends Controller
{
    /**
     * [index description]
     * @Route("/login123")
     * @return [type] [description]
     */
    public function index()
    {
        $task = New Task();

        $tasks = $this->getTaskRepository()->findByStatus('new');
        var_dump($tasks);

        $form = $this->getDeviceExcelForm(
            $this->generateUrl('device_upload_excel')
        );

        return $this->render('index/index.html.twig', array(
            'tasks' => $tasks,
            'navbar' => 'home'
        ));
    }

    private function getLoginForm()
    {
        return $this->createFormBuilder($bau);
    }

    /**
     * [getRepository description]
     * @return [type] [description]
     */
    private function getTaskRepository () {
        return $this->getDoctrine()->getRepository(Task::class);
    }
}
