<?php

namespace App\Controller;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;


class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('index.html.twig',['articles'=> $articles]);
        }
    }
    #[Route("/articel/{id}", name:"article_show")]
 
public function show($id, EntityManagerInterface $entityManager) {
    $article = $entityManager->getRepository(Article::class)
        ->find($id);
    return $this->render('show.html.twig', array('article' => $article));
}
 
      /**
    * @Route("/new", name="new_article")
    * @Method({"GET", "POST"})
    */
public function new(Request $request, EntityManagerInterface $entityManager) {
  
    $article = new Article();
    $form = $this->createFormBuilder($article)
    ->add('titre', TextType::class)
    ->add('contenu', TextType::class)
    ->add('date', TextType::class)
  
    ->add('save', SubmitType::class, array('label' => 'Créer'))
    ->getForm();
        
        
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $article = $form->getData();

        $entityManager->persist($article);
        $entityManager->flush();

        return $this->redirectToRoute('article_list');
    }

    return $this->render('new.html.twig', ['form' => $form->createView()]);
}
/**
 * @Route("/article/delete/{id}",name="delete_article")
 * @Method("GET","DELETE")
 */
public function delete(Request $request, $id ,EntityManagerInterface $entityManager) {
    $article = $entityManager->getRepository(Article::class)->find($id);
   
    $entityManager->remove($article);
    $entityManager->flush();
    return $this->redirectToRoute('/');
    }

} 
?>