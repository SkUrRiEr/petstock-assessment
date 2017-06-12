<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type;
use AppBundle\Entity\Article;

class ArticleController extends Controller
{
    private function getRepo()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository("AppBundle:Article");
    }

    /**
     * @Rest\View(serializerGroups={"All","Summary"})
     */
    public function getArticlesAction()
    {
        $articles = $this->getRepo()->findAll();

        return $articles;
    }

    /**
     * @Rest\View(serializerGroups={"All","Detail"})
     */
    public function getArticleAction($id)
    { 
        $article = $this->getRepo()->find($id);

        if (!$article) {
            throw $this->createNotFoundException("Could not find article with id " . $id);
        }

        return $article;
    }

    /**
     * Post new article action
     * 
     * @Rest\View()
     * @Rest\RequestParam(name="author_id", nullable=false)
     * @Rest\RequestParam(name="title", nullable=false)
     * @Rest\RequestParam(name="content", nullable=false)
     *
     * @param ParamFetcher $fetcher
     */
    public function postArticleAction(ParamFetcher $fetcher)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article, array('csrf_protection' => false))
            ->add("author", 'Symfony\Bridge\Doctrine\Form\Type\EntityType', array(
                'class' => 'AppBundle:Author'
            ))
            ->add("title", Type\TextType::class)
            ->add("content", Type\TextType::class)
            ->getForm();

        $data = $fetcher->all();

        if (isset($data["author_id"])) {
            $data["author"] = $data["author_id"];
            unset($data["author_id"]);
        }

        $form->submit($data, true);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $article->setCreatedAt(new \DateTime());
            $article->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('get_article', array("id" => $article->getId()));
        }

        return $form;
    }
}
