<?php

namespace PointWeb\NewsletterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PointWeb\NewsletterBundle\Entity\NewsletterMail;
use PointWeb\NewsletterBundle\Form\NewsletterMailType;

/**
 * NewsletterMail controller.
 *
 */
class NewsletterMailController extends Controller
{

    /**
     * Lists all NewsletterMail entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterMail')->findBy(array('newsletterGroup' => null));
        $entities = $em->getRepository('PointWebNewsletterBundle:NewsletterGroup')->findAll();

        return $this->render('PointWebNewsletterBundle:NewsletterMail:index.html.twig', array(
            'groups' => $entities,
            'otherMail' => $entity,
        ));
    }

    /**
     * Creates a new NewsletterMail entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new NewsletterMail();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setRegisterDate(new \DateTime("now"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_newsletter_mail_show', array('id' => $entity->getId())));
        }

        return $this->render('PointWebNewsletterBundle:NewsletterMail:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a NewsletterMail entity.
     *
     * @param NewsletterMail $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(NewsletterMail $entity)
    {
        $form = $this->createForm(new NewsletterMailType(), $entity, array(
            'action' => $this->generateUrl('admin_newsletter_mail_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new NewsletterMail entity.
     *
     */
    public function newAction()
    {
        $entity = new NewsletterMail();
        $form = $this->createCreateForm($entity);


        return $this->render('PointWebNewsletterBundle:NewsletterMail:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a NewsletterMail entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterMail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterMail entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebNewsletterBundle:NewsletterMail:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing NewsletterMail entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterMail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterMail entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebNewsletterBundle:NewsletterMail:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a NewsletterMail entity.
     *
     * @param NewsletterMail $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(NewsletterMail $entity)
    {
        $form = $this->createForm(new NewsletterMailType(), $entity, array(
            'action' => $this->generateUrl('admin_newsletter_mail_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Edits an existing NewsletterMail entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterMail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterMail entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_newsletter_mail_edit', array('id' => $id)));
        }

        return $this->render('PointWebNewsletterBundle:NewsletterMail:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a NewsletterMail entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PointWebNewsletterBundle:NewsletterMail')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NewsletterMail entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_newsletter_mail'));
    }

    /**
     * Creates a form to delete a NewsletterMail entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_newsletter_mail_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm();
    }
}
