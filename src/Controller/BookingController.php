<?php

namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Booking;
use App\Entity\Cabin;
use App\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
 
#[Route('/api', name: 'api_')]
class BookingController extends AbstractController
{
    #[Route('/bookings', name: 'booking_index', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $bookings = $doctrine
            ->getRepository(Booking::class)
            ->findAll();
   
        $data = [];
   
        foreach ($bookings as $booking) {
           $data[] = [
               'customer_id' => $booking->getCustomerId(),
               'cabin_id' => $booking->getCabinId(),
               'start' => $booking->getStart(),
               'end' => $booking->getEnd(),
               'notes' => $booking->getNotes(),
               'total' => $booking->getTotal(),
           ];
        }
   
        return $this->json($data);
    }
 
 
    #[Route('/bookings', name: 'booking_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
   
        $booking = new Booking();
        $booking->setCustomerId($request->request->get('customer_id'));
        $booking->setCabinId($request->request->get('cabin_id'));
        $booking->setStart(new DateTime($request->request->get('start')));
        $booking->setEnd(new DateTime($request->request->get('end')));
        $booking->setNotes($request->request->get('notes'));
        $booking->setTotal($request->request->get('total'));
        
        $entityManager->persist($booking);
        $entityManager->flush();
   
        
        $data = [
            'customer_id' => $booking->getCustomerId(),
            'cabin_id' => $booking->getCabinId(),
            'start' => $booking->getStart(),
            'end' => $booking->getEnd(),
            'notes' => $booking->getNotes(),
            'total' => $booking->getTotal(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/bookings/{id}', name: 'booking_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $booking = $doctrine->getRepository(Booking::class)->find($id);
   
        if (!$booking) {
   
            return $this->json('No booking found for id ' . $id, 404);
        }
   
        $data = [
            'customer_id' => $booking->getCustomerId(),
            'cabin_id' => $booking->getCabinId(),
            'start' => $booking->getStart(),
            'end' => $booking->getEnd(),
            'notes' => $booking->getNotes(),
            'total' => $booking->getTotal(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/bookings/{id}', name: 'booking_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $booking = $entityManager->getRepository(Booking::class)->find($id);
   
        if (!$booking) {
            return $this->json('No booking found for id' . $id, 404);
        }
   
        $booking->setCustomerId($request->request->get('customer_id'));
        $booking->setCabinId($request->request->get('cabin_id'));
        $booking->setStart(new DateTime($request->request->get('start')));
        $booking->setEnd(new DateTime($request->request->get('end')));
        $booking->setNotes($request->request->get('notes'));
        $booking->setTotal($request->request->get('total'));
        
        $entityManager->flush();
   
        $data = [
            'customer_id' => $booking->getCustomerId(),
            'cabin_id' => $booking->getCabinId(),
            'start' => $booking->getStart(),
            'end' => $booking->getEnd(),
            'notes' => $booking->getNotes(),
            'total' => $booking->getTotal(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/bookings/{id}', name: 'booking_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $booking = $entityManager->getRepository(Booking::class)->find($id);
   
        if (!$booking) {
            return $this->json('No booking found for id' . $id, 404);
        }
   
        $entityManager->remove($booking);
        $entityManager->flush();
   
        return $this->json('Deleted a booking successfully with id ' . $id);
    }
}