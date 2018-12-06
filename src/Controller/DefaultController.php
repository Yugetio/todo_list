<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TodoList;

class DefaultController extends AbstractController
{
  /**
   * @Route("/", methods="GET")
   */
    public function getAllItems()
    {
      $items = $this->getDoctrine()
        ->getRepository(TodoList::class)
        ->findAll()
      ;
      
      return $this->render('base.html.twig', [
          'data' => $items
      ]);
    }

  /**
   * @Route("/", methods="POST")
   */
  public function createItem()
  {
    $em = $this->getDoctrine()->getManager(); // получаэм доступ до entity

    $item = new TodoList();
    $item->setText('create some other app');
    $item->setReady(false);
    $item->setUserId(1);

    $em->persist($item); //сохраняєм без запроса до бд

    $em->flush();

    $this->getAllItems();
    return new Response('Check out this great product: ', 200);
  }
}

  

//create 



//get one by id

// $id = 1;

// $item = $this->getDoctrine()
//   ->getRepository(TodoList::class)
//   ->find($id);

// if (!$item) {
//   throw $this->createNotFoundException(
//       'No product found for id '.$id
//   );
// }

// return new Response('Check out this great product: '.$item->getText());