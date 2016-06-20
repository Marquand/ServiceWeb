<?php

namespace PointWeb\GedBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PointWeb\GedBundle\Entity\Folder;
use PointWeb\GedBundle\Form\FolderType;

/**
 * Folder controller.
 *
 */
class FolderController extends Controller
{

    /**
     * Lists all Folder entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PointWebGedBundle:Folder')->findBy(array(), array('position'=>'ASC'));

        return $this->render('PointWebGedBundle:Folder:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Folder entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Folder();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_folder_show', array('id' => $entity->getId())));
        }

        return $this->render('PointWebGedBundle:Folder:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Folder entity.
     *
     * @param Folder $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Folder $entity)
    {
        $form = $this->createForm(new FolderType(), $entity, array(
            'action' => $this->generateUrl('admin_folder_create'),
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
     * Displays a form to create a new Folder entity.
     *
     */
    public function newAction()
    {
        $entity = new Folder();
        $form = $this->createCreateForm($entity);

        return $this->render('PointWebGedBundle:Folder:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Folder entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebGedBundle:Folder')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Folder entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebGedBundle:Folder:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Folder entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebGedBundle:Folder')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Folder entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebGedBundle:Folder:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Folder entity.
     *
     * @param Folder $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Folder $entity)
    {
        $form = $this->createForm(new FolderType(), $entity, array(
            'action' => $this->generateUrl('admin_folder_update', array('id' => $entity->getId())),
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
     * Edits an existing Folder entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Folder $entity */
        $entity = $em->getRepository('PointWebGedBundle:Folder')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Folder entity.');
        }

        $originalPictures = new ArrayCollection();
        foreach ($entity->getPictures() as $picture) {
            $originalPictures->add($picture);
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($originalPictures as $picture) {
                if ($entity->getPictures()->contains($picture) == false) {
                    $entity->getPictures()->removeElement($picture);
                    $em->remove($picture);
                }
            }
            $em->flush();

            return $this->redirect($this->generateUrl('admin_folder_show', array('id' => $id)));
        }

        return $this->render('PointWebGedBundle:Folder:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Folder entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PointWebGedBundle:Folder')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Folder entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('folder'));
    }

    /**
     * Creates a form to delete a Folder entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_folder_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                array(
                    'label' => 'Supprimer',
                    'attr' => array('class' => 'btn btn-danger')
                ))
            ->getForm();
    }

    public function sortAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $value = $request->get('value');
        /** @var Ged $entity */
        $entity = $em->getRepository('PointWebGedBundle:Folder')->find($id);
        $entity->setPosition($value);
        $em->persist($entity);
        $em->flush();
        return new JsonResponse(array('result' => 'OK'));
    }
}
