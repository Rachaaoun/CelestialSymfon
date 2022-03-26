<?php

namespace App\Controller;

use App\Entity\CategoriePost;
use App\Form\CategoriePostType;
use App\Repository\CategoriePostRepository;
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
 * @Route("/categorie/post")
 */
class CategoriePostController extends AbstractController
{


    
    const ATTRIBUTES_TO_SERIALIZE =['id','nom_categorie_post'];



    /**
     * @Route("/", name="categorie_post_index", methods={"GET"})
     */
    public function index(CategoriePostRepository $categoriePostRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $donnes=$categoriePostRepository->findAll();
        $categ=$paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('categorie_post/index.html.twig', [
            'categorie_posts' => $categ,
        ]);
    }
/**
     * @Route("/Allcategorie/Json", name="Allcategorie", methods={"GET"})
     */
    public function JSONindex(CategoriePostRepository $categoriePostRepository,SerializerInterface $serializer): Response
    {
        $result = $categoriePostRepository->findAll();
        /* $n = $normalizer->normalize($result, null, ['groups' => 'livreur:read']);
        $json = json_encode($n); */
        $json = $serializer->serialize($result, 'json', ['groups' => 'categorie:read']);
        return new JsonResponse($json, 200, [], true);
    }
    /**
     * @Route("/new", name="categorie_post_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $categoriePost = new CategoriePost();
        $form = $this->createForm(CategoriePostType::class, $categoriePost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($categoriePost);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $entityManager->persist($categoriePost);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_post/new.html.twig', [
            'categorie_post' => $categoriePost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_post_show", methods={"GET"})
     */
    public function show(CategoriePost $categoriePost): Response
    {
        return $this->render('categorie_post/show.html.twig', [
            'categorie_post' => $categoriePost,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CategoriePost $categoriePost, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $form = $this->createForm(CategoriePostType::class, $categoriePost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($categoriePost);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $entityManager->flush();

            return $this->redirectToRoute('categorie_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_post/edit.html.twig', [
            'categorie_post' => $categoriePost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_post_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoriePost $categoriePost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoriePost->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoriePost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_post_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/Allcomments/Json", name="Allcommentaire", methods={"GET"})
     */
    public function JSONindex2(CategoriePostRepository $Rep,SerializerInterface $serializer): Response
    {
        $result = $Rep->findAll();
        /* $n = $normalizer->normalize($result, null, ['groups' => 'livreur:read']);
        $json = json_encode($n); */
        $json = $serializer->serialize($result, 'json', ['groups' => 'categorie:read']);
        return new JsonResponse($json, 200, [], true);
    }




       /**
     * @Route("/delete/categorie/json", name="supprimer_categorie")
     */
    public function supprimerCommentaire(Request $request, CategoriePostRepository $repo): Response
    {

        $id =$request->get("id");
        $em=$this->getDoctrine()->getManager();

     $d=   $repo->find($id);

        if($d != null){
            $em->remove($d);
            $em->flush();
            $serializer=new Serializer([new ObjectNormalizer()]);
            $formatted=$serializer->normalize("Categorie a eté supprimeé");
            return new JsonResponse($formatted);
        }

       return  new JsonResponse("Id Invalide");
    }





    /**
     * @Route("/Allcategories/json")
     * @param CategoriePostRepository $repo
     */
    public function getList(CategoriePostRepository $repo,SerializerInterface $serializer):Response{
     
        $categorie=$repo->findAll();
        $json=$serializer->serialize($categorie,'json', ['groups' => ['categorie']]);


        return $this->json(['categorie'=>$categorie],Response::HTTP_OK,[],[
            'attributes'=>self::ATTRIBUTES_TO_SERIALIZE
        ]);

}

    /**
     * @Route("/edit/categorie/json/{id}" , name="categorie_modifier" ,  methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function editCommentaire(Request $request,SerializerInterface $serializer,$id,CategoriePostRepository $repo)
    {
        $categorie=$repo->findOneById($id);
        $nom= $request->get('nomcategorie');
        $post=$request->query->get('post');

        $em=$this->getDoctrine()->getManager();
        $categorie->setNomCategoriePost($nom);
        $categorie->addPost($post);
        

        $em->persist($categorie);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($categorie);
        return new JsonResponse($formatted);
    }

    


     /**
     * @Route("/AddCategorie/json", name="AddCategorie")
     */
    public function AddCommentaireJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $categoriePost = new CategoriePost();
       $nom= $request->get('nomcategorie');
       $post= $request->get('post');
        $categoriePost->setNomCategoriePost($nom);
        $categoriePost->addPost($post);

       
        $em->persist($categoriePost);
        $em->flush();

        $jsonContent= $Normalizer->normalize($categoriePost,'json',['groups'=>"categorie:read"]);
        return new Response(json_encode($jsonContent));;
    }
}
