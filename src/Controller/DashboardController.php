<?php
declare(strict_types=1);

namespace App\Controller;

use CakePdf\Pdf\CakePdf;
use Cake\I18n\FrozenTime;

class DashboardController extends AppController
{
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

        return $sessionUser['id'] ?? null;
    }

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

        return $sessionUser['role'] ?? null;
    }

    private function getCurrentUserName()
    {
        $identity = $this->request->getAttribute('identity');

        if ($identity) {
            if (method_exists($identity, 'get')) {
                return $identity->get('name');
            }

            return $identity->name ?? 'User';
        }

        $sessionUser = $this->request->getSession()->read('Auth.User');

        return $sessionUser['name'] ?? 'User';
    }

    public function index()
    {
        $user = $this->request->getAttribute('identity');

        $userId = $this->getCurrentUserId();
        $role = $this->getCurrentUserRole();
        $userName = $this->getCurrentUserName();
        $isAdmin = $role === 'admin';

        $usersTable = $this->fetchTable('Users');
        $lostItemsTable = $this->fetchTable('LostItems');
        $foundItemsTable = $this->fetchTable('FoundItems');
        $claimsTable = $this->fetchTable('Claims');
        $certificatesTable = $this->fetchTable('Certificates');

        if ($isAdmin) {
            $totalUsers = $usersTable->find()->count();
            $totalLostItems = $lostItemsTable->find()->count();
            $totalFoundItems = $foundItemsTable->find()->count();
            $totalClaims = $claimsTable->find()->count();
            $totalCertificates = $certificatesTable->find()->count();

            $pendingClaims = $claimsTable->find()
                ->where(['Claims.claim_status' => 'Pending'])
                ->count();

            $approvedClaims = $claimsTable->find()
                ->where(['Claims.claim_status' => 'Approved'])
                ->count();

            $availableFoundItems = $foundItemsTable->find()
                ->where(['FoundItems.status' => 'Available'])
                ->count();

            $claimedFoundItems = $foundItemsTable->find()
                ->where(['FoundItems.status' => 'Claimed'])
                ->count();

            $dashboardClaims = $claimsTable->find()
                ->contain(['Users', 'FoundItems', 'LostItems'])
                ->where(['Claims.claim_status' => 'Pending'])
                ->order(['Claims.created_at' => 'DESC'])
                ->limit(6)
                ->all();

            $recentLostItems = $lostItemsTable->find()
                ->contain(['Users'])
                ->order(['LostItems.created_at' => 'DESC'])
                ->limit(5)
                ->all();

            $recentFoundItems = $foundItemsTable->find()
                ->contain(['Users'])
                ->order(['FoundItems.created_at' => 'DESC'])
                ->limit(5)
                ->all();

            $overviewLabels = ['Users', 'Lost Items', 'Found Items', 'Claims', 'Certificates'];
            $overviewCounts = [
                $totalUsers,
                $totalLostItems,
                $totalFoundItems,
                $totalClaims,
                $totalCertificates
            ];
        } else {
            $totalUsers = $usersTable->find()->count();

            $totalLostItems = $lostItemsTable->find()
                ->where(['LostItems.user_id' => $userId])
                ->count();

            $totalFoundItems = $foundItemsTable->find()
                ->where(['FoundItems.user_id' => $userId])
                ->count();

            $totalClaims = $claimsTable->find()
                ->where(['Claims.user_id' => $userId])
                ->count();

            $totalCertificates = $certificatesTable->find()
                ->where(['Certificates.user_id' => $userId])
                ->count();

            $pendingClaims = $claimsTable->find()
                ->where([
                    'Claims.user_id' => $userId,
                    'Claims.claim_status' => 'Pending'
                ])
                ->count();

            $approvedClaims = $claimsTable->find()
                ->where([
                    'Claims.user_id' => $userId,
                    'Claims.claim_status' => 'Approved'
                ])
                ->count();

            $availableFoundItems = $foundItemsTable->find()
                ->where(['FoundItems.status' => 'Available'])
                ->count();

            $claimedFoundItems = $foundItemsTable->find()
                ->where([
                    'FoundItems.user_id' => $userId,
                    'FoundItems.status' => 'Claimed'
                ])
                ->count();

            $dashboardClaims = $claimsTable->find()
                ->contain(['Users', 'FoundItems', 'LostItems'])
                ->where(['Claims.user_id' => $userId])
                ->order(['Claims.created_at' => 'DESC'])
                ->limit(6)
                ->all();

            $recentLostItems = $lostItemsTable->find()
                ->contain(['Users'])
                ->where(['LostItems.user_id' => $userId])
                ->order(['LostItems.created_at' => 'DESC'])
                ->limit(5)
                ->all();

            $recentFoundItems = $foundItemsTable->find()
                ->contain(['Users'])
                ->where(['FoundItems.user_id' => $userId])
                ->order(['FoundItems.created_at' => 'DESC'])
                ->limit(5)
                ->all();

            $overviewLabels = ['My Lost Reports', 'My Found Reports', 'My Claims', 'My Certificates'];
            $overviewCounts = [
                $totalLostItems,
                $totalFoundItems,
                $totalClaims,
                $totalCertificates
            ];
        }

        $leaderboardQuery = $usersTable->find();

        $topFinders = $leaderboardQuery
            ->select([
                'id' => 'Users.id',
                'name' => 'Users.name',
                'email' => 'Users.email',
                'found_count' => $leaderboardQuery->func()->count('FoundItems.id')
            ])
            ->leftJoinWith('FoundItems')
            ->group(['Users.id', 'Users.name', 'Users.email'])
            ->order(['found_count' => 'DESC'])
            ->limit(10)
            ->enableAutoFields(false)
            ->all();

        $categoryQuery = $foundItemsTable->find();

        if (!$isAdmin) {
            $categoryQuery->where(['FoundItems.user_id' => $userId]);
        }

        $categoryQuery
            ->select([
                'category' => 'FoundItems.category',
                'count' => $categoryQuery->func()->count('*')
            ])
            ->group(['FoundItems.category'])
            ->order(['count' => 'DESC']);

        $categoryLabels = [];
        $categoryCounts = [];

        foreach ($categoryQuery->all() as $row) {
            $categoryLabels[] = $row->category ?: 'Uncategorized';
            $categoryCounts[] = (int)$row->count;
        }

        if (empty($categoryLabels)) {
            $categoryLabels = ['No Data'];
            $categoryCounts = [0];
        }

        $claimStatusQuery = $claimsTable->find();

        if (!$isAdmin) {
            $claimStatusQuery->where(['Claims.user_id' => $userId]);
        }

        $claimStatusQuery
            ->select([
                'claim_status' => 'Claims.claim_status',
                'count' => $claimStatusQuery->func()->count('*')
            ])
            ->group(['Claims.claim_status'])
            ->order(['count' => 'DESC']);

        $claimStatusLabels = [];
        $claimStatusCounts = [];

        foreach ($claimStatusQuery->all() as $row) {
            $claimStatusLabels[] = $row->claim_status ?: 'Unknown';
            $claimStatusCounts[] = (int)$row->count;
        }

        if (empty($claimStatusLabels)) {
            $claimStatusLabels = ['No Data'];
            $claimStatusCounts = [0];
        }

        $foundStatusQuery = $foundItemsTable->find();

        if (!$isAdmin) {
            $foundStatusQuery->where(['FoundItems.user_id' => $userId]);
        }

        $foundStatusQuery
            ->select([
                'status' => 'FoundItems.status',
                'count' => $foundStatusQuery->func()->count('*')
            ])
            ->group(['FoundItems.status'])
            ->order(['count' => 'DESC']);

        $foundStatusLabels = [];
        $foundStatusCounts = [];

        foreach ($foundStatusQuery->all() as $row) {
            $foundStatusLabels[] = $row->status ?: 'Unknown';
            $foundStatusCounts[] = (int)$row->count;
        }

        if (empty($foundStatusLabels)) {
            $foundStatusLabels = ['No Data'];
            $foundStatusCounts = [0];
        }

        $chartYear = (int)date('Y');
        $monthlyLabels = [];
        $monthlyLostCounts = [];
        $monthlyFoundCounts = [];
        $monthlyClaimsCounts = [];
        $monthlyCertificateCounts = [];

        for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++) {
            $monthStart = FrozenTime::parse(sprintf('%04d-%02d-01 00:00:00', $chartYear, $monthNumber));
            $monthEnd = $monthStart->addMonth();

            $monthlyLabels[] = $monthStart->format('M');

            $lostQuery = $lostItemsTable->find();
            $foundQuery = $foundItemsTable->find();
            $claimsQuery = $claimsTable->find();
            $certificatesQuery = $certificatesTable->find();

            if (!$isAdmin) {
                $lostQuery->where(['LostItems.user_id' => $userId]);
                $foundQuery->where(['FoundItems.user_id' => $userId]);
                $claimsQuery->where(['Claims.user_id' => $userId]);
                $certificatesQuery->where(['Certificates.user_id' => $userId]);
            }

            $monthlyLostCounts[] = (int)$lostQuery
                ->where([
                    'LostItems.created_at >=' => $monthStart->format('Y-m-d H:i:s'),
                    'LostItems.created_at <' => $monthEnd->format('Y-m-d H:i:s')
                ])
                ->count();

            $monthlyFoundCounts[] = (int)$foundQuery
                ->where([
                    'FoundItems.created_at >=' => $monthStart->format('Y-m-d H:i:s'),
                    'FoundItems.created_at <' => $monthEnd->format('Y-m-d H:i:s')
                ])
                ->count();

            $monthlyClaimsCounts[] = (int)$claimsQuery
                ->where([
                    'Claims.created_at >=' => $monthStart->format('Y-m-d H:i:s'),
                    'Claims.created_at <' => $monthEnd->format('Y-m-d H:i:s')
                ])
                ->count();

            $monthlyCertificateCounts[] = (int)$certificatesQuery
                ->where([
                    'Certificates.created_at >=' => $monthStart->format('Y-m-d H:i:s'),
                    'Certificates.created_at <' => $monthEnd->format('Y-m-d H:i:s')
                ])
                ->count();
        }

        $this->set(compact(
            'user',
            'userId',
            'role',
            'userName',
            'isAdmin',
            'totalUsers',
            'totalLostItems',
            'totalFoundItems',
            'totalClaims',
            'totalCertificates',
            'pendingClaims',
            'approvedClaims',
            'availableFoundItems',
            'claimedFoundItems',
            'dashboardClaims',
            'recentLostItems',
            'recentFoundItems',
            'topFinders',
            'overviewLabels',
            'overviewCounts',
            'categoryLabels',
            'categoryCounts',
            'claimStatusLabels',
            'claimStatusCounts',
            'foundStatusLabels',
            'foundStatusCounts',
            'monthlyLabels',
            'monthlyLostCounts',
            'monthlyFoundCounts',
            'monthlyClaimsCounts',
            'monthlyCertificateCounts'
        ));
    }

    public function monthlyReport()
    {
        $role = $this->getCurrentUserRole();

        if ($role !== 'admin') {
            $this->Flash->error('Only admin can view monthly reports.');

            return $this->redirect(['action' => 'index']);
        }

        $selectedYear = (int)($this->request->getQuery('year') ?: date('Y'));
        $selectedMonth = (int)($this->request->getQuery('month') ?: date('m'));

        if ($selectedMonth < 1 || $selectedMonth > 12) {
            $selectedMonth = (int)date('m');
        }

        $start = FrozenTime::parse(sprintf('%04d-%02d-01 00:00:00', $selectedYear, $selectedMonth));
        $end = $start->addMonth();

        $lostItemsTable = $this->fetchTable('LostItems');
        $foundItemsTable = $this->fetchTable('FoundItems');
        $claimsTable = $this->fetchTable('Claims');
        $certificatesTable = $this->fetchTable('Certificates');

        $selectedLostItems = $lostItemsTable->find()
            ->contain(['Users'])
            ->where([
                'LostItems.created_at >=' => $start->format('Y-m-d H:i:s'),
                'LostItems.created_at <' => $end->format('Y-m-d H:i:s')
            ])
            ->order(['LostItems.created_at' => 'DESC'])
            ->all();

        $selectedFoundItems = $foundItemsTable->find()
            ->contain(['Users'])
            ->where([
                'FoundItems.created_at >=' => $start->format('Y-m-d H:i:s'),
                'FoundItems.created_at <' => $end->format('Y-m-d H:i:s')
            ])
            ->order(['FoundItems.created_at' => 'DESC'])
            ->all();

        $selectedClaims = $claimsTable->find()
            ->contain(['Users', 'FoundItems', 'LostItems'])
            ->where([
                'Claims.created_at >=' => $start->format('Y-m-d H:i:s'),
                'Claims.created_at <' => $end->format('Y-m-d H:i:s')
            ])
            ->order(['Claims.created_at' => 'DESC'])
            ->all();

        $selectedCertificates = $certificatesTable->find()
            ->contain(['Users', 'FoundItems'])
            ->where([
                'Certificates.created_at >=' => $start->format('Y-m-d H:i:s'),
                'Certificates.created_at <' => $end->format('Y-m-d H:i:s')
            ])
            ->order(['Certificates.created_at' => 'DESC'])
            ->all();

        $lostCount = $selectedLostItems->count();
        $foundCount = $selectedFoundItems->count();
        $claimsCount = $selectedClaims->count();
        $certificatesCount = $selectedCertificates->count();

        $chartLabels = [];
        $monthlyLostCounts = [];
        $monthlyFoundCounts = [];
        $monthlyClaimsCounts = [];
        $monthlyCertificateCounts = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthStart = FrozenTime::parse(sprintf('%04d-%02d-01 00:00:00', $selectedYear, $m));
            $monthEnd = $monthStart->addMonth();

            $chartLabels[] = $monthStart->format('M');

            $monthlyLostCounts[] = $lostItemsTable->find()
                ->where([
                    'LostItems.created_at >=' => $monthStart->format('Y-m-d H:i:s'),
                    'LostItems.created_at <' => $monthEnd->format('Y-m-d H:i:s')
                ])
                ->count();

            $monthlyFoundCounts[] = $foundItemsTable->find()
                ->where([
                    'FoundItems.created_at >=' => $monthStart->format('Y-m-d H:i:s'),
                    'FoundItems.created_at <' => $monthEnd->format('Y-m-d H:i:s')
                ])
                ->count();

            $monthlyClaimsCounts[] = $claimsTable->find()
                ->where([
                    'Claims.created_at >=' => $monthStart->format('Y-m-d H:i:s'),
                    'Claims.created_at <' => $monthEnd->format('Y-m-d H:i:s')
                ])
                ->count();

            $monthlyCertificateCounts[] = $certificatesTable->find()
                ->where([
                    'Certificates.created_at >=' => $monthStart->format('Y-m-d H:i:s'),
                    'Certificates.created_at <' => $monthEnd->format('Y-m-d H:i:s')
                ])
                ->count();
        }

        $monthName = $start->format('F');

        $this->set(compact(
            'selectedYear',
            'selectedMonth',
            'monthName',
            'selectedLostItems',
            'selectedFoundItems',
            'selectedClaims',
            'selectedCertificates',
            'lostCount',
            'foundCount',
            'claimsCount',
            'certificatesCount',
            'chartLabels',
            'monthlyLostCounts',
            'monthlyFoundCounts',
            'monthlyClaimsCounts',
            'monthlyCertificateCounts'
        ));
    }

    public function downloadReport()
    {
        $userId = $this->getCurrentUserId();
        $role = $this->getCurrentUserRole();
        $isAdmin = $role === 'admin';

        $usersTable = $this->fetchTable('Users');
        $lostItemsTable = $this->fetchTable('LostItems');
        $foundItemsTable = $this->fetchTable('FoundItems');
        $claimsTable = $this->fetchTable('Claims');
        $certificatesTable = $this->fetchTable('Certificates');

        if ($isAdmin) {
            $totalUsers = $usersTable->find()->count();
            $totalLostItems = $lostItemsTable->find()->count();
            $totalFoundItems = $foundItemsTable->find()->count();
            $totalClaims = $claimsTable->find()->count();
            $totalCertificates = $certificatesTable->find()->count();

            $overviewLabels = ['Users', 'Lost Items', 'Found Items', 'Claims', 'Certificates'];
            $overviewCounts = [
                $totalUsers,
                $totalLostItems,
                $totalFoundItems,
                $totalClaims,
                $totalCertificates,
            ];
        } else {
            $totalUsers = $usersTable->find()->count();

            $totalLostItems = $lostItemsTable->find()
                ->where(['LostItems.user_id' => $userId])
                ->count();

            $totalFoundItems = $foundItemsTable->find()
                ->where(['FoundItems.user_id' => $userId])
                ->count();

            $totalClaims = $claimsTable->find()
                ->where(['Claims.user_id' => $userId])
                ->count();

            $totalCertificates = $certificatesTable->find()
                ->where(['Certificates.user_id' => $userId])
                ->count();

            $overviewLabels = ['My Lost Reports', 'My Found Reports', 'My Claims', 'My Certificates'];
            $overviewCounts = [
                $totalLostItems,
                $totalFoundItems,
                $totalClaims,
                $totalCertificates,
            ];
        }

        $categoryQuery = $foundItemsTable->find();

        if (!$isAdmin) {
            $categoryQuery->where(['FoundItems.user_id' => $userId]);
        }

        $categoryQuery
            ->select([
                'category' => 'FoundItems.category',
                'count' => $categoryQuery->func()->count('*')
            ])
            ->group(['FoundItems.category'])
            ->order(['count' => 'DESC']);

        $categoryLabels = [];
        $categoryCounts = [];

        foreach ($categoryQuery->all() as $row) {
            $categoryLabels[] = $row->category ?: 'Uncategorized';
            $categoryCounts[] = (int)$row->count;
        }

        if (empty($categoryLabels)) {
            $categoryLabels = ['No Data'];
            $categoryCounts = [0];
        }

        $claimStatusQuery = $claimsTable->find();

        if (!$isAdmin) {
            $claimStatusQuery->where(['Claims.user_id' => $userId]);
        }

        $claimStatusQuery
            ->select([
                'claim_status' => 'Claims.claim_status',
                'count' => $claimStatusQuery->func()->count('*')
            ])
            ->group(['Claims.claim_status'])
            ->order(['count' => 'DESC']);

        $claimStatusLabels = [];
        $claimStatusCounts = [];

        foreach ($claimStatusQuery->all() as $row) {
            $claimStatusLabels[] = $row->claim_status ?: 'Unknown';
            $claimStatusCounts[] = (int)$row->count;
        }

        if (empty($claimStatusLabels)) {
            $claimStatusLabels = ['No Data'];
            $claimStatusCounts = [0];
        }

        $foundStatusQuery = $foundItemsTable->find();

        if (!$isAdmin) {
            $foundStatusQuery->where(['FoundItems.user_id' => $userId]);
        }

        $foundStatusQuery
            ->select([
                'status' => 'FoundItems.status',
                'count' => $foundStatusQuery->func()->count('*')
            ])
            ->group(['FoundItems.status'])
            ->order(['count' => 'DESC']);

        $foundStatusLabels = [];
        $foundStatusCounts = [];

        foreach ($foundStatusQuery->all() as $row) {
            $foundStatusLabels[] = $row->status ?: 'Unknown';
            $foundStatusCounts[] = (int)$row->count;
        }

        if (empty($foundStatusLabels)) {
            $foundStatusLabels = ['No Data'];
            $foundStatusCounts = [0];
        }

        $monthlyLostQuery = $lostItemsTable->find();
        $monthlyFoundQuery = $foundItemsTable->find();

        if (!$isAdmin) {
            $monthlyLostQuery->where(['LostItems.user_id' => $userId]);
            $monthlyFoundQuery->where(['FoundItems.user_id' => $userId]);
        }

        $monthlyLostQuery
            ->select([
                'month_label' => $monthlyLostQuery->func()->date_format([
                    'LostItems.created_at' => 'identifier',
                    "'%Y-%m'" => 'literal'
                ]),
                'count' => $monthlyLostQuery->func()->count('*')
            ])
            ->group(['month_label'])
            ->order(['month_label' => 'ASC'])
            ->enableAutoFields(false);

        $monthlyFoundQuery
            ->select([
                'month_label' => $monthlyFoundQuery->func()->date_format([
                    'FoundItems.created_at' => 'identifier',
                    "'%Y-%m'" => 'literal'
                ]),
                'count' => $monthlyFoundQuery->func()->count('*')
            ])
            ->group(['month_label'])
            ->order(['month_label' => 'ASC'])
            ->enableAutoFields(false);

        $monthlyTotals = [];

        foreach ($monthlyLostQuery->all() as $row) {
            $label = $row->month_label;
            $monthlyTotals[$label]['lost'] = (int)$row->count;
            $monthlyTotals[$label]['found'] = $monthlyTotals[$label]['found'] ?? 0;
        }

        foreach ($monthlyFoundQuery->all() as $row) {
            $label = $row->month_label;
            $monthlyTotals[$label]['found'] = (int)$row->count;
            $monthlyTotals[$label]['lost'] = $monthlyTotals[$label]['lost'] ?? 0;
        }

        if (empty($monthlyTotals)) {
            $monthlyTotals['No Data'] = ['lost' => 0, 'found' => 0];
        }

        $monthlyLabels = [];
        $monthlyLostCounts = [];
        $monthlyFoundCounts = [];

        foreach ($monthlyTotals as $label => $values) {
            $monthlyLabels[] = $label;
            $monthlyLostCounts[] = $values['lost'];
            $monthlyFoundCounts[] = $values['found'];
        }

        $reportTitle = $isAdmin ? 'UiTEMU Monthly Activity Report' : 'My UiTEMU Monthly Activity Report';

        $cakePdf = new CakePdf([
            'engine' => [
                'className' => 'CakePdf.DomPdf',
                'options' => [
                    'isRemoteEnabled' => true,
                ],
            ],
            'pageSize' => 'A4',
            'orientation' => 'portrait',
            'margin' => [
                'bottom' => 12,
                'left' => 12,
                'right' => 12,
                'top' => 12,
            ],
        ]);

        $cakePdf->template('report', 'pdf');
        $cakePdf->viewVars([
            'reportTitle' => $reportTitle,
            'isAdmin' => $isAdmin,
            'overviewLabels' => $overviewLabels,
            'overviewCounts' => $overviewCounts,
            'categoryLabels' => $categoryLabels,
            'categoryCounts' => $categoryCounts,
            'claimStatusLabels' => $claimStatusLabels,
            'claimStatusCounts' => $claimStatusCounts,
            'foundStatusLabels' => $foundStatusLabels,
            'foundStatusCounts' => $foundStatusCounts,
            'monthlyLabels' => $monthlyLabels,
            'monthlyLostCounts' => $monthlyLostCounts,
            'monthlyFoundCounts' => $monthlyFoundCounts,
            'monthlyTotals' => $monthlyTotals,
        ]);

        $pdf = $cakePdf->output();

        $filename = 'uitemu-monthly-report.pdf';

        return $this->response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->withStringBody($pdf);
    }
}