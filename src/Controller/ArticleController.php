<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\FormulaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ArticleController extends AbstractController
{
    private string $uploadDir;

    public function __construct(ParameterBagInterface $params)
    {
        // Initialisation du répertoire d'upload à partir des paramètres
        $this->uploadDir = $params->get('upload_dir');
    }

    #[Route('/articles', name: 'app_articles_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/create', name: 'app_articles_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Vous devez être connecté pour créer un article.');

        $article = new Article();
        $form = $this->createForm(FormulaireType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->uploadDir, $newFilename);
                $article->setImage($newFilename);
            }

            $article->setDate(new \DateTimeImmutable());
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article créé avec succès.');

            return $this->redirectToRoute('app_articles_list');
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/articles/edit/{id}', name: 'app_articles_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Vous devez être connecté pour modifier un article.');

        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        $form = $this->createForm(FormulaireType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->uploadDir, $newFilename);
                $article->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Article modifié avec succès.');

            return $this->redirectToRoute('app_articles_list');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/articles/delete/{id}', name: 'app_articles_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Vous devez être connecté pour supprimer un article.');

        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', 'Article supprimé avec succès.');

        return $this->redirectToRoute('app_articles_list');
    }
}