<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * FoundItems Controller
 *
 * @property \App\Model\Table\FoundItemsTable $FoundItems
 * @method \App\Model\Entity\FoundItem[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FoundItemsController extends AppController
{
    /**
     * Get current logged-in user id
     */
    private function getCurrentUserId()
    {
        $identity = $this->request->getAttribute('identity');

        if ($identity) {
            if (method_exists($identity, 'getIdentifier')) {
                return $identity->getIdentifier();
            }

            if (method_exists($identity, 'get')) {
                return $identity->get('id');
            }

            return $identity->id ?? null;
        }

        $sessionUser = $this->request->getSession()->read('Auth.User');

        if (!empty($sessionUser['id'])) {
            return $sessionUser['id'];
        }

        return null;
    }

    /**
     * Get current logged-in user role
     */
    private function getCurrentUserRole()
    {
        $identity = $this->request->getAttribute('identity');

        if ($identity) {
            if (method_exists($identity, 'get')) {
                return $identity->get('role');
            }

            return $identity->role ?? null;
        }

        $sessionUser = $this->request->getSession()->read('Auth.User');

        if (!empty($sessionUser['role'])) {
            return $sessionUser['role'];
        }

        return null;
    }

    /**
     * Check if current user can modify item
     */
    private function canModify($foundItem): bool
    {
        $userId = $this->getCurrentUserId();
        $role = $this->getCurrentUserRole();

        if ($role === 'admin') {
            return true;
        }

        return $userId && (int)$foundItem->user_id === (int)$userId;
    }

    /**
     * Upload image
     */
    private function uploadImage(array $data): array
    {
        $image = $this->request->getData('image');

        if (is_object($image) && $image->getError() === UPLOAD_ERR_OK) {
            $originalName = $image->getClientFilename();
            $safeName = preg_replace('/[^A-Za-z0-9_\.\-]/', '_', $originalName);
            $filename = time() . '_' . $safeName;

            $uploadPath = WWW_ROOT . 'img' . DS . 'found_items' . DS;

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            $image->moveTo($uploadPath . $filename);

            $data['image'] = $filename;
        } else {
            unset($data['image']);
        }

        return $data;
    }

    /**
     * Index method
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
            'order' => ['FoundItems.created_at' => 'DESC'],
        ];

        $foundItems = $this->paginate($this->FoundItems);

        $this->set(compact('foundItems'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        $foundItem = $this->FoundItems->get($id, [
            'contain' => ['Users', 'Claims'],
        ]);

        $this->set(compact('foundItem'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $foundItem = $this->FoundItems->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $userId = $this->getCurrentUserId();

            if (!$userId) {
                $this->Flash->error('Please login first.');

                return $this->redirect([
                    'controller' => 'Users',
                    'action' => 'login'
                ]);
            }

            $data['user_id'] = $userId;

            if (empty($data['status'])) {
                $data['status'] = 'Available';
            }

            $data = $this->uploadImage($data);

            $foundItem = $this->FoundItems->patchEntity($foundItem, $data);

            if ($this->FoundItems->save($foundItem)) {
                $this->Flash->success(__('The found item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The found item could not be saved. Please try again.'));
        }

        $this->set(compact('foundItem'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        $foundItem = $this->FoundItems->get($id, [
            'contain' => [],
        ]);

        if (!$this->canModify($foundItem)) {
            throw new \Cake\Http\Exception\ForbiddenException('Access denied');
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $data = $this->uploadImage($data);

            $foundItem = $this->FoundItems->patchEntity($foundItem, $data);

            if ($this->FoundItems->save($foundItem)) {
                $this->Flash->success(__('The found item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The found item could not be saved. Please try again.'));
        }

        $users = $this->FoundItems->Users->find('list', ['limit' => 200])->all();

        $this->set(compact('foundItem', 'users'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $foundItem = $this->FoundItems->get($id);

        if (!$this->canModify($foundItem)) {
            throw new \Cake\Http\Exception\ForbiddenException('Access denied');
        }

        if ($this->FoundItems->delete($foundItem)) {
            $this->Flash->success(__('The found item has been deleted.'));
        } else {
            $this->Flash->error(__('The found item could not be deleted. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}