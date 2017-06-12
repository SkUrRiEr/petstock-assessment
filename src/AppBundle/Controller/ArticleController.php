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
     * @Rest\View()
     */
    public function getArticlesAction()
    {
        $authors = $this->getRepo()->findAll();

        return $authors;
    }

    /**
     * @Rest\View()
     */
    public function getArticleAction($id)
    { 
        $author = $this->getRepo()->find($id);

        if (!$author) {
            throw $this->createNotFoundException("Could not find author with id " . $id);
        }

        return $author;
    }
}
