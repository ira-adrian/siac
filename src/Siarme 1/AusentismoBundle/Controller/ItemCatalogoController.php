<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\ItemCatalogo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Itemcatalogo controller.
 *
 * @Route("catalogo")
 */
class ItemCatalogoController extends Controller
{
    /**
     * Lists all itemCatalogo entities.
     *
     * @Route("/items", name="item_catalogo_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $itemCatalogos= null;
        $cons = null;
    if (null !== $request->get('consulta')) {
        $cons = trim($request->get('consulta')); 
            $repository = $this->getDoctrine()->getRepository('AusentismoBundle:ItemCatalogo');
            $query = $repository->createQueryBuilder('i');
        if (!empty($cons)) {     
              $query->andWhere("concat(i.codigo,' ',i.rubro,' ',i.item) like :keyword")
                    ->setParameter('keyword', '%'.$cons.'%')
                    ->addOrderby('i.codigo', 'ASC');
        } else {
            $repository = $this->getDoctrine()->getRepository('AusentismoBundle:ItemPedido');
            $query = $repository->createQueryBuilder('i');
            $query->groupBy('i.codigo')
                  ->addOrderby('COUNT(i.id)', 'DESC');
        }
        $query->setMaxResults(25000);
        $itemCatalogos = $query->getQuery()->getResult();  
    }
        return $this->render('AusentismoBundle:ItemCatalogo:index.html.twig', array(
            'itemCatalogos' => $itemCatalogos,
            'find' => $cons,
        ));
    }

    /**
     * Creates a new itemCatalogo entity.
     *
     * @Route("/new", name="item_catalogo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $itemCatalogo = new Itemcatalogo();
        $form = $this->createForm('Siarme\AusentismoBundle\Form\ItemCatalogoType', $itemCatalogo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($itemCatalogo);
            $em->flush();

            return $this->redirectToRoute('item_catalogo_show', array('id' => $itemCatalogo->getId()));
        }

        return $this->render('AusentismoBundle:ItemCatalogo:new.html.twig', array(
            'itemCatalogo' => $itemCatalogo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a itemCatalogo entity.
     *
     * @Route("/{id}", name="item_catalogo_show")
     * @Method("GET")
     */
    public function showAction(ItemCatalogo $itemCatalogo)
    {
        $deleteForm = $this->createDeleteForm($itemCatalogo);

        return $this->render('AusentismoBundle:ItemCatalogo:show.html.twig', array(
            'itemCatalogo' => $itemCatalogo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing itemCatalogo entity.
     *
     * @Route("/{id}/edit", name="item_catalogo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ItemCatalogo $itemCatalogo)
    {
        $deleteForm = $this->createDeleteForm($itemCatalogo);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\ItemCatalogoType', $itemCatalogo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_catalogo_edit', array('id' => $itemCatalogo->getId()));
        }

        return $this->render('AusentismoBundle:ItemCatalogo:edit.html.twig', array(
            'itemCatalogo' => $itemCatalogo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a itemCatalogo entity.
     *
     * @Route("/{id}", name="item_catalogo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ItemCatalogo $itemCatalogo)
    {
        $form = $this->createDeleteForm($itemCatalogo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemCatalogo);
            $em->flush();
        }

        return $this->redirectToRoute('item_catalogo_index');
    }

    /**
     * Creates a form to delete a itemCatalogo entity.
     *
     * @param ItemCatalogo $itemCatalogo The itemCatalogo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ItemCatalogo $itemCatalogo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('item_catalogo_delete', array('id' => $itemCatalogo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
