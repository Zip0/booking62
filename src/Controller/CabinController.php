<?php

namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Cabin;
 
#[Route('/api', name: 'api_')]
class CabinController extends AbstractController
{
    #[Route('/cabins', name: 'cabin_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $cabins = $doctrine
            ->getRepository(Cabin::class)
            ->findAll();
   
        $data = [];
   
        foreach ($cabins as $cabin) {
           $data[] = [
               'id' => $cabin->getId(),
               'name' => $cabin->getName(),
               'description' => $cabin->getDescription(),
               'price_multiplier' => $cabin->getPriceMultiplier(),
               'custom_price' => $cabin->getCustomPrice(),
               'active' => $cabin->isActive(),
               'coordinates' => $cabin->getCoordinates(),
               'miniature' => $cabin->getMiniature(),
           ];
        }
   
        return $this->json($data);
    }
 
 
    #[Route('/cabins', name: 'cabin_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
   
        $cabin = new Cabin();
        $cabin->setName($request->request->get('name'));
        $cabin->setDescription($request->request->get('description'));
        $cabin->setPriceMultiplier($request->request->get('price_multiplier'));
        $cabin->setCustomPrice($request->request->get('custom_price'));
        $cabin->setActive($request->request->get('active'));
        $cabin->setCoordinates($request->request->get('coordinates'));
        $cabin->setMiniature($request->request->get('miniature'));
   
        $entityManager->persist($cabin);
        $entityManager->flush();
   
        
        $data = [
            'id' => $cabin->getId(),
            'name' => $cabin->getName(),
            'description' => $cabin->getDescription(),
            'price_multiplier' => $cabin->getPriceMultiplier(),
            'custom_price' => $cabin->getCustomPrice(),
            'active' => $cabin->isActive(),
            'coordinates' => $cabin->getCoordinates(),
            'miniature' => $cabin->getMiniature(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/cabins/{id}', name: 'cabin_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $cabin = $doctrine->getRepository(Cabin::class)->find($id);
   
        if (!$cabin) {
   
            return $this->json('No cabin found for id ' . $id, 404);
        }
   
        $data = [
            'id' => $cabin->getId(),
            'name' => $cabin->getName(),
            'description' => $cabin->getDescription(),
            'price_multiplier' => $cabin->getPriceMultiplier(),
            'custom_price' => $cabin->getCustomPrice(),
            'active' => $cabin->isActive(),
            'coordinates' => $cabin->getCoordinates(),
            'miniature' => $cabin->getMiniature(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/cabins/{id}', name: 'cabin_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $cabin = $entityManager->getRepository(Cabin::class)->find($id);
   
        if (!$cabin) {
            return $this->json('No cabin found for id' . $id, 404);
        }
   
        $cabin->setName($request->request->get('name'));
        $cabin->setSurname($request->request->get('surname'));
        $cabin->setEmail($request->request->get('email'));
        $cabin->setPhone($request->request->get('phone'));
        $cabin->setActive($request->request->get('active'));
        $entityManager->flush();
   
        $data = [
            'id' => $cabin->getId(),
            'name' => $cabin->getName(),
            'description' => $cabin->getDescription(),
            'price_multiplier' => $cabin->getPriceMultiplier(),
            'custom_price' => $cabin->getCustomPrice(),
            'active' => $cabin->isActive(),
            'coordinates' => $cabin->getCoordinates(),
            'miniature' => $cabin->getMiniature(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/cabins/{id}', name: 'cabin_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $cabin = $entityManager->getRepository(Cabin::class)->find($id);
   
        if (!$cabin) {
            return $this->json('No cabin found for id' . $id, 404);
        }
   
        $entityManager->remove($cabin);
        $entityManager->flush();
   
        return $this->json('Deleted a cabin successfully with id ' . $id);
    }
}