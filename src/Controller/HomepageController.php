<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use App\Controller\BookingController;
use App\Controller\CabinController;
use App\Entity\Cabin;

class HomepageController extends AbstractController
{

    #[Route('/', name: 'homepage')]
    public function homepage(ManagerRegistry $doctrine )
    {
        $entityManager = $doctrine->getManager();
        $cabins = $entityManager->getRepository(Cabin::class)->findAll();
        // $managerRegistry = new ManagerRegistry;
        // $cabins = $cabinController->index($managerRegistry);
        // $bookings = new BookingController->get_;
        return $this->render('homepage/homepage.html.twig', [
            'cabins' => $cabins
        ]);
        // die('Homepage to Booking 62!');
    }

    #[Route('/', name: 'app_homepage')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Homepage to your new controller!',
            'path' => 'src/Controller/HomepageController.php',
        ]);
    }
}
