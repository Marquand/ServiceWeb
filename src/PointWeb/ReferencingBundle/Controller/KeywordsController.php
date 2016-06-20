<?php

namespace PointWeb\ReferencingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PointWeb\ReferencingBundle\Entity\Keywords;
use PointWeb\ReferencingBundle\Form\KeywordsType;

/**
 * Keywords controller.
 *
 */
class KeywordsController extends Controller
{

    /**
     * Lists all Keywords entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PointWebReferencingBundle:Keywords')->findAll();

        return $this->render('PointWebReferencingBundle:Keywords:index.html.twig', array(
            'key' => $entities,
        ));
    }
    /**
     * Creates a new Keywords entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Keywords();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_keywords'));
        }

        return $this->render('PointWebReferencingBundle:Keywords:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Keywords entity.
     *
     * @param Keywords $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Keywords $entity)
    {
        $form = $this->createForm(new KeywordsType(), $entity, array(
            'action' => $this->generateUrl('admin_keywords_create'),
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
     * Displays a form to create a new Keywords entity.
     *
     */
    public function newAction()
    {
        $entity = new Keywords();
        $form   = $this->createCreateForm($entity);

        return $this->render('PointWebReferencingBundle:Keywords:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Keywords entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebReferencingBundle:Keywords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Keywords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebReferencingBundle:Keywords:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Keywords entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebReferencingBundle:Keywords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Keywords entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebReferencingBundle:Keywords:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Keywords entity.
    *
    * @param Keywords $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Keywords $entity)
    {
        $form = $this->createForm(new KeywordsType(), $entity, array(
            'action' => $this->generateUrl('admin_keywords_update', array('id' => $entity->getId())),
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
     * Edits an existing Keywords entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebReferencingBundle:Keywords')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Keywords entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_keywords_edit', array('id' => $id)));
        }

        return $this->render('PointWebReferencingBundle:Keywords:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Keywords entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PointWebReferencingBundle:Keywords')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Keywords entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_keywords'));
    }

    /**
     * Creates a form to delete a Keywords entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_keywords_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
