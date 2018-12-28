<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class User extends Fixture
{

    private $passwordEncoder;

    public function load(ObjectManager $manager)
    {
        $admin = new \App\Entity\User();
        $admin->setEmail('email@gmail.com')
              ->setName('Admin')
              ->setRoles(['ROLE_ADMIN'])
              ->setPassword($this->passwordEncoder->encodePassword($admin, '123456'));
        $manager->persist($admin);
        $manager->flush();
        for($i=0;$i<30;$i++){
            $article = new Article();
            $article->setName('Article'.$i)->setTextToPublish('Fat new smallness few supposing suspicion two. Course sir people worthy horses add entire suffer. How one dull get busy dare far. At principle perfectly by sweetness do. As mr started arrival subject by believe. Strictly numerous outlived kindness whatever on we no on addition. 
             Offered say visited elderly and. Waited period are played family man formed. He ye body or made on pain part meet. You one delay nor begin our folly abode. By disposed replying mr me unpacked no. As moonlight of my resolving unwilling. ');

              $article1= new Article();
              $article1->setName('Article'.$i)
                  ->setText('Fat new smallness few supposing suspicion two. Course sir people worthy horses add entire suffer. How one dull get busy dare far. At principle perfectly by sweetness do. As mr started arrival subject by believe. Strictly numerous outlived kindness whatever on we no on addition. 
            Offered say visited elderly and. Waited period are played family man formed. He ye body or made on pain part meet. You one delay nor begin our folly abode. By disposed replying mr me unpacked no. As moonlight of my resolving unwilling. ');

            $user = new \App\Entity\User();
             $user->setName('User')
                 ->setEmail($i.'email@ukr.net')
                 ->setPassword($this->passwordEncoder->encodePassword($user, '123456'))
                 ->addArticle($article)
                 ->addArticle($article1);

        $manager->persist($user);
        $manager->flush();
        }
    }

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
}
