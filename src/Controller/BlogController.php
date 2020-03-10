<?php

namespace App\Controller;


use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{

    /**
    * @Route("/{page}", name="blog_list", defaults={"page": 5}, requirements={"page"="\d+"})
    * @param $page
    * @param \Symfony\Component\HttpFoundation\Request $request
    * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
    * @param \Doctrine\ORM\EntityManagerInterface $em
    * @param \App\Repository\BlogPostRepository $blogPostRepository
    * @return \Symfony\Component\HttpFoundation\JsonResponse
    */
    public function list($page, Request $request,
        SessionInterface $session,
        EntityManagerInterface $em,
        BlogPostRepository $blogPostRepository)
    {

        $query = $blogPostRepository->getOrderedArticles(
            $request->get('by', 'id'),
            $request->get('order', 'ASC'))
            ->setFirstResult($page*50)
            ->setMaxResults(50);

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $result = [];
        foreach ($paginator as $post) {
            $result[] = $post;
        }

        return new JsonResponse(
            [
                'page' => $page,
                'limit' => 50,
                'order' => $request->get('order', 'ASC'),
                'by' => $request->get('by', 'id'),
                'data' => array_map(function (BlogPost $item) {
                    return [
                        'id' =>  $item->getId(),
                        'title' =>  $item->getTitle(),
                        'status' =>  $item->getStatus(),
                        'date' =>  $item->getCreatedAt(),
                        'like' =>  $item->getLikesCount(),
                    ];
                }, $result)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
    * @param \App\Entity\BlogPost $id
    * @return \Symfony\Component\HttpFoundation\JsonResponse
    */
    public function post(BlogPost $id)
    {
        return $this->json($id);
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
    * @param \Symfony\Component\HttpFoundation\Request $request
    * @return \Symfony\Component\HttpFoundation\JsonResponse
    */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class,'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
    * @param \App\Entity\BlogPost $id
    * @return \Symfony\Component\HttpFoundation\JsonResponse
    */
    public function delete(BlogPost $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return new JsonResponse(['status' => 'ok']);
    }




}