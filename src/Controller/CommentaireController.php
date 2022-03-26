<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Entity\Post;
use App\Repository\CommentaireRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    
    const ATTRIBUTES_TO_SERIALIZE =['id','msgCommentaire','dateCommentaire'];

    /**
     * @Route("/", name="commentaire_index", methods={"GET"})
     */
    public function index(Request $request,CommentaireRepository $commentaireRepository, PaginatorInterface $paginator): Response
    {
        $donnes=$commentaireRepository->findAll();
        $comments=$paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),
            2
        );

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $comments,
        ]);
    }
    
    /**
     * @Route("/new", name="commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($commentaire);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }
 /**
     * @Route("/newcommentaire", name="newCommentaire", methods={"GET", "POST"})
     */
    public function newCommentaire(Request $request, EntityManagerInterface $entityManager): Response
    {
       if ($request->request->get('inputComment')!= "") {

       /* $commentairs= new Commentaire();
        $Post = new Post();
        $id =(int) $request->request->get('poste');
        $Post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $commentaire = new Commentaire();
        $commentaire->setPost($Post);
        $commentaire->setMsgCommentaire($request->request->get('inputComment'));
        $entityManager->persist($commentaire);
        $entityManager->flush();
        $commentairs=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('post' => $id));*/
        $commentairs= new Commentaire();
        $Post = new Post();
        $id =(int) $request->request->get('poste');
        $Post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $commentairs->setPost($Post);
        $commentairs->setMsgCommentaire($request->request->get('inputComment'));
        $entityManager->persist($commentairs);
        $entityManager->flush();
        $commentairs=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('post' => $id));
    }
     return $this->render('basefront/DetailsPost.html.twig', array('post'=>$Post ,'commentairs'=>$commentairs));
    
     // return $this->redirectToRoute('get_post_show', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="commentaire_show", methods={"GET"})
     */
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($commentaire);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $entityManager->flush();

            return $this->redirectToRoute('commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commentaire_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/Allcomments/Json", name="Allcommentaire", methods={"GET"})
     */
    public function JSONindex(CommentaireRepository $Rep,SerializerInterface $serializer): Response
    {
        $result = $Rep->findAll();
        /* $n = $normalizer->normalize($result, null, ['groups' => 'livreur:read']);
        $json = json_encode($n); */
        $json = $serializer->serialize($result, 'json', ['groups' => 'commentaire:read']);
        return new JsonResponse($json, 200, [], true);
    }




       /**
     * @Route("/delete/commentaire/json", name="supprimer_commentaire")
     */
    public function supprimerCommentaire(Request $request, CommentaireRepository $repo): Response
    {

        $id =$request->get("id");
        $em=$this->getDoctrine()->getManager();

     $d=   $repo->find($id);

        if($d != null){
            $em->remove($d);
            $em->flush();
            $serializer=new Serializer([new ObjectNormalizer()]);
            $formatted=$serializer->normalize("Commentaire a eté supprimeé");
            return new JsonResponse($formatted);
        }

       return  new JsonResponse("Id Invalide");
    }





    /**
     * @Route("/Allcommentaires/json")
     * @param CommentaireRepository $repo
     */
    public function getList(CommentaireRepository $repo,SerializerInterface $serializer):Response{
     
        $commentaires=$repo->findAll();
        $json=$serializer->serialize($commentaires,'json', ['groups' => ['commentaire']]);


        return $this->json(['commentaire'=>$commentaires],Response::HTTP_OK,[],[
            'attributes'=>self::ATTRIBUTES_TO_SERIALIZE
        ]);

}




    /**
     * @Route("/edit/commentaire/json/{id}" , name="commentaire_modifier" ,  methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function editCommentaire(Request $request,SerializerInterface $serializer,$id,CommentaireRepository $repo)
    {
        $commentaire=$repo->findOneById($id);

        $message=$request->query->get('message');
        $post=$request->query->get('post');

        $em=$this->getDoctrine()->getManager();
        $commentaire->setDateCommentaire(new \DateTime('now'));
        $commentaire->setMsgCommentaire($message);
        $commentaire->setPost($post);


        $em->persist($commentaire);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($commentaire);
        return new JsonResponse($formatted);
    }

    


     /**
     * @Route("/AddCommentaire/json", name="AddCommentaire")
     */
    public function AddCommentaireJSON(Request $request,NormalizerInterface $Normalizer,PostRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = new Commentaire();
        $idPost=$request->get('idPost');
        $post = $repo->findOneById($idPost);
        $commentaire->setDateCommentaire(new \DateTime('now'));
        $commentaire->setMsgCommentaire($request->get('message'));
        $commentaire->setPost($post);
       
        $em->persist($commentaire);
        $em->flush();

        $jsonContent= $Normalizer->normalize($commentaire,'json',['groups'=>"commentaire:read"]);
        return new Response(json_encode($jsonContent));;
    }

    
     /**
     * @Route("/Allcommentaires/post/json")
     * @param PostRepository $repo
     */
    public function getCommentaireListByPost(PostRepository $repo,CommentaireRepository $commentaireRepository,Request $request,SerializerInterface $serializer):Response{
     
        $id=$request->query->get('id');
        $post=$repo->findOneById($id);
        $postId=$post->getId();
        $commentaires=$post->getCommentaires();
        $json=$serializer->serialize($commentaires,'json', ['groups' => ['commentaire']]);


        return $this->json(['commentaire'=>$commentaires],Response::HTTP_OK,[],[
            'attributes'=>self::ATTRIBUTES_TO_SERIALIZE
        ]);

}
}
