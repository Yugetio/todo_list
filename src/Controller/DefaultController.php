<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TodoList;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
  public function getAllItems()
  {
    try {
      $items = $this->getDoctrine()
        ->getRepository(TodoList::class)
        ->findAll();

      $arrayCollection = TodoList::serialize($items);

      return new JsonResponse($arrayCollection, 200);
    } catch (\Exception $e) {
      return new JsonResponse(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * @Route("/item", methods="POST")
   * @param Request $request
   * @return Response
   */
  public function createItem(Request $request)
  {
    try { 
      $req = TodoList::jsonDecode($request);
      $valid = TodoList::validate($req);

      if ($valid){
        throw new BadRequestHttpException('Text from input shouldn`t be empty');
      }

      $item = new TodoList();
      $item->setText($req['text']);
      $item->setReady($req['ready']);
      $item->setUserId(1);

      $this->em->persist($item);
      $this->em->flush();

      return new Response('created', 201);
    } catch (\Exception $e) {
      return new JsonResponse(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * @Route("/item", methods="PUT")
   * @param Request $request
   * @return Response
   */
  public function editItem(Request $request)
  {
    try {
      $req = TodoList::jsonDecode($request);

      $id = $req['id'];
      $item = $this->em->find(TodoList::class, $id);

      if (!$item) {
        throw new NotFoundHttpException(
            'Item not found for id '.$id
        );
      }
      
      $text = $req['text'];

      if (!isset($text)) {
        throw new BadRequestHttpException('Text from input shouldn`t be empty');
      }

      $ready = $req['ready'];

      if (!isset($ready)) {
        throw new BadRequestHttpException('State of the element shouldn`t be empty');
      }
      
      $item->setText($text);
      $item->setReady($ready);

      $this->em->flush();

      return new Response('edited', 200);
    } catch (NotFoundHttpException $e) {
      return new JsonResponse(['error' => $e->getMessage()], 404);
    } catch (\Exception $e) {
      return new JsonResponse(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * @Route("/item", methods="DELETE")
   * @param Request $request
   * @return Response
   */
  public function deleteItem(Request $request)
  {
    try {
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
    } catch (\Exception $e) {
      return new JsonResponse(['error' => $e->getMessage()], 500);
    }
  }
}

 