<?php

namespace PointWeb\PdfBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PointWeb\PdfBundle\Entity\Pdf;
use PointWeb\PdfBundle\Form\PdfType;

/**
 * Pdf controller.
 *
 */
class PdfController extends Controller
{

    /**
     * Lists all Pdf entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PointWebPdfBundle:Pdf')->findAll();

        return $this->render('PointWebPdfBundle:Pdf:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Pdf entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Pdf();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_pdf'));
        }

        return $this->render('PointWebPdfBundle:Pdf:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Pdf entity.
     *
     * @param Pdf $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pdf $entity)
    {
        $form = $this->createForm(new PdfType(), $entity, array(
            'action' => $this->generateUrl('admin_pdf_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Pdf entity.
     *
     */
    public function newAction()
    {
        $entity = new Pdf();
        $form   = $this->createCreateForm($entity);

        return $this->render('PointWebPdfBundle:Pdf:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Pdf entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebPdfBundle:Pdf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pdf entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebPdfBundle:Pdf:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Pdf entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PointWebPdfBundle:Pdf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pdf entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PointWebPdfBundle:Pdf:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Pdf entity.
    *
    * @param Pdf $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Pdf $entity)
    {
        $form = $this->createForm(new PdfType(), $entity, array(
            'action' => $this->generateUrl('admin_pdf_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Pdf entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Pdf $entity */
        $entity = $em->getRepository('PointWebPdfBundle:Pdf')->find($id);
        $lastPdf = $entity->getPdfFile();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pdf entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($editForm->get('file')->getData() instanceof UploadedFile) {
                unlink($entity->directoryPath()."/".$lastPdf);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('admin_pdf_edit', array('id' => $id)));
        }

        return $this->render('PointWebPdfBundle:Pdf:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Pdf entity.
     *
     */
    public function deleteAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            /** @var Pdf $entity */
            $entity = $em->getRepository('PointWebPdfBundle:Pdf')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pdf entity.');
            }

            unlink($entity->directoryPath()."/".$entity->getPdfFile());
            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('admin_pdf'));
    }

    /**
     * Creates a form to delete a Pdf entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_pdf_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
