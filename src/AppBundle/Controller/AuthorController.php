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
use AppBundle\Entity\Author;

class AuthorController extends Controller
{
    private function getRepo()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository("AppBundle:Author");
    }

    /**
     * @Rest\View()
     */
    public function getAuthorsAction()
    {
        $authors = $this->getRepo()->findAll();

        return $authors;
    }

    /**
     * @Rest\View()
     */
    public function getAuthorAction($id)
    { 
        $author = $this->getRepo()->find($id);

        if (!$author) {
            throw $this->createNotFoundException("Could not find author with id " . $id);
        }

        return $author;
    }

    /**
     * Post new author action
     * 
     * @Rest\View()
     * @Rest\RequestParam(name="name", nullable=false)
     *
     * @param ParamFetcher $fetcher
     */
    public function postAuthorAction(ParamFetcher $fetcher)
    {
        $author = new Author();

        $form = $this->createFormBuilder($author, array('csrf_protection' => false))
            ->add("name", Type\TextType::class)
            ->getForm();

        $form->submit($fetcher->all(), true);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('get_author', array("id" => $author->getId()));
        }

        return $form;
    }
}
