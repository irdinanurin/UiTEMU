<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\ForbiddenException;
use Cake\I18n\FrozenTime;

/**
 * Claims Controller
 *
 * @property \App\Model\Table\ClaimsTable $Claims
 * @method \App\Model\Entity\Claim[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClaimsController extends AppController
{
    /**
     * Get current logged-in user id for ClaimsController only
     */
    private function getClaimUserId()
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
     * Get current logged-in user role for ClaimsController only
     */
    private function getClaimUserRole()
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
     * Check if current user is admin for ClaimsController only
     */
    private function isCurrentAdmin(): bool
    {
        return $this->getClaimUserRole() === 'admin';
    }

    /**
     * Check if current user is the claimant
     */
    private function isClaimant($claim): bool
    {
        return (int)$claim->user_id === (int)$this->getClaimUserId();
    }

    /**
     * Check if current user is the finder/reporter of the found item
     */
    private function isFinder($claim): bool
    {
        if (!$claim->has('found_item')) {
            return false;
        }

        return (int)$claim->found_item->user_id === (int)$this->getClaimUserId();
    }

    /**
     * Generate pickup code
     */
    private function generatePickupCode(): string
    {
        return 'UITEMU-' . random_int(100000, 999999);
    }

    /**
     * Complete handover if both finder and claimant confirmed
     */
    private function completeHandoverIfReady($claim): void
    {
        if ((int)$claim->finder_confirmed === 1 && (int)$claim->claimant_confirmed === 1) {
            $claim->claim_status = 'Completed';
            $claim->handover_status = 'Completed';
            $claim->handed_over_at = FrozenTime::now();

            $this->Claims->save($claim);

            if ($claim->has('found_item')) {
                $foundItem = $claim->found_item;
                $foundItem->status = 'Returned';
                $this->Claims->FoundItems->save($foundItem);
            }

            if ($claim->has('lost_item')) {
                $lostItem = $claim->lost_item;
                $lostItem->status = 'Found';
                $this->Claims->LostItems->save($lostItem);
            }
        }
    }

    /**
     * Index method
     *
     * Admin sees all found items and all claims.
     * Finder sees claims made on their found items.
     * Claimant sees claims they submitted.
     */
    public function index()
    {
        $userId = $this->getClaimUserId();
        $role = $this->getClaimUserRole();

        $foundItemsTable = $this->fetchTable('FoundItems');

        if ($role === 'admin') {
            $foundItems = $foundItemsTable->find()
                ->contain([
                    'Users',
                    'Claims' => function ($q) {
                        return $q->contain(['Users', 'LostItems'])
                            ->order(['Claims.created_at' => 'DESC']);
                    }
                ])
                ->order(['FoundItems.created_at' => 'DESC'])
                ->all();
        } else {
            /**
             * Found items reported by current user.
             * Current user is the finder, so they can see all claims on these items.
             */
            $ownFoundItems = $foundItemsTable->find()
                ->contain([
                    'Users',
                    'Claims' => function ($q) {
                        return $q->contain(['Users', 'LostItems'])
                            ->order(['Claims.created_at' => 'DESC']);
                    }
                ])
                ->where(['FoundItems.user_id' => $userId])
                ->order(['FoundItems.created_at' => 'DESC'])
                ->all()
                ->toArray();

            /**
             * Found items claimed by current user.
             * Current user is claimant, so they should only see their own claim.
             */
            $claimedFoundItems = $foundItemsTable->find()
                ->contain([
                    'Users',
                    'Claims' => function ($q) use ($userId) {
                        return $q->contain(['Users', 'LostItems'])
                            ->where(['Claims.user_id' => $userId])
                            ->order(['Claims.created_at' => 'DESC']);
                    }
                ])
                ->matching('Claims', function ($q) use ($userId) {
                    return $q->where(['Claims.user_id' => $userId]);
                })
                ->where(['FoundItems.user_id !=' => $userId])
                ->distinct(['FoundItems.id'])
                ->order(['FoundItems.created_at' => 'DESC'])
                ->all()
                ->toArray();

            $foundItems = array_merge($ownFoundItems, $claimedFoundItems);
        }

        $this->set(compact('foundItems', 'role', 'userId'));
    }

    /**
     * View method
     *
     * @param string|null $id Claim id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function view($id = null)
    {
        $claim = $this->Claims->get($id, [
            'contain' => [
                'Users',
                'FoundItems' => ['Users'],
                'LostItems'
            ],
        ]);

        if (!$this->isCurrentAdmin() && !$this->isClaimant($claim) && !$this->isFinder($claim)) {
            throw new ForbiddenException('You are not allowed to view this claim.');
        }

        $userId = $this->getClaimUserId();
        $role = $this->getClaimUserRole();
        $isFinder = $this->isFinder($claim);
        $isClaimant = $this->isClaimant($claim);

        $this->set(compact('claim', 'userId', 'role', 'isFinder', 'isClaimant'));
    }

    /**
     * Add method
     *
     * @param string|null $foundItemId Found item id.
     * @return \Cake\Http\Response|null|void
     */
    public function add($foundItemId = null)
    {
        $userId = $this->getClaimUserId();

        if (!$userId) {
            $this->Flash->error('Please login first.');

            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login'
            ]);
        }

        if (!$foundItemId) {
            $this->Flash->error('Please choose a found item to claim.');

            return $this->redirect([
                'controller' => 'FoundItems',
                'action' => 'index'
            ]);
        }

        $claim = $this->Claims->newEmptyEntity();

        $foundItem = $this->Claims->FoundItems->get($foundItemId, [
            'contain' => ['Users']
        ]);

        /**
         * User cannot claim their own found item.
         */
        if ((int)$foundItem->user_id === (int)$userId) {
            $this->Flash->error('You cannot claim an item that you reported as found.');

            return $this->redirect([
                'controller' => 'FoundItems',
                'action' => 'index'
            ]);
        }

        /**
         * Prevent duplicate active claims by the same user for the same item.
         */
        $existingClaim = $this->Claims->find()
            ->where([
                'Claims.user_id' => $userId,
                'Claims.found_item_id' => $foundItemId,
                'Claims.claim_status IN' => ['Pending', 'Approved']
            ])
            ->first();

        if ($existingClaim) {
            $this->Flash->error('You already have an active claim for this item.');

            return $this->redirect(['action' => 'index']);
        }

        $lostItems = $this->Claims->LostItems->find('list', [
            'keyField' => 'id',
            'valueField' => 'item_name'
        ])
        ->where(['LostItems.user_id' => $userId])
        ->toArray();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $data['user_id'] = $userId;
            $data['found_item_id'] = $foundItemId;
            $data['claim_status'] = 'Pending';
            $data['handover_status'] = 'Not Arranged';
            $data['finder_confirmed'] = 0;
            $data['claimant_confirmed'] = 0;

            $claim = $this->Claims->patchEntity($claim, $data);

            if ($this->Claims->save($claim)) {
                $this->Flash->success('Your claim has been submitted. The finder will review your verification details.');

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('Unable to submit claim. Please try again.');
        }

        $this->set(compact('claim', 'foundItem', 'lostItems'));
    }

    /**
     * Edit method
     *
     * Admin only.
     *
     * @param string|null $id Claim id.
     * @return \Cake\Http\Response|null|void
     */
    public function edit($id = null)
    {
        if (!$this->isCurrentAdmin()) {
            throw new ForbiddenException('Only admin can edit claim records directly.');
        }

        $claim = $this->Claims->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $claim = $this->Claims->patchEntity($claim, $this->request->getData());

            if ($this->Claims->save($claim)) {
                $this->Flash->success(__('The claim has been saved.'));

                return $this->redirect(['action' => 'view', $claim->id]);
            }

            $this->Flash->error(__('The claim could not be saved. Please, try again.'));
        }

        $users = $this->Claims->Users->find('list', ['limit' => 200])->all();
        $foundItems = $this->Claims->FoundItems->find('list', ['limit' => 200])->all();
        $lostItems = $this->Claims->LostItems->find('list', ['limit' => 200])->all();

        $this->set(compact('claim', 'users', 'foundItems', 'lostItems'));
    }

    /**
     * Delete method
     *
     * Admin only.
     *
     * @param string|null $id Claim id.
     * @return \Cake\Http\Response|null|void
     */
    public function delete($id = null)
    {
        if (!$this->isCurrentAdmin()) {
            throw new ForbiddenException('Only admin can delete claim records.');
        }

        $this->request->allowMethod(['post', 'delete']);

        $claim = $this->Claims->get($id);

        if ($this->Claims->delete($claim)) {
            $this->Flash->success(__('The claim has been deleted.'));
        } else {
            $this->Flash->error(__('The claim could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Approve claim
     *
     * Finder or admin accepts claim and arranges handover.
     *
     * @param string|null $id Claim id.
     * @return \Cake\Http\Response|null
     */
    public function approve($id = null)
    {
        $this->request->allowMethod(['post']);

        $claim = $this->Claims->get($id, [
            'contain' => [
                'FoundItems',
                'LostItems'
            ]
        ]);

        if (!$this->isCurrentAdmin() && !$this->isFinder($claim)) {
            throw new ForbiddenException('Only the finder or admin can approve this claim.');
        }

        $data = $this->request->getData();

        $claim->claim_status = 'Approved';
        $claim->handover_status = 'Arranged';

        if (empty($claim->pickup_code)) {
            $claim->pickup_code = $this->generatePickupCode();
        }

        if (!empty($data['meeting_location'])) {
            $claim->meeting_location = $data['meeting_location'];
        }

        if (!empty($data['meeting_datetime'])) {
            $claim->meeting_datetime = $data['meeting_datetime'];
        }

        if (!empty($data['handover_notes'])) {
            $claim->handover_notes = $data['handover_notes'];
        }

        $claim->finder_confirmed = 0;
        $claim->claimant_confirmed = 0;

        if ($this->Claims->save($claim)) {
            if ($claim->has('found_item')) {
                $foundItem = $claim->found_item;
                $foundItem->status = 'Reserved';
                $this->Claims->FoundItems->save($foundItem);
            }

            if ($claim->has('lost_item')) {
                $lostItem = $claim->lost_item;
                $lostItem->status = 'Matched';
                $this->Claims->LostItems->save($lostItem);
            }

            $this->Flash->success('Claim accepted. Handover details and pickup code have been generated.');
        } else {
            $this->Flash->error('Unable to approve claim.');
        }

        return $this->redirect(['action' => 'view', $claim->id]);
    }

    /**
     * Reject claim
     *
     * Finder or admin can reject claim.
     *
     * @param string|null $id Claim id.
     * @return \Cake\Http\Response|null
     */
    public function reject($id = null)
    {
        $this->request->allowMethod(['post']);

        $claim = $this->Claims->get($id, [
            'contain' => ['FoundItems']
        ]);

        if (!$this->isCurrentAdmin() && !$this->isFinder($claim)) {
            throw new ForbiddenException('Only the finder or admin can reject this claim.');
        }

        $claim->claim_status = 'Rejected';
        $claim->handover_status = 'Not Arranged';
        $claim->finder_confirmed = 0;
        $claim->claimant_confirmed = 0;

        if ($this->Claims->save($claim)) {
            $this->Flash->success('Claim rejected successfully.');
        } else {
            $this->Flash->error('Unable to reject claim.');
        }

        return $this->redirect(['action' => 'view', $claim->id]);
    }

    /**
     * Finder confirms item has been handed over
     *
     * @param string|null $id Claim id.
     * @return \Cake\Http\Response|null
     */
    public function confirmHandedOver($id = null)
    {
        $this->request->allowMethod(['post']);

        $claim = $this->Claims->get($id, [
            'contain' => [
                'FoundItems',
                'LostItems'
            ]
        ]);

        if (!$this->isCurrentAdmin() && !$this->isFinder($claim)) {
            throw new ForbiddenException('Only the finder or admin can confirm handover.');
        }

        if ($claim->claim_status !== 'Approved') {
            $this->Flash->error('Only approved claims can be confirmed for handover.');

            return $this->redirect(['action' => 'view', $claim->id]);
        }

        $claim->finder_confirmed = 1;

        if ($this->Claims->save($claim)) {
            $this->completeHandoverIfReady($claim);
            $this->Flash->success('Handover confirmation saved.');
        } else {
            $this->Flash->error('Unable to confirm handover.');
        }

        return $this->redirect(['action' => 'view', $claim->id]);
    }

    /**
     * Claimant confirms item has been received
     *
     * @param string|null $id Claim id.
     * @return \Cake\Http\Response|null
     */
    public function confirmReceived($id = null)
    {
        $this->request->allowMethod(['post']);

        $claim = $this->Claims->get($id, [
            'contain' => [
                'FoundItems',
                'LostItems'
            ]
        ]);

        if (!$this->isCurrentAdmin() && !$this->isClaimant($claim)) {
            throw new ForbiddenException('Only the claimant or admin can confirm item received.');
        }

        if ($claim->claim_status !== 'Approved') {
            $this->Flash->error('Only approved claims can be confirmed as received.');

            return $this->redirect(['action' => 'view', $claim->id]);
        }

        $claim->claimant_confirmed = 1;

        if ($this->Claims->save($claim)) {
            $this->completeHandoverIfReady($claim);
            $this->Flash->success('Received confirmation saved.');
        } else {
            $this->Flash->error('Unable to confirm received item.');
        }

        return $this->redirect(['action' => 'view', $claim->id]);
    }

    /**
     * Report dispute
     *
     * Claimant, finder, or admin can report dispute.
     *
     * @param string|null $id Claim id.
     * @return \Cake\Http\Response|null
     */
    public function dispute($id = null)
    {
        $this->request->allowMethod(['post']);

        $claim = $this->Claims->get($id, [
            'contain' => [
                'FoundItems',
                'LostItems'
            ]
        ]);

        if (!$this->isCurrentAdmin() && !$this->isFinder($claim) && !$this->isClaimant($claim)) {
            throw new ForbiddenException('You are not allowed to dispute this claim.');
        }

        $claim->claim_status = 'Disputed';
        $claim->handover_status = 'Disputed';

        if ($this->Claims->save($claim)) {
            $this->Flash->success('This claim has been marked as disputed. Admin can review it.');
        } else {
            $this->Flash->error('Unable to mark claim as disputed.');
        }

        return $this->redirect(['action' => 'view', $claim->id]);
    }
}