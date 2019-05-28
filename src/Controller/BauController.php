<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Constants\BauTypeOptions;
use App\Constants\BauStatusOptions;
use App\Controller\Traits\HasRepositories;
use App\Entity\Bau;
use App\Entity\Site;

class BauController extends Controller
{
    use HasRepositories;

    /**
     * [index description]
     * @Route("/bau", name="bau")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function getIndexAction(Request $request)
    {
        $pageNumber = $request->query->get('page', 1);
        $keyWord = $request->query->get('keyWord', null);

        $query = $this->getBauRepository()->findByKeyword($keyWord);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            10
        );

        return $this->render('bau/index.html.twig', array(
            'bauPagination' => $pagination,
            'bauStatusOptions' => BauStatusOptions::getOptions(),
            'keyWord' => $keyWord,
            'navbar' => 'bau'
        ));
    }

    /**
     * [index description]
     * @Route("/bau/new", name="new_bau")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function newAction(Request $request) {
        $bau = New Bau();
        $form = $this->getBauForm($bau, $this->generateUrl('new_bau'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bau = $form->getData();
            $bau->setStatus('pending');



            // ... perform some action, such as saving the bau to the database
            // for example, if bau is a Doctrine entity, save it!
            $this->saveBau($bau);
            return $this->redirectToRoute('bau');
        }

        return $this->render('bau/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'bau'
        ));
    }

    /**
     * @Route("/bau/{bau}/eidt", name="edit_bau")
     * @param  Bau $bau
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function editAction(Bau $bau, Request $request)
    {
        $form = $this->getBauForm(
            $bau,
            $this->generateUrl(
                'edit_bau',
                ['bau' => $bau->getId()]
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $this->saveBau($item);

            return $this->redirectToRoute('bau');
        }

        return $this->render('bau/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'bau'
        ));
    }

    /**
     * [getBauForm description]
     * @param  Bau   $bau [description]
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    private function getBauForm(Bau $bau, $path)
    {
        return $this->createFormBuilder($bau)
            ->setAction($path)
            ->setMethod('POST')
            ->add('chg_number', TextType::class,array('label' => 'CHG'))
            ->add('type', ChoiceType::class, [
                    'choices'  => BauTypeOptions::getBauTypeOptions(),
                ])
            ->add('description', TextareaType::class)
            ->add('start_time', DateTimeType::class)
            ->add('end_time', DateTimeType::class)
            ->add('vendor', TextType::class)
            ->add('remark', TextareaType::class)
            ->add('cmp', ChoiceType::class, [
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'CMP',
            ])
            ->add('inc_array', TextType::class, array('label' => 'INC'))
            ->add('task_array', TextType::class, array('label' => 'Task'))
            ->add('status', ChoiceType::class, [
                    'choices'  => BauStatusOptions::getOptions(),
                ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    /**
     * [saveBau description]
     * @param  Bau $bau [description]
     * @return [type]      [description]
     */
    private function saveBau($bau)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bau);
        $entityManager->flush();
    }

}
