<?php

namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Customer;

// class CustomerController extends AbstractController
// {
//     #[Route('/customer', name: 'app_customer')]
//     public function index(): JsonResponse
//     {
//         return $this->json([
//             'message' => 'Welcome to your new controller!',
//             'path' => 'src/Controller/CustomerController.php',
//         ]);
//     }
// }
 
#[Route('/api', name: 'api_')]
class CustomerController extends AbstractController
{
    #[Route('/customers', name: 'customer_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $customers = $doctrine
            ->getRepository(Customer::class)
            ->findAll();
   
        $data = [];
   
        foreach ($customers as $customer) {
           $data[] = [
               'id' => $customer->getId(),
               'name' => $customer->getName(),
               'surname' => $customer->getSurname(),
               'email' => $customer->getEmail(),
               'phone' => $customer->getPhone(),
               'active' => $customer->isActive(),
           ];
        }
   
        return $this->json($data);
    }
 
 
    #[Route('/customers', name: 'customer_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
   
        $customer = new Customer();
        $customer->setName($request->request->get('name'));
        $customer->setSurname($request->request->get('surname'));
        $customer->setEmail($request->request->get('email'));
        $customer->setPhone($request->request->get('phone'));
        $customer->setActive($request->request->get('active'));
   
        $entityManager->persist($customer);
        $entityManager->flush();
   
        
        $data = [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'surname' => $customer->getSurname(),
            'email' => $customer->getEmail(),
            'phone' => $customer->getPhone(),
            'active' => $customer->isActive(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/customers/{id}', name: 'customer_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $customer = $doctrine->getRepository(Customer::class)->find($id);
   
        if (!$customer) {
   
            return $this->json('No customer found for id ' . $id, 404);
        }
   
        $data = [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'surname' => $customer->getSurname(),
            'email' => $customer->getEmail(),
            'phone' => $customer->getPhone(),
            'active' => $customer->isActive(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/customers/{id}', name: 'customer_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);
   
        if (!$customer) {
            return $this->json('No customer found for id' . $id, 404);
        }
   
        $customer->setName($request->request->get('name'));
        $customer->setSurname($request->request->get('surname'));
        $customer->setEmail($request->request->get('email'));
        $customer->setPhone($request->request->get('phone'));
        $customer->setActive($request->request->get('active'));
        $entityManager->flush();
   
        $data = [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'surname' => $customer->getSurname(),
            'email' => $customer->getEmail(),
            'phone' => $customer->getPhone(),
            'active' => $customer->isActive(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/customers/{id}', name: 'customer_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);
   
        if (!$customer) {
            return $this->json('No customer found for id' . $id, 404);
        }
   
        $entityManager->remove($customer);
        $entityManager->flush();
   
        return $this->json('Deleted a customer successfully with id ' . $id);
    }
}