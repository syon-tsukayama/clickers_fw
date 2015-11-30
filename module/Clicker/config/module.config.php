<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Clicker\Controller\Clicker' => 'Clicker\Controller\ClickerController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'clicker' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/clicker[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Clicker\Controller\Clicker',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'clicker' => __DIR__ . '/../view',
        ),
    ),
);
