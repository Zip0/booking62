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
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

#[Route('/api', name: 'api_')]
class BookingController extends AbstractController
{
    #[Route('/bookings', name: 'booking_index', methods: ['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {



        $bookings = $doctrine
            ->getRepository(Booking::class)
            ->findAll();

        $data = [];

        foreach ($bookings as $booking) {
            $data[] = [
                'booking_id' => $booking->getId(),
                'customer_name' => $booking->getCustomerId()->getName(),
                'customer_surname' => $booking->getCustomerId()->getSurname(),
                'cabin_name' => $booking->getCabinId()->getName(),
                'start' => $booking->getStart(),
                'end' => $booking->getEnd(),
                'notes' => $booking->getNotes(),
                'total' => $booking->getTotal(),
            ];
        }

        return $this->json($data);
    }


    #[Route('/bookings', name: 'booking_create', methods: ['post'])]
    public function create(BookingRepository $bookingRepository, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $cabin = $entityManager->getRepository(Cabin::class)->find($request->request->get('cabin_id'));
        $customer = $entityManager->getRepository(Customer::class)->find($request->request->get('customer_id'));
        $start = new DateTime($request->request->get('start'));
        $end = new DateTime($request->request->get('end'));
        $now = new DateTime(date("Y-m-d"));

        if ($start < $now) {
            return $this->json('Start date cannot be in the past', 404);
        }
        if ($end < $now) {
            return $this->json('End date cannot be in the past', 404);
        }
        if ($end < $start) {
            return $this->json('End date cannot before start date', 404);
        }
        if (!$cabin) {
            return $this->json('No cabin found for id' . $request->request->get('cabin_id'), 404);
        }
        if (!$customer) {
            return $this->json('No customer found for id' . $request->request->get('customer_id'), 404);
        }

        $bookings = $bookingRepository->findCollidingDates($cabin, $start, $end);

        if (!$bookings) {

            $booking = new Booking();
            $booking->setCustomerId($customer);
            $booking->setCabinId($cabin);
            $booking->setStart($start);
            $booking->setEnd($end);
            $booking->setNotes($request->request->get('notes'));
            $booking->setTotal($request->request->get('total'));

            $entityManager->persist($booking);
            $entityManager->flush();


            $data = [
                'customer_name' => $booking->getCustomerId()->getName(),
                'customer_surname' => $booking->getCustomerId()->getSurname(),
                'cabin_name' => $booking->getCabinId()->getName(),
                'start' => $booking->getStart(),
                'end' => $booking->getEnd(),
                'notes' => $booking->getNotes(),
                'total' => $booking->getTotal(),
            ];

            return $this->json($data);
        }

        return $this->json('There are colliding bookings.', 404);
    }


    #[Route('/bookings/{id}', name: 'booking_show', methods: ['get'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {

        $entityManager = $doctrine->getManager();
        $booking = $doctrine->getRepository(Booking::class)->find($id);

        if (!$booking) {

            return $this->json('No booking found for id ' . $id, 404);
        }

        $data = [
            'customer_name' => $booking->getCustomerId()->getName(),
            'customer_surname' => $booking->getCustomerId()->getSurname(),
            'cabin_name' => $booking->getCabinId()->getName(),
            'start' => $booking->getStart(),
            'end' => $booking->getEnd(),
            'notes' => $booking->getNotes(),
            'total' => $booking->getTotal(),
        ];

        return $this->json($data);
    }

    #[Route('/bookings/{id}', name: 'booking_update', methods: ['put', 'patch'])]
    public function update(BookingRepository $bookingRepository, ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $booking = $entityManager->getRepository(Booking::class)->find($id);
        $cabin = $entityManager->getRepository(Cabin::class)->find($request->request->get('cabin_id'));
        $customer = $entityManager->getRepository(Customer::class)->find($request->request->get('customer_id'));
        $start = new DateTime($request->request->get('start'));
        $end = new DateTime($request->request->get('end'));
        $now = new DateTime(date("Y-m-d"));

        if ($start < $now) {
            return $this->json('Start date cannot be in the past', 404);
        }
        if ($end < $now) {
            return $this->json('End date cannot be in the past', 404);
        }
        if ($end < $start) {
            return $this->json('End date cannot before start date', 404);
        }
        if (!$cabin) {
            return $this->json('No cabin found for id' . $request->request->get('cabin_id'), 404);
        }
        if (!$customer) {
            return $this->json('No customer found for id' . $request->request->get('customer_id'), 404);
        }

        $bookings = $bookingRepository->findCollidingDates($cabin, $start, $end);

        if (!$bookings) {

            $booking->setCustomerId($customer);
            $booking->setCabinId($cabin);
            $booking->setStart($start);
            $booking->setEnd($end);
            $booking->setNotes($request->request->get('notes'));
            $booking->setTotal($request->request->get('total'));

            $entityManager->flush();

            $data = [
                'customer_name' => $booking->getCustomerId()->getName(),
                'customer_surname' => $booking->getCustomerId()->getSurname(),
                'cabin_name' => $booking->getCabinId()->getName(),
                'start' => $booking->getStart(),
                'end' => $booking->getEnd(),
                'notes' => $booking->getNotes(),
                'total' => $booking->getTotal(),
            ];

            return $this->json($data);
        }

        return $this->json('There are colliding bookings.', 404);
    }

    #[Route('/bookings/{id}', name: 'booking_delete', methods: ['delete'])]
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
