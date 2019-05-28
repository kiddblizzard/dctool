<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Receiving;
use App\Controller\Traits\HasRepositories;

class ReceivingController extends Controller
{
    use HasRepositories;

    /**
     * [index description]
     * @Route("/receivings", name="receivings")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function getIndexAction(Request $request)
    {
        $pageNumber = $request->query->get('page', 1);
        $keyWord = $request->query->get('keyWord', null);

        $query = $this->getReceivingRepository()->findByKeyword($keyWord);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            10
        );

        return $this->render('receiving/index.html.twig', array(
            'receivingPagination' => $pagination,
            'keyWord' => $keyWord,
            'navbar' => 'receiving'
        ));
    }

    /**
     * [index description]
     * @Route("/receivings/new", name="new_receiving")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function newAction(Request $request) {
        $receiving = New Receiving();
        $form = $this->getReceivingForm($receiving, $this->generateUrl('new_receiving'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receiving = $form->getData();
            $receiving->setStatus('new');
            // ... perform some action, such as saving the receiving to the database
            // for example, if Receiving is a Doctrine entity, save it!
            $this->saveReceiving($receiving);
            return $this->redirectToRoute('receivings');
        }

        return $this->render('receiving/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'receiving'
        ));
    }

    /**
     * @Route("/receivings/{receiving}/eidt", name="edit_receiving")
     * @param  Receiving $receiving
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function editAction(Receiving $receiving, Request $request)
    {
        $form = $this->getReceivingForm(
            $receiving,
            $this->generateUrl(
                'edit_receiving',
                ['receiving' => $receiving->getId()]
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $this->save($item);

            return $this->redirectToRoute('receivings');
        }

        return $this->render('receiving/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'receiving'
        ));
    }

    /**
     * [getReceivingForm description]
     * @param  Receiving   $receiving [description]
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    private function getReceivingForm(Receiving $receiving, $path)
    {
        return $this->createFormBuilder($receiving)
            ->setAction($path)
            ->setMethod('POST')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Receiving' => 'receiving',
                    'Sending' => 'sending',

                ],
            ])
            ->add('detail', TextType::class)
            ->add('delivery_info', TextType::class, ['required' => false])
            ->add('planned_date', DateType::class)
            ->add('status', HiddenType::class)
            ->add('access', ChoiceType::class, [
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ],
            ])
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

}
