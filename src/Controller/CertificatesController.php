<?php
declare(strict_types=1);

namespace App\Controller;

use CakePdf\Pdf\CakePdf;

/**
 * Certificates Controller
 *
 * @property \App\Model\Table\CertificatesTable $Certificates
 * @method \App\Model\Entity\Certificate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CertificatesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'FoundItems'],
        ];

        $certificates = $this->paginate($this->Certificates);

        $this->set(compact('certificates'));
    }

    /**
     * View method
     *
     * @param string|null $id Certificate id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function view($id = null)
    {
        $certificate = $this->Certificates->get($id, [
            'contain' => ['Users', 'FoundItems'],
        ]);

        $this->set(compact('certificate'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $certificate = $this->Certificates->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (empty($data['certificate_no'])) {
                $data['certificate_no'] = 'CERT-' . date('YmdHis');
            }

            if (empty($data['title'])) {
                $data['title'] = 'Certificate of Appreciation';
            }

            if (empty($data['description'])) {
                $data['description'] = 'For demonstrating honesty and responsibility by reporting a found item through UiTEMU.';
            }

            if (empty($data['issue_date'])) {
                $data['issue_date'] = date('Y-m-d');
            }

            if (empty($data['status'])) {
                $data['status'] = 'Issued';
            }

            $certificate = $this->Certificates->patchEntity($certificate, $data);

            if ($this->Certificates->save($certificate)) {
                $this->Flash->success(__('The certificate has been saved.'));

                return $this->redirect(['action' => 'view', $certificate->id]);
            }

            $this->Flash->error(__('The certificate could not be saved. Please, try again.'));
        }

        $users = $this->Certificates->Users->find('list', ['limit' => 200])->all();
        $foundItems = $this->Certificates->FoundItems->find('list', ['limit' => 200])->all();

        $this->set(compact('certificate', 'users', 'foundItems'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Certificate id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     */
    public function edit($id = null)
    {
        $certificate = $this->Certificates->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (empty($data['title'])) {
                $data['title'] = 'Certificate of Appreciation';
            }

            if (empty($data['status'])) {
                $data['status'] = 'Issued';
            }

            $certificate = $this->Certificates->patchEntity($certificate, $data);

            if ($this->Certificates->save($certificate)) {
                $this->Flash->success(__('The certificate has been saved.'));

                return $this->redirect(['action' => 'view', $certificate->id]);
            }

            $this->Flash->error(__('The certificate could not be saved. Please, try again.'));
        }

        $users = $this->Certificates->Users->find('list', ['limit' => 200])->all();
        $foundItems = $this->Certificates->FoundItems->find('list', ['limit' => 200])->all();

        $this->set(compact('certificate', 'users', 'foundItems'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Certificate id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $certificate = $this->Certificates->get($id);

        if ($this->Certificates->delete($certificate)) {
            $this->Flash->success(__('The certificate has been deleted.'));
        } else {
            $this->Flash->error(__('The certificate could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Issue reward certificate for found item reporter
     *
     * @param string|null $foundItemId Found item id.
     * @return \Cake\Http\Response|null
     */
    public function issue($foundItemId = null)
    {
        $this->request->allowMethod(['post']);

        $foundItem = $this->Certificates->FoundItems->get($foundItemId, [
            'contain' => ['Users']
        ]);

        if (empty($foundItem->user_id)) {
            $this->Flash->error('Cannot issue certificate because this found item has no reporter/user.');

            return $this->redirect([
                'controller' => 'FoundItems',
                'action' => 'view',
                $foundItemId
            ]);
        }

        $existingCertificate = $this->Certificates->find()
            ->where(['found_item_id' => $foundItemId])
            ->first();

        if ($existingCertificate) {
            $this->Flash->success('Certificate already exists for this found item.');

            return $this->redirect([
                'controller' => 'Certificates',
                'action' => 'view',
                $existingCertificate->id
            ]);
        }

        $certificate = $this->Certificates->newEmptyEntity();

        $data = [
            'user_id' => $foundItem->user_id,
            'found_item_id' => $foundItem->id,
            'certificate_no' => 'CERT-' . date('YmdHis'),
            'title' => 'Certificate of Appreciation',
            'description' => 'For demonstrating honesty and responsibility by reporting a found item through UiTEMU.',
            'issue_date' => date('Y-m-d'),
            'status' => 'Issued',
        ];

        $certificate = $this->Certificates->patchEntity($certificate, $data);

        if ($this->Certificates->save($certificate)) {
            $this->Flash->success('Reward certificate has been issued.');

            return $this->redirect([
                'controller' => 'Certificates',
                'action' => 'view',
                $certificate->id
            ]);
        }

        $this->Flash->error('Unable to issue certificate.');

        return $this->redirect([
            'controller' => 'FoundItems',
            'action' => 'view',
            $foundItemId
        ]);
    }

    /**
     * Download certificate as PDF
     *
     * @param string|null $id Certificate id.
     * @return \Cake\Http\Response
     */
    public function downloadPdf($id = null)
    {
        $certificate = $this->Certificates->get($id, [
            'contain' => ['Users', 'FoundItems'],
        ]);

        $cakePdf = new CakePdf([
            'engine' => [
                'className' => 'CakePdf.DomPdf',
                'options' => [
                    'isRemoteEnabled' => true,
                ],
            ],
            'pageSize' => 'A4',
            'orientation' => 'landscape',
            'margin' => [
                'bottom' => 0,
                'left' => 0,
                'right' => 0,
                'top' => 0,
            ],
        ]);

        $cakePdf->template('certificate', 'default');

        $cakePdf->viewVars([
            'certificate' => $certificate,
        ]);

        $pdf = $cakePdf->output();

        $certificateNo = !empty($certificate->certificate_no)
            ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $certificate->certificate_no)
            : $certificate->id;

        $filename = 'certificate-' . $certificateNo . '.pdf';

        return $this->response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->withStringBody($pdf);
    }
}