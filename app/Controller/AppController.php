<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {
    public $components = array(
        'RequestHandler',
        'Session',
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array(
                        'username' => 'email', 
                        'password' => 'password'
                    )
                )
            ),
            'loginRedirect' => array(
                'controller' => 'messages',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
        )
    );
}
