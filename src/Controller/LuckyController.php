<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController
{
  /**
   * @Route("/test")
   */
    public function number()
    {
        return new Response(
            '<html><body>test</body></html>'
        );
    }
}