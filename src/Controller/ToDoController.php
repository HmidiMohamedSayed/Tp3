<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    /**
     * @Route("/todo",name="todo")
     */
    public function indexAction(SessionInterface $session): Response
    {
        if (!$session->has('todo')) {
            $message="bienvenu dans votre plateforme de TODOs";
            $this->addFlash('bienvenu',$message);
            $todos = array(
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            );
            $session->set('todo', $todos);
        }
        return $this->render("todo\listeToDo.html.twig");
    }
    /**
     *@Route("/add/{cle}/{valeur}",name="add")
     */
    public function addToDoAction(SessionInterface $session,$cle,$valeur){
        if(!$session->has('todo')){
            $message="la liste n'est pas encore initialisée";
            $this->addFlash('warning',$message);
        }
        else{
            if(array_key_exists($cle,$session->get('todo'))){
                $tab=$session->get('todo');
                $warn="la cle " .$cle." existe deja vous allez la modifier sa valeur de ".$tab[$cle]." en ".$valeur;
                $this->addFlash('warn',$warn);

                $tab[$cle]=$valeur;
                $session->set('todo',$tab);
            }
            else{
                $tab= $session->get('todo');
                $tab[$cle]=$valeur;
                $session->set('todo',$tab);
                $succ="ajout avec succés de l'element ".$cle." de valeur ".$valeur;
                $this->addFlash('succ',$succ);
            }
        }
        return $this->render('todo/listeToDo.html.twig');
    }
    /**
     *@Route("/sup/{cle}",name="sup")
     */
    public function supprimerToDo(SessionInterface $session,$cle)
    {
        if (!$session->has('todo')) {
            $message = "la liste n'existe pas vous ne pouvez pas supprimer des elements";
            $this->addFlash('error', $message);

        }
        else{
            if(array_key_exists($cle,$session->get('todo'))){
                $tab=$session->get('todo');
                $message="vous etes en train de supprimer l'element ".$cle." avec succes";
                $this->addFlash('sup', $message);
                unset($tab[$cle]);
                $session->set('todo',$tab);
            }
            else{
                $message="l'element ".$cle. " n'existe pas vous ne pouvez pas supprimer un element inexistant";
                $this->addFlash('supp',$message);
            }
        }
        return $this->render('todo/supprimer.html.twig');
    }
    /**
     *@Route("/resetToDo",name="reset")
     */
    public function reset(SessionInterface $session){
        if(!$session->has('todo')) {
            $message = "La liste n'existe pas il faut l'initialiser ";
            $this->addFlash('reset',$message);
            return $this->render('todo/reset.html.twig');
        }
        else{
            $message="l'action de reset se fait avec succes ";
            $this->addFlash('Succesreset',$message);
            $todos = array(
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens');
            $session->set('todo', $todos);
            return $this->render('todo/listeToDo.html.twig');
        }
    }
}