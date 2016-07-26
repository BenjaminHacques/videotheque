<?php
# Fichier videotheque/src/Iabsis/Bundle/VideothequeBundle/DataFixtures/ORM/LoadingFixtures.php
/* Les Fixtures doivent êtres stockées dans le namespace suivant */
namespace  Iabsis\VideothequeBundle\DataFixtures\ORM;
  
/*
 *  On a besoin de recourir à l'interface FixtureInterface pour définir une fixture alors on le déclare
 * Si nous n'avions pas mis ce use, on aurait dû taper
 * class LoadingFixtures implements Doctrine\Common\DataFixtures\FixtureInterface pour l'implémentation
 * de l'interface FixtureInterface, ce qui avouons-le n'est pas toujours très pratique, surtout si on
 * veut utiliser plusieurs fois l'objet / interface en question.
 */
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
 
/*
 * Nous aurons besoin de nos entités Genre et Film également, on le déclare donc ici aussi...
 */
use Iabsis\VideothequeBundle\Entity\Genre;
use Iabsis\VideothequeBundle\Entity\Film;

use Iabsis\VideothequeBundle\Entity\Role;
use Iabsis\VideothequeBundle\Entity\User;

# il nous faut ce namespace pour la gestion du cryptage du mot de passe
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
 
/*
 * Les fixtures sont des objets qui doivent obligatoireemnt implémenter l'interface FixtureInterface
 */
class LoadingFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Création d'un Genre "Horreur"
        $Horreur = new Genre();
        $Horreur->setLabel("Horreur");
        // Enregistrment dans la base de données
        $manager->persist($Horreur);
        $manager->flush();
         
        // Création d'un genre Action
        $Action = new Genre();
        $Action->setLabel("Action");
        // Enregistrment dans la base de données
        $manager->persist($Action);
        $manager->flush();
         
        // Création d'un genre Aventure
        $Aventure = new Genre();
        $Aventure->setLabel("Aventure");
        // Enregistrment dans la base de données
        $manager->persist($Aventure);
        $manager->flush();
         
        // Création d'un genre Science fiction
        $Science_fiction = new Genre();
        $Science_fiction->setLabel("Science fiction");
        // Enregistrment dans la base de données
        $manager->persist($Science_fiction);
        $manager->flush();
         
        // On crée le film Matrix !
        $Film = new Film();
        $Film->setTitre("Matrix");
        $Film->setDescription(
            "Un super film ou neo va se révéler être l'élu. Sa mission sera de sauver l'humanité de la matrix. " .
            "Mais ... Qu'est ce que la matrice ?"
        );
        
        $Film->getListeDesGenres()->add($Action);
        $Film->getListeDesGenres()->add($Science_fiction);
        // Enregistrment dans la base de données
        $manager->persist($Film);
        $manager->flush();

        // Create the roles
        $role_user = new Role();
        $role_user->setName("ROLE_USER");
        $manager->persist($role_user);
        $manager->flush();

        $role_admin = new Role();
        $role_admin->setName("ROLE_ADMIN");
        $manager->persist($role_admin);
        $manager->flush();

        // create a user
        $user = new User();

        // On donne le login Admin à notre nouvel utilisateur
        $user->setUsername('admin');

        // On cré un salt pour amélioré la sécurité
        $user->setSalt(md5(time()));

        // On crée un mot de passe (attention, comme vous pouvez le voir, il faut utiliser les même paramètres
        // que spécifiés dans le fichier security.yml, à savoir SHA512 avec 10 itérations.
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        // On cré donc le mot de passe "password" à partir de l'encodage choisi au-dessus
        $password = $encoder->encodePassword('password', $user->getSalt());
        // On applique le mot de passe à l'utilisateur
        $user->setPassword($password);

        $user->getUserRoles()->add($role_admin);

        $manager->persist($user);
        $manager->flush();
    }
}
