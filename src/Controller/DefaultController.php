<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TodoList;

class DefaultController extends AbstractController
{

  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
   * @Route("/", methods="GET")
   */
    public function renderIndex()
    {
      return $this->render('base.html.twig');
    }

    /**
   * @Route("/items", methods="GET")
   * @return JsonResponse
   */
  public function getAllItemsF()
  {
    $items = $this->getDoctrine()
      ->getRepository(TodoList::class)
      ->findAll();

    $arrayCollection = TodoList::serialize($items);

    return new JsonResponse($arrayCollection, 200);
  }

  /**
   * @Route("/item", methods="POST")
   * @param Request $request
   * @return Response
   */
  public function createItem(Request $request)
  {
    $req = TodoList::jsonDecode($request);

    $item = new TodoList();
    $item->setText($req['text']);
    $item->setReady($req['ready']);
    $item->setUserId(1);

    $this->em->persist($item);
    $this->em->flush();

    return new Response('created', 201);
  }

  /**
   * @Route("/item", methods="PUT")
   * @param Request $request
   * @return Response
   */
  public function editItem(Request $request)
  {
    $req = TodoList::jsonDecode($request);

    $id = $req['id'];
    $item = $this->em->find(TodoList::class, $id);

    $text = $req['text'];
    $ready = $req['ready'];
    
    $item->setText($text);
    $item->setReady($ready);

    $this->em->flush();


    return new Response('edited', 200);
  }

  /**
   * @Route("/item", methods="DELETE")
   * @param Request $request
   * @return Response
   */
  public function deleteItem(Request $request)
  {
    $id = TodoList::jsonDecode($request)['id'];
        
    $item = $this->em->find(TodoList::class, $id);

    if (!$item) {
      throw new NotFoundHttpException(
          'Item not found for id '.$id
      );
    }

    $this->em->remove($item);
    $this->em->flush();

    return new Response('item was delete', 200);
  }
}

 