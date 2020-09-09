<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      // use the factory to create a Faker\Generator instance
      $faker = \Faker\Factory::create('fr_FR');
      // Utilisation de fzaninotto/Faker librairy et paramètre en français

      // Création de 3 catégories fakées
      // Pour chaque catégorie, je définis son titre et son contenu
      // Pour chaque catégorie, je créée un article
      // Pour chaque article, je donne  un nom et son contenu et un commentaire

      for ($i=1; $i <=3 ; $i++) {
          $category = new Category();
          $category->setTitle($faker->sentence())
                  ->setDescription($faker->paragraph());

          $manager->persist($category);

      // créer entre 4 et 6 articles - mt-rand est une fontion php
      for ($j=1; $j <= mt_rand(4,6); $j++) //  Création aléatoire d'articles
      {
          $article = new Article();

           // création des paragraphes sous forme de tableau avec 5 paragaphes
          $content = '<p>' . join($faker->paragraphs(5),'</p><p>') . '</p>';

          $article->setTitle($faker->sentence())
                  ->setContent($content)
                  ->setImage($faker->imageUrl())
                  ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                  ->setCategory($category);

          $manager->persist($article);

      for ($k=1; $k <= mt_rand(4,10) ; $k++) {
           // On donne des commentaires à l'articles
          $comment = new Comment();

          // création des paragraphes sous forme de tableau avec 5 paragaphes
          $content ='<p>' . join($faker->paragraphs(2),'</p><p>') . '</p>';

          // interval entre la date de 6 mois et aujourd'hui
          $days = $interval = (new \DateTime())->diff($article->getCreatedAt())->days;


          $comment->setAuthor($faker->name())
                  ->setContent($content)
                  ->setCreatedAt($faker->dateTimeBetween('_'.$days.'days')) // -100 jours
                  ->setArticle($article);

          $manager->persist($comment);
      }
    }
    $manager->flush();
    }
  }
}
