<?php

namespace Books\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Books\Form\BooksForm,
    Doctrine\ORM\EntityManager,
    Books\Entity\Books,
    Zend\View\Model\JsonModel,
    Zend\Filter\PregReplace;

class BooksController extends AbstractActionController {

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    //Ação para retorna todos os books
    public function indexAction() {
        //retorna todos os books
        $books = $this->getEntityManager()->getRepository('Books\Entity\Books')->findAll();

        //retorno em forma de json
        return new JsonModel(array(
            'books' => $books
        ));
    }

    //Ação para retornar um book específico
    public function bookAction() {
        //recebe o parâmetro id
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

        //busca do book com o id
        $book = $this->getEntityManager()->find('Books\Entity\Books', $id);

        //retorno em forma de json
        return new JsonModel(array(
            'book' => $book,
        ));
    }

    //Ação para retornar um book com um título específico
    public function getByTitleAction() {
        //recebe o parâmetro title
        $title = $this->getEvent()->getRouteMatch()->getParam('title');
        
        //Substitui o caracter '+' por ' ' para fazer a pesquisa
        $title = str_replace ('+' , ' ' , $title);

        //busca do book com o titulo
        $books = $this->getEntityManager()->getRepository('Books\Entity\Books')->findBy(array('title' => $title));

        //retorno em forma de json
        return new JsonModel(array(
            'books' => $books,
        ));
    }

    //Ação para retornar um book com um autor específico
    public function getByAuthorAction() {
        //recebe o parâmetro author
        $author = $this->getEvent()->getRouteMatch()->getParam('author');
        
        //Substitui o caracter '+' por ' ' para fazer a pesquisa
        $author = str_replace ('+' , ' ' , $author);
        
        //busca do book pelo autor
        $books = $this->getEntityManager()->getRepository('Books\Entity\Books')->findBy(array('author' => $author));

        //retorno em forma de json
        return new JsonModel(array(
            'books' => $books,
        ));
    }

    //Ação para retornar um book com um título e um autor específicos
    public function getByTitleAndAuthorAction() {
        //recebe o parâmetro title
        $title = $this->getEvent()->getRouteMatch()->getParam('title');

        //recebe o parâmetro author
        $author = $this->getEvent()->getRouteMatch()->getParam('author');

        //Substitui o caracter '+' por ' ' para fazer a pesquisa
        $title = str_replace ('+' , ' ' , $title);
        $author = str_replace ('+' , ' ' , $author);

        //busca do book com o id
        $books = $this->getEntityManager()->getRepository('Books\Entity\Books')->findBy(array('title' => $title, 'author' => $author));

        //retorno em forma de json
        return new JsonModel(array(
            'books' => $books,
        ));
    }

    /* Não necessário */

    public function editAction() {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('books', array('action' => 'add'));
        }
        $books = $this->getEntityManager()->find('Books\Entity\Books', $id);

        $form = new BooksForm();
        $form->setBindOnValidate(false);
        $form->bind($books);
        $form->get('submit')->setAttribute('label', 'Edit');
        $request = $this->getRequest();
        if ($request->isPost()) {
            //$form->setData($request->post());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->bindValues();
                $this->getEntityManager()->flush();

// Redirect to list of books
                return $this->redirect()->toRoute('books');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    /* Não necessário */

    public function deleteAction() {
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('books');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            //$del = $request->post()->get('del', 'No');
            $del = $request->getPost()->get('del', 'No');

            if ($del == 'Yes') {
                //$id = (int) $request->post()->get('id');
                $id = (int) $request->getPost()->get('id');
                $books = $this->getEntityManager()->find('Books\Entity\Books', $id);
                if ($books) {
                    $this->getEntityManager()->remove($books);
                    $this->getEntityManager()->flush();
                }
            }

// Redirect to list of books
            return $this->redirect()->toRoute('default', array(
                        'controller' => 'books',
                        'action' => 'index',
            ));
        }

        return array(
            'id' => $id,
            'books' => $this->getEntityManager()->find('Books\Entity\Books', $id)->getArrayCopy()
        );
    }

}

?>