<?php

namespace PointWeb\NewsletterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PointWeb\NewsletterBundle\Entity\NewsletterGroup;
use PointWeb\NewsletterBundle\Form\NewsletterGroupType;

/**
 * NewsletterGroup controller.
 *
 */
class NewsletterGroupController extends Controller
{

    /**
     * Lists all NewsletterGroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PointWebNewsletterBundle:NewsletterGroup')->findAll();

        return $this->render('PointWebNewsletterBundle:NewsletterGroup:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new NewsletterGroup entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new NewsletterGroup();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setCreateDate(new \DateTime("now"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_newsletter_group_show', array('id' => $entity->getId())));
        }

        return $this->render('PointWebNewsletterBundle:NewsletterGroup:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a NewsletterGroup entity.
     *
     * @param NewsletterGroup $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(NewsletterGroup $entity)
    {
        $form = $this->createForm(new NewsletterGroupType(), $entity, array(
            'action' => $this->generateUrl('admin_newsletter_group_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new NewsletterGroup entity.
     *
     */
    public function newAction()
    {
        $entity = new NewsletterGroup();
        $form = $this->createCreateForm($entity);

        return $this->render('PointWebNewsletterBundle:NewsletterGroup:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a NewsletterGroup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebNewsletterBundle:NewsletterGroup:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing NewsletterGroup entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterGroup entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebNewsletterBundle:NewsletterGroup:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a NewsletterGroup entity.
     *
     * @param NewsletterGroup $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(NewsletterGroup $entity)
    {
        $form = $this->createForm(new NewsletterGroupType(), $entity, array(
            'action' => $this->generateUrl('admin_newsletter_group_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Edits an existing NewsletterGroup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_newsletter_group_edit', array('id' => $id)));
        }

        return $this->render('PointWebNewsletterBundle:NewsletterGroup:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a NewsletterGroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NewsletterGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_newsletter_group'));
    }

    /**
     * Creates a form to delete a NewsletterGroup entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_newsletter_group_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm();
    }
}
