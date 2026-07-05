<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Before Filter
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // Allow public access to login and register only
        $this->Authentication->addUnauthenticatedActions([
            'login',
            'register'
        ]);

        $identity = $this->request->getAttribute('identity');
        $action = $this->request->getParam('action');

        // Allow logout to run for all logged-in users
        if ($action === 'logout') {
            return;
        }

        // Admin-only actions
        $adminOnlyActions = [
            'index',
            'view',
            'add',
            'edit',
            'delete'
        ];

        if ($identity && in_array($action, $adminOnlyActions)) {
            $role = method_exists($identity, 'get')
                ? $identity->get('role')
                : ($identity->role ?? null);

            if ($role !== 'admin') {
                $this->Flash->error('You are not allowed to access this page.');

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);
            }
        }
    }

    /**
     * Index method
     *
     * Admin can view all users.
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $users = $this->Users->find()
            ->order([
                'Users.id' => 'DESC'
            ])
            ->all();

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [
                'LostItems',
                'FoundItems',
                'Claims',
                'Certificates'
            ],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method - Admin creates user
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (empty($data['role'])) {
                $data['role'] = 'user';
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $this->set(compact('user'));
    }

    /**
     * Register method - Student registration
     *
     * @return \Cake\Http\Response|null|void
     */
    public function register()
    {
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // New registered users are students
            $data['role'] = 'user';

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success('Your account has been registered. Please login.');

                return $this->redirect([
                    'controller' => 'Users',
                    'action' => 'login'
                ]);
            }

            $this->Flash->error('Unable to register account. Please try again.');
        }

        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (empty($data['role'])) {
                $data['role'] = $user->role;
            }

            // If password is empty during edit, keep old password
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $identity = $this->request->getAttribute('identity');

        $currentUserId = null;

        if ($identity) {
            $currentUserId = method_exists($identity, 'get')
                ? $identity->get('id')
                : ($identity->id ?? null);
        }

        // Prevent admin from deleting own account
        if ((int)$id === (int)$currentUserId) {
            $this->Flash->error(__('You cannot delete your own account.'));

            return $this->redirect(['action' => 'index']);
        }

        $user = $this->Users->get($id);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function login()
    {
        $result = $this->Authentication->getResult();

        // If already logged in, send user to dashboard
        if ($result && $result->isValid()) {
            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index'
            ]);
        }

        if ($this->request->is('post')) {
            $this->Flash->error('Invalid email or password');
        }
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response
     */
    public function logout()
    {
        // Logout from Authentication plugin
        $this->Authentication->logout();

        // Clear any manual session data too
        $session = $this->request->getSession();
        $session->delete('Auth.User');
        $session->delete('Auth');
        $session->delete('User');

        // Destroy session completely
        $session->destroy();

        return $this->redirect([
            'controller' => 'Users',
            'action' => 'login'
        ]);
    }
}