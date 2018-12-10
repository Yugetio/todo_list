<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TodoList;

use Doctrine\ORM\EntityManager;

class DefaultController extends AbstractController
{
  /**
   * @Route("/", methods="GET")
   */
    public function renderIndex()
    {
      return $this->render('base.html.twig');
    }

    /**
   * @Route("/items", methods="GET")
   */
  public function getAllItemsF()
  {
    $items = $this->getDoctrine()
      ->getRepository(TodoList::class)
      ->findAll()
    ;

    $arrayCollection = array();

    foreach($items as $item) {
      $arrayCollection[] = array(
        'id' => $item->getId(),
        'text' => $item->getText(),
        'ready' => $item->getReady()
     );
    }
    
    return new JsonResponse($arrayCollection, 200);
  }

  /**
   * @Route("/item", methods="POST")
   * @param Request $request
   * @return Response
   */
  public function createItem(Request $request)
  {
    $req = json_decode($request->getContent(), true);
    $em = $this->getDoctrine()->getManager();

    $item = new TodoList();
    $item->setText($req['text']);
    $item->setReady($req['ready']);
    $item->setUserId(1);

    $em->persist($item);
    $em->flush();

    return new Response('created with id: '.$item->getId());
  }

  /**
   * @Route("/item", methods="PUT")
   * @param Request $request
   * @return Response
   */
  public function editItem(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $req = json_decode($request->getContent(), true);

    $id = $req['id'];
    $item = $em->find(TodoList::class, $id);

    $text = $req['text'];
    $ready = $req['ready'];
    
    $item->setText($text);
    $item->setReady($ready);

    $em->flush();


    return new Response('edited');
  }

  /**
   * @Route("/item", methods="DELETE")
   * @param Request $request
   * @return Response
   */
  public function deleteItem(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $id = json_decode($request->getContent(), true)['id'];
    
    $item = $em->find(TodoList::class, $id);
    $em->remove($item);
    $em->flush();

    return new Response('item was delete');
  }
}

 