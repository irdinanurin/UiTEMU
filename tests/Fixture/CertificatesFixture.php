<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CertificatesFixture
 */
class CertificatesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'certificate_no' => 'Lorem ipsum dolor sit amet',
                'issue_date' => '2026-06-13',
                'created_at' => 1781344680,
                'updated_at' => 1781344680,
            ],
        ];
        parent::init();
    }
}
