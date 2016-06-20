<?php

namespace PointWeb\NewsletterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PointWeb\NewsletterBundle\Entity\Newsletter;
use PointWeb\NewsletterBundle\Form\NewsletterType;

/**
 * Newsletter controller.
 *
 */
class NewsletterController extends Controller
{

    /**
     * Lists all Newsletter entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PointWebNewsletterBundle:Newsletter')->findAll();

        return $this->render('PointWebNewsletterBundle:Newsletter:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Newsletter entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Newsletter();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $group = $em->getRepository('PointWebNewsletterBundle:NewsletterGroup')->findOneBy(array('id' => ($entity->getNewsletterGroup()) ? $entity->getNewsletterGroup()->getId() : null));
            if ($group) {
                $mails = $group->getNewsletterMail();
            } else {
                $mails = $em->getRepository('PointWebNewsletterBundle:NewsletterMail')->findAll();
            }

            $entity->setSendDate(new \DateTime('now'));
            $em->persist($entity);
            $em->flush();


            if ($form->get('send')->isClicked()) {

                $recipient = array();
                foreach ($mails as $mail) {
                    array_push($recipient, $mail->getEmail());
                }
                $message = \Swift_Message::newInstance()
                    ->setSubject($entity->getObject())
                    ->setFrom($entity->getAddresser())
                    ->setTo($recipient)
                    ->setBody($this->renderView('PointWebNewsletterBundle:Mail:contact.txt.twig', array(
                        'entity' => $entity
                    )));
                $this->get('mailer')->send($message);

            }

            return $this->redirect($this->generateUrl('admin_newsletter_show', array('id' => $entity->getId())));
        }

        return $this->render('PointWebNewsletterBundle:Newsletter:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Newsletter entity.
     *
     * @param Newsletter $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Newsletter $entity)
    {
        $form = $this->createForm(new NewsletterType(), $entity, array(
            'action' => $this->generateUrl('admin_newsletter_create'),
            'method' => 'POST',
        ));

        $form
            ->add('send', 'submit', array('label' => 'Enregistrer / Envoyer', 'attr' => array('class' => 'btn btn-success')))
            ->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success')));


        return $form;
    }

    /**
     * Displays a form to create a new Newsletter entity.
     *
     */
    public function newAction()
    {
        $entity = new Newsletter();
        $form = $this->createCreateForm($entity);

        return $this->render('PointWebNewsletterBundle:Newsletter:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Newsletter entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebNewsletterBundle:Newsletter:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Newsletter entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebNewsletterBundle:Newsletter:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Newsletter entity.
     *
     * @param Newsletter $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Newsletter $entity)
    {
        $form = $this->createForm(new NewsletterType(), $entity, array(
            'action' => $this->generateUrl('admin_newsletter_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form
            ->add('update', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success')))
            ->add('send', 'submit', array('label' => 'Enregistrer/Envoyer', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Edits an existing Newsletter entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebNewsletterBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $group = $em->getRepository('PointWebNewsletterBundle:NewsletterGroup')->findOneBy(array('id' => ($entity->getNewsletterGroup()) ? $entity->getNewsletterGroup()->getId() : null));
            if ($group) {
                $mails = $group->getNewsletterMail();
            } else {
                $mails = $em->getRepository('PointWebNewsletterBundle:NewsletterMail')->findAll();
            }
            $entity->setSendDate(new \DateTime('now'));

            $em->flush();

            if ($editForm->get('send')->isClicked()) {

                $recipient = array();
                foreach ($mails as $mail) {
                    array_push($recipient, $mail->getEmail());
                }

                $message = \Swift_Message::newInstance()
                    ->setSubject($entity->getObject())
                    ->setFrom($entity->getAddresser())
                    ->setTo($recipient)
                    ->setBody($this->renderView('PointWebNewsletterBundle:Mail:contact.txt.twig', array(
                        'entity' => $entity
                    )));
                $this->get('mailer')->send($message);

            }

            return $this->redirect($this->generateUrl('admin_newsletter_edit', array('id' => $id)));
        }

        return $this->render('PointWebNewsletterBundle:Newsletter:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Newsletter entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PointWebNewsletterBundle:Newsletter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Newsletter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_newsletter'));
    }

    /**
     * Creates a form to delete a Newsletter entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_newsletter_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm();
    }
}
