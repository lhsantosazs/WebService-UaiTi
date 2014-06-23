<?php

namespace Books;

return array(
    // Controllers in this module
    'controllers' => array(
        'invokables' => array(
            'Books\Controller\Books' => 'Books\Controller\BooksController',
        ),
    ),
    // Routes for this module
    'router' => array(
        'routes' => array(
            'books' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/books[/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Books\Controller\Books',
                        'action' => 'index',
                    ),
                ),
            ),
            //rota para a pagina do book
            'book' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/books/:id[/]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Books\Controller\Books',
                        'action' => 'book',
                    ),
                ),
            ),
            //filtro de titulo
            'title' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/books/title=:title[/]',
                    'defaults' => array(
                        'controller' => 'Books\Controller\Books',
                        'action' => 'get-by-title',
                    ),
                ),
            ),
            //filtro de autor
            'author' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/books/author=:author[/]',
                    'defaults' => array(
                        'controller' => 'Books\Controller\Books',
                        'action' => 'getByAuthor',
                    ),
                ),
            ),
            //filtro de titulo e autor
            'title_author' => array(
                'type' => 'segment',
                'options' => array(
                    //'route' => '/books/title=:title&author=:author[/]',
                    'route' => '/books/title=[:title]&author=[:author][/]',
                    'defaults' => array(
                        'controller' => 'Books\Controller\Books',
                        'action' => 'getByTitleAndAuthor',
                    ),
                ),
            ),
            //filtro de autor e titulo
            'author_title' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/books/author=[:author]&title=[:title][/]',
                    'defaults' => array(
                        'controller' => 'Books\Controller\Books',
                        'action' => 'getByTitleAndAuthor',
                    ),
                ),
            ),
        ),
    ),
    // View setup for this module
    'view_manager' => array(
        'template_path_stack' => array(
            'books' => __DIR__ . '/../view',
        ),
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    'strategies' => array(
        'ViewJsonStrategy',
    ),
);
?>
