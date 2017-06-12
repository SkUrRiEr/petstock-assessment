<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}
