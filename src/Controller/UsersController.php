<?php
namespace App\Controller;

use App\Controller\AppController;

use App\Controller;
use Cake\Event\Event;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
{
    parent::initialize();
    // Add the 'add' action to the allowed actions list.
    $this->Auth->allow(['logout', 'add']);
}
   
/**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
    }
    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Posts']
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }
    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }
    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }
    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    // Login
    public function login(){
        if($this->request->is('post')){
            $user=$this->Users->find( 'all',['conditions' => array(
                            'email' => $_POST['email'],
                            'password'=>$_POST['password']
                            )
             ]);
            
            //$user = $this->Users->get($_POST('email'),$_POST('password'));
            
            if($user){
                $this->Auth->setUser($user);
                    $this->Flash->success(__('Credential are correct'));
                return $this->redirect(['action' => 'welcome']);
            }
           
            // Bad Login
            $this->Flash->error('Incorrect Login');
            
        }
    }
    // Logout
    public function logout(){
         $this->Flash->success('You are logged out');
         return $this->redirect($this->Auth->logout());
    }
 
    public function register(){
        $user = $this->Users->newEntity();
        if($this->request->is('post')){
            $user = $this->Users->patchEntity($user, $this->request->data);
            if($this->Users->save($user)){
                $this->Flash->success('You are registered and can login');
                return $this->redirect(['action' => 'verify']);
            } else {
                $this->Flash->error('You are not registered');
            }
        }
        $this->set(compact('user'));
        $this->set('_serialzie', ['user']);
    }
    public function otp()
    {}
    public function process()
    {}
     public function otpprocess()
    {}
    public function success()
    {}
    public function verify()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'process']);
            } else {
                $this->Flash->error(__('The user is not be verfied. Please, try again.'));
            }
        }
    }
      public function beforeFilter(Event $event){
        $this->Auth->allow(['register']);
        $this->Auth->allow(['welcome']);
        $this->Auth->allow(['verify']);
        $this->Auth->allow(['otpprocess']);
        $this->Auth->allow(['process']);
        $this->Auth->allow(['otp']);
         $this->Auth->allow(['success']);
        
      }
    public function welcome()
    {
    }

}
