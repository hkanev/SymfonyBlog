<?php

namespace SoftUniBlogBundle\Controller;

use Doctrine\ORM\Mapping\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SoftUniBlogBundle\Form\ArticleType;
use SoftUniBlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @Route("/article/create", name="article_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())    {

            $article->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('article/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("article/{id}", name="article_view")
     * @param $id int
     * @return Response
     */
    public function articleAction($id)  {

        $articleRepo = $this->getDoctrine()->getRepository(Article::class);

        $article = $articleRepo->find($id);

        return $this->render('article/article.html.twig', array('article' => $article));

    }

    /**
     * @Route("article/edit/{id}", name="article_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $request Request
     * @param $id int
     * @return Response
     */
    public function editAction(Request $request, $id)  {

        $articleRepo = $this->getDoctrine()->getRepository(Article::class);

        $article = $articleRepo->find($id);

        if(null === $article)   {
            return $this->redirectToRoute('blog_index');
        }

        $currentUser = $this->getUser();

        if(!$currentUser->isAuthor($article) && !$currentUser->isAdmin())   {
            return $this->redirectToRoute('blog_index');
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted())    {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

        }

        return $this->render('article/edit.html.twig', array('article' => $article,
                                                                'form' => $form->createView()));

    }

    /**
     * @Route("article/delete/{id}", name="article_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $request Request
     * @param $id int
     * @return Response
     */
    public function deleteAction(Request $request, $id)  {

        $articleRepo = $this->getDoctrine()->getRepository(Article::class);

        $article = $articleRepo->find($id);

        if(null === $article)   {
            return $this->redirectToRoute('blog_index');
        }

        $currentUser = $this->getUser();

        if(!$currentUser->isAuthor($article) && !$currentUser->isAdmin())   {
            return $this->redirectToRoute('blog_index');
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted())    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            return $this->redirectToRoute('blog_index');

        }

        return $this->render('article/delete.html.twig', array('article' => $article,
            'form' => $form->createView()));

    }
}
