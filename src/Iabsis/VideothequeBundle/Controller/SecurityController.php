<?php
namespace Iabsis\VideothequeBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
 
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="_security_login")
     * @Template()
     */
    public function loginAction()
    {
        // ON récupère les erreurs d'authentification si le formulaire a été passé avec de mauvaises informations
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
 
        return $this->render('IabsisVideothequeBundle:Security:login.html.twig', array(
            // On envoie à notre vue le login qu'a saisi l'utilisateur précédemment
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            // Et les erreurs qu'il y a eut lors de la validation du formulaire
            'error'         => $error,
        ));
    }
}
