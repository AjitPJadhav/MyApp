<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
public function beforeFilter(Event $event) {
    // put in the functions that point to the views you want to be able to see 
    // without logging in. This works for all controllers so be careful for naming
    // functions the same thing. (all index pages are viewable in this example)
    //$this->Auth->allow('posts','users','register','view');
    //$this->Auth->loginAction = array('controller'=>'Posts', 'action'=>'index');
}
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
   public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', 
                [
                    'authorize'=> 'Controller',
                'authenticate' => [
                    'Form' => [
                        'fields' => [
                            'username' => 'email',
                            'password' => 'password'
                        ]
                    ]
                ],
                'loginAction' => [
                    'controller' => 'users',
                    'action' => 'login'
                ],
                'loginRedirect' => [
                    'controller' => 'Posts',
                    'action' => 'add'
                    ],
                'logoutRedirect' => [
                    'controller' => 'users',
                    'action' => 'logout'
                    ]
            
            
            ]);
        //$this->Auth->allow(['users']);
    }
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        // Login Check
        if($this->request->session()->read('Auth.User')){
             $this->set('loggedIn', true);   
        } else {
            $this->set('loggedIn', false); 
        }
    }
}
