<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\BookingController;

class HomepageController extends AbstractController
{

    #[Route('/', name: 'homepage')]
    public function homepage()
    {
        // $bookings = new BookingController->get_;
        return $this->render('homepage/homepage.html.twig', [
            'title' => 'Booking 62'
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
