<?php

namespace Iabsis\VideothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        /**
         * On retourne les variables à envoyer au template défini dans le commentaire de la méthode indexAction
         * Si @Template() avait été vide, on aurait pu utiliser la syntaxe suivante :
         * return $this->render('IabsisVideothequeBundle:Admin:admin_home.html.twig', array());
         */
        return array();
    }
}
