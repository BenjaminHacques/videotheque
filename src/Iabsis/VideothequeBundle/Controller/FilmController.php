<?php

namespace Iabsis\VideothequeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Iabsis\VideothequeBundle\Entity\Film;
use Iabsis\VideothequeBundle\Form\FilmType;

/**
 * Film controller.
 *
 * @Route("/film")
 */
class FilmController extends Controller
{
    const SECTION = "film";

    /**
     * Lists all Film entities.
     *
     * @Route("/", name="film")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IabsisVideothequeBundle:Film')->findAll();

        return array(
            'entities' => $entities,
            'section' => self::SECTION
        );
    }
    /**
     * Creates a new Film entity.
     *
     * @Route("/", name="film_create")
     * @Method("POST")
     * @Template("IabsisVideothequeBundle:Film:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Film();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('film_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'section' => self::SECTION
        );
    }

    /**
     * Creates a form to create a Film entity.
     *
     * @param Film $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Film $entity)
    {
        $form = $this->createForm(new FilmType(), $entity, array(
            'action' => $this->generateUrl('film_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Film entity.
     *
     * @Route("/new", name="film_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Film();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'section' => self::SECTION
        );
    }

    /**
     * Finds and displays a Film entity.
     *
     * @Route("/{id}", name="film_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IabsisVideothequeBundle:Film')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Film entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'section' => self::SECTION
        );
    }

    /**
     * Displays a form to edit an existing Film entity.
     *
     * @Route("/{id}/edit", name="film_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IabsisVideothequeBundle:Film')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Film entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'section' => self::SECTION
        );
    }

    /**
    * Creates a form to edit a Film entity.
    *
    * @param Film $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Film $entity)
    {
        $form = $this->createForm(new FilmType(), $entity, array(
            'action' => $this->generateUrl('film_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Film entity.
     *
     * @Route("/{id}", name="film_update")
     * @Method("PUT")
     * @Template("IabsisVideothequeBundle:Film:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IabsisVideothequeBundle:Film')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Film entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('film_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'section' => self::SECTION
        );
    }
    /**
     * Deletes a Film entity.
     *
     * @Route("/{id}", name="film_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IabsisVideothequeBundle:Film')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Film entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('film'));
    }

    /**
     * Creates a form to delete a Film entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('film_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
