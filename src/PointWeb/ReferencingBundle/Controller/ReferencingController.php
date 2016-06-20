<?php

namespace PointWeb\ReferencingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PointWeb\ReferencingBundle\Entity\Referencing;
use PointWeb\ReferencingBundle\Form\ReferencingType;

/**
 * Referencing controller.
 *
 */
class ReferencingController extends Controller
{

    /**
     * Lists all Referencing entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PointWebReferencingBundle:Referencing')->findAll();

        return $this->render('PointWebReferencingBundle:Referencing:index.html.twig', array(
            'referencing' => $entities
        ));
    }
    /**
     * Creates a new Referencing entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Referencing();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_referencing'));
        }

        return $this->render('PointWebReferencingBundle:Referencing:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Referencing entity.
     *
     * @param Referencing $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Referencing $entity)
    {
        $form = $this->createForm(new ReferencingType(), $entity, array(
            'action' => $this->generateUrl('admin_referencing_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit',
            array(
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-success')
            ));

        return $form;
    }

    /**
     * Displays a form to create a new Referencing entity.
     *
     */
    public function newAction()
    {
        $entity = new Referencing();
        $form   = $this->createCreateForm($entity);

        return $this->render('PointWebReferencingBundle:Referencing:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Referencing entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebReferencingBundle:Referencing')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Referencing entity.');
        }
        return $this->render('PointWebReferencingBundle:Referencing:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Referencing entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebReferencingBundle:Referencing')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Referencing entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('PointWebReferencingBundle:Referencing:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Referencing entity.
    *
    * @param Referencing $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Referencing $entity)
    {
        $form = $this->createForm(new ReferencingType(), $entity, array(
            'action' => $this->generateUrl('admin_referencing_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit',
            array(
                'label' => 'Enregistrer',
                'attr' => array('class' => 'btn btn-success')
            ));

        return $form;
    }
    /**
     * Edits an existing Referencing entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebReferencingBundle:Referencing')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Referencing entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_referencing_edit', array('id' => $id)));
        }

        return $this->render('PointWebReferencingBundle:Referencing:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Referencing entity.
     *
     */
    public function deleteAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PointWebReferencingBundle:Referencing')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Referencing entity.');
            }

            $entity->setOnline(false);
            $em->persist($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_referencing'));
    }
}
