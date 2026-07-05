<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * LostItems Controller
 *
 * @property \App\Model\Table\LostItemsTable $LostItems
 * @method \App\Model\Entity\LostItem[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LostItemsController extends AppController
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
    private function canModify($lostItem): bool
    {
        $userId = $this->getCurrentUserId();
        $role = $this->getCurrentUserRole();

        if ($role === 'admin') {
            return true;
        }

        return $userId && (int)$lostItem->user_id === (int)$userId;
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

            $uploadPath = WWW_ROOT . 'img' . DS . 'lost_items' . DS;

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
            'order' => ['LostItems.created_at' => 'DESC'],
        ];

        $lostItems = $this->paginate($this->LostItems);

        $this->set(compact('lostItems'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        $lostItem = $this->LostItems->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('lostItem'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $lostItem = $this->LostItems->newEmptyEntity();

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
                $data['status'] = 'Pending';
            }

            $data = $this->uploadImage($data);

            $lostItem = $this->LostItems->patchEntity($lostItem, $data);

            if ($this->LostItems->save($lostItem)) {
                $this->Flash->success(__('The lost item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The lost item could not be saved. Please try again.'));
        }

        $this->set(compact('lostItem'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        $lostItem = $this->LostItems->get($id, [
            'contain' => [],
        ]);

        if (!$this->canModify($lostItem)) {
            throw new \Cake\Http\Exception\ForbiddenException('Access denied');
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $data = $this->uploadImage($data);

            $lostItem = $this->LostItems->patchEntity($lostItem, $data);

            if ($this->LostItems->save($lostItem)) {
                $this->Flash->success(__('The lost item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The lost item could not be saved. Please try again.'));
        }

        $users = $this->LostItems->Users->find('list', ['limit' => 200])->all();

        $this->set(compact('lostItem', 'users'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $lostItem = $this->LostItems->get($id);

        if (!$this->canModify($lostItem)) {
            throw new \Cake\Http\Exception\ForbiddenException('Access denied');
        }

        if ($this->LostItems->delete($lostItem)) {
            $this->Flash->success(__('The lost item has been deleted.'));
        } else {
            $this->Flash->error(__('The lost item could not be deleted. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}