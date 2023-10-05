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
use App\Entity\PriceModifier;
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use DateInterval;
use DatePeriod;

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
        $cabin_id = $request->request->get('cabin_id');
        $cabin = $entityManager->getRepository(Cabin::class)->find($cabin_id);
        $customer_id = $request->request->get('customer_id');
        $customer = $entityManager->getRepository(Customer::class)->find($customer_id);
        $start = new DateTime($request->request->get('start'));
        $end = new DateTime($request->request->get('end'));
        $now = new DateTime(date("Y-m-d"));
        $total = (int)0;

        $validationMessage = $this->validateBooking($start, $end, $now, $cabin, $customer, $cabin_id, $customer_id);
        if ($validationMessage != null) return $validationMessage;

        $bookings = $bookingRepository->findCollidingDates($cabin, $start, $end);

        if (!$request->request->get('total')) {
            $total = $this->calculateTotal($doctrine, $request->request->get('start'), $request->request->get('end'), $cabin->getCustomPrice());
        } else {
            $total = $request->request->get('total');
        }


        if (!$bookings) {

            $booking = new Booking();
            $booking->setCustomerId($customer);
            $booking->setCabinId($cabin);
            $booking->setStart($start);
            $booking->setEnd($end);
            $booking->setNotes($request->request->get('notes'));
            $booking->setTotal($total);

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
        $cabin_id = $request->request->get('cabin_id');
        $cabin = $entityManager->getRepository(Cabin::class)->find($cabin_id);
        $customer_id = $request->request->get('customer_id');
        $customer = $entityManager->getRepository(Customer::class)->find($customer_id);
        $start = new DateTime($request->request->get('start'));
        $end = new DateTime($request->request->get('end'));
        $now = new DateTime(date("Y-m-d"));
        $total = (int)0;

        $validationMessage = $this->validateBooking($start, $end, $now, $cabin, $customer, $cabin_id, $customer_id);
        if ($validationMessage != null) return $validationMessage;

        $bookings = $bookingRepository->findCollidingDates($cabin, $start, $end);

        if (!$request->request->get('total')) {
            $total = $this->calculateTotal($doctrine, $request->request->get('start'), $request->request->get('end'), $cabin->getCustomPrice());
        } else {
            $total = $request->request->get('total');
        }
        if (!$bookings) {

            $booking->setCustomerId($customer);
            $booking->setCabinId($cabin);
            $booking->setStart($start);
            $booking->setEnd($end);
            $booking->setNotes($request->request->get('notes'));
            $booking->setTotal($total);

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

    public function validateBooking($start, $end, $now, $cabin, $customer, $cabin_id, $customer_id)
    {
        if ($start < $now) {
            return $this->json('Start date cannot be in the past', 404);
        }
        if ($end < $now) {
            return $this->json('End date cannot be in the past', 404);
        }
        if ($end == $start) {
            return $this->json('Start and end dates cannot be the same', 404);
        }
        if ($end < $start) {
            return $this->json('End date cannot be before start date', 404);
        }
        if ($start > $end) {
            return $this->json('Start date cannot be after end date', 404);
        }
        if (!$cabin) {
            return $this->json('No cabin found for id ' . $cabin_id, 404);
        }
        if (!$customer) {
            return $this->json('No customer found for id ' . $customer_id, 404);
        }
        if ($customer->isActive() != 1) {
            return $this->json('This customer is inactive ' . $customer_id, 404);
        }
        if ($cabin->isActive() != 1) {
            return $this->json('This cabin is inactive ' . $cabin_id, 404);
        }
    }

    private function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {

        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach ($period as $date) {
            $array[] = $date->format($format);
        }

        return $array;
    }


    //TODO have a look at this function when not tired

    private function calculateTotal($doctrine, $start, $end, $price)
    {
        $total = 0;
        $entityManager = $doctrine->getManager();

        $bookingDates = $this->getDatesFromRange($start, $end);
        array_pop($bookingDates);

        foreach ($bookingDates as $date) {
            //TODO figure out how to solve recurring dates. It must be simple.
            $priceModifiers = $entityManager->getRepository(PriceModifier::class)->getPriceModifiersForDay($date);

            foreach ($priceModifiers as $priceModifier) {
                $modifier = $priceModifier->getModifier();
                if (!$modifier) {
                    $modifier = 1;
                }
                $price = $price * $modifier;
                $this->$price = $price;
            }

            $total += $price;
        }
        return $total;
    }
}
