<?php

namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PriceModifier;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;


#[Route('/api', name: 'api_')]
class PriceModifierController extends AbstractController
{
    #[Route('/price-modifiers', name: 'price_modifier_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $priceModifiers = $doctrine
            ->getRepository(PriceModifier::class)
            ->findAll();
   
        $data = [];
   
        foreach ($priceModifiers as $priceModifier) {
           $data[] = [
               'id' => $priceModifier->getId(),
               'name' => $priceModifier->getName(),
               'start' => $priceModifier->getStart(),
               'end' => $priceModifier->getEnd(),
               'repetition' => $priceModifier->getRepetition(),
               'modifier' => $priceModifier->getModifier(),
               'active' => $priceModifier->isActive(),
           ];
        }
        print_r($data);
        return $this->json($data);
    }
 
 
    #[Route('/price-modifiers', name: 'price_modifier_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
   
        $priceModifier = new PriceModifier();
        $priceModifier->setName($request->request->get('name'));
        $priceModifier->setStart(new DateTime($request->request->get('start')));
        $priceModifier->setEnd(new DateTime($request->request->get('end')));
        $priceModifier->setRepetition($request->request->get('repetition'));
        $priceModifier->setModifier($request->request->get('modifier'));
        $priceModifier->setActive($request->request->get('active'));
   
        $entityManager->persist($priceModifier);
        $entityManager->flush();
   
        
        $data = [
            'id' => $priceModifier->getId(),
            'name' => $priceModifier->getName(),
            'start' => $priceModifier->getStart(),
            'end' => $priceModifier->getEnd(),
            'repetition' => $priceModifier->getRepetition(),
            'modifier' => $priceModifier->getModifier(),
            'active' => $priceModifier->isActive(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/price-modifiers/{id}', name: 'price_modifier_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $priceModifier = $doctrine->getRepository(PriceModifier::class)->find($id);
   
        if (!$priceModifier) {
   
            return $this->json('No price modifier found for id ' . $id, 404);
        }
   
        $data = [
            'id' => $priceModifier->getId(),
            'name' => $priceModifier->getName(),
            'start' => $priceModifier->getStart(),
            'end' => $priceModifier->getEnd(),
            'repetition' => $priceModifier->getRepetition(),
            'modifier' => $priceModifier->getModifier(),
            'active' => $priceModifier->isActive(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/price-modifiers/{id}', name: 'price_modifier_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $priceModifier = $entityManager->getRepository(PriceModifier::class)->find($id);
   
        if (!$priceModifier) {
            return $this->json('No price modifier found for id' . $id, 404);
        }
   
        $priceModifier->setName($request->request->get('name'));
        $priceModifier->setStart($request->request->get('start'));
        $priceModifier->setEnd($request->request->get('end'));
        $priceModifier->setRepetition($request->request->get('repetition'));
        $priceModifier->setModifier($request->request->get('modifier'));
        $priceModifier->setActive($request->request->get('active'));
        $entityManager->flush();
   
        $data = [
            'id' => $priceModifier->getId(),
            'name' => $priceModifier->getName(),
            'start' => $priceModifier->getStart(),
            'end' => $priceModifier->getEnd(),
            'repetition' => $priceModifier->getRepetition(),
            'modifier' => $priceModifier->getModifier(),
            'active' => $priceModifier->isActive(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/price-modifiers/{id}', name: 'price_modifier_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $priceModifier = $entityManager->getRepository(PriceModifier::class)->find($id);
   
        if (!$priceModifier) {
            return $this->json('No price modifier found for id' . $id, 404);
        }
   
        $entityManager->remove($priceModifier);
        $entityManager->flush();
   
        return $this->json('Deleted a price modifier successfully with id ' . $id);
    }
}