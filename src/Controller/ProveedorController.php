<?php

namespace App\Controller;

use App\Entity\Proveedor;
use App\Form\ProveedorType;
use App\Repository\ProveedorRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProveedorController extends AbstractController
{
    /**
     * @Route("/proveedores", name="proveedores_index")
     */
    public function index(ProveedorRepository $proveedorRepository): Response
    {
        return $this->render('proveedor/index.html.twig', [
            'listaProveedores' => $proveedorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/proveedores/{id}", name="proveedores_details")
     */
    public function findById($id, Request $request, ProveedorRepository $proveedorRepository): Response
    {
        $proveedor = $proveedorRepository->find($id);
        $form = $this->createForm(ProveedorType::class,$proveedor);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $proveedor = $form->getData();
            $proveedor->setUpdatedAt(new DateTime());
            $em->persist($proveedor);
            $em->flush();
            return $this->redirectToRoute("proveedores_index");
        }

        return $this->render('proveedor/details.html.twig', array(
            "proveedor"=>$proveedor,
            "form"=>$form->createView()
        ));
    }

    /**
     * @Route("/proveedor/add", name="proveedores_add")
     */
    public function addProveedor(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $proveedor = new Proveedor();
        $form = $this->createForm(ProveedorType::class,$proveedor);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $proveedor = $form->getData();
            $em->persist($proveedor);
            $em->flush();
            $this->addFlash("success","Proveedor añadido!");
            return $this->redirectToRoute("proveedores_index");
        }

        return $this->render('proveedor/add.html.twig', array(
            "form"=>$form->createView()
        ));
    }

    /**
     * @Route("/proveedores/delete/{id}", name="proveedores_delete")
     */
    public function deleteProveedor($id, ProveedorRepository $proveedorRepository): Response
    {
        $proveedorRepository->remove($proveedorRepository->find($id));
        $this->addFlash("success","Proveedor eliminado!");
        return $this->redirectToRoute("proveedores_index");
    }
}
