<?php

namespace App\Controller;

use App\Entity\Proveedor;
use App\Form\ProveedorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProveedorController extends AbstractController
{
    /**
     * @Route("/", name="app_proveedor")
     */
    public function index(): Response
    {
        return $this->render('proveedor/index.html.twig', [
            'controller_name' => 'ProveedorController',
        ]);
    }

    /**
     * @Route("/proveedores", name="findAll")
     */
    public function findAll(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $listaProveedores = $em->getRepository(Proveedor::class)->findAll();
        return $this->render('proveedor/ProveedorView.html.twig', array(
            "listaProveedores"=>$listaProveedores
        ));
    }

    /**
     * @Route("/proveedores/{id}", name="details")
     */
    public function findById($id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $proveedor = $em->getRepository(Proveedor::class)->find($id);
        $form = $this->createForm(ProveedorType::class,$proveedor);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $proveedor = $form->getData();
            $em->persist($proveedor);
            $em->flush();
            return $this->render('proveedor/success.html.twig');
        }

        return $this->render('proveedor/details.html.twig', array(
            "proveedor"=>$proveedor,
            "form"=>$form->createView()
        ));
    }

    /**
     * @Route("/proveedor/delete/{id}", name="delete")
     */
    public function deleteProveedor($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $proveedor = $em->getRepository(Proveedor::class)->find($id);
        $em->remove($proveedor);
        $em->flush();
        return $this->render('proveedor/success.html.twig');
    }

    /**
     * @Route("/proveedor/add", name="add")
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
            return $this->render('proveedor/success.html.twig');
        }

        return $this->render('proveedor/add.html.twig', array(
            "form"=>$form->createView()
        ));
    }
}
