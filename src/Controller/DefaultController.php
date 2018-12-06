<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
  /**
   * @Route("/", methods="GET")
   */
    public function todo()
    {
      $number = random_int(0, 100);

      return $this->render('base.html.twig', [
          'number' => $number,
      ]);
    }
}