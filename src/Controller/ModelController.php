<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Part;
use App\Entity\Model;
use App\Entity\Manufacturer;
use App\Entity\Location;
use App\Constants\ModelTypeOptions;
use App\Controller\Traits\HasRepositories;
use App\Controller\Traits\HasUploadFile;
use App\Service\FileUploader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ModelController extends Controller
{
    use HasRepositories;
    use HasUploadFile;

    /**
     * show the list of devices
     * @Route("/models", name="models")
     * @param  Request $request [description]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request)
    {
        $pageNumber = $request->query->get('page', 1);
        $keyWord = $request->query->get('keyWord', null);

        $query = $this->getModelRepository()->findByKeyword($keyWord);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $pageNumber,
            10
        );

        $form = $this->getModelExcelForm(
            $this->generateUrl('ajax_model_upload_excel')
        );

        return $this->render('model/list.html.twig', array(
            'models' => $pagination,
            'keyWord' => $keyWord,
            'navbar' => 'model',
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/models/new", name="new_model")
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function newAction(Request $request)
    {
        $model = new Model();

        $manufacturers = $this->getManufacturerRepository()->findAll();

        $form = $this->getModelForm(
            $model,
            $this->generateUrl('new_model')
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $part = $form->getData();
            $this->saveModel($part);

            return $this->redirectToRoute('models');
        }

        return $this->render('model/new.html.twig', array(
            'manufacturers' => $manufacturers,
            'form' => $form->createView(),
            'navbar' => 'model'
        ));
    }

    /**
     * @Route("/models/{model}/eidt", name="edit_model")
     * @param  Model   $model
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function editAction(Model $model, Request $request)
    {
        $form = $this->getModelForm(
            $model,
            $this->generateUrl(
                'edit_model',
                ['model' => $model->getId()]
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $this->saveModel($item);

            return $this->redirectToRoute('models');
        }

        return $this->render('model/new.html.twig', array(
            'form' => $form->createView(),
            'navbar' => 'models'
        ));
    }

    /**
     * [getPartForm description]
     * @param  Model   $model [description]
     * @param  String  $path [description]
     * @return [type]       [description]
     */
    private function getModelForm(Model $model, $path)
    {
        return $this->createFormBuilder($model)
            ->setAction($path)
            ->setMethod('POST')
            ->add('manufacturer', EntityType::class, [
                'class' => Manufacturer::class,
                'choice_label' => 'name',
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => ModelTypeOptions::getModelTypeOptions(),
            ])
            ->add('model', TextType::class, ['required' => false])

            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();
    }

    private function getModelExcelForm($path)
    {
        return $this->createFormBuilder()
            ->setAction($path)
            ->setMethod('POST')
            ->getForm();
    }

    private function saveModel($model)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($model);
        $entityManager->flush();
    }

}
