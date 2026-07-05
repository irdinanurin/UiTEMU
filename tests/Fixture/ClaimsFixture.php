<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ClaimsFixture
 */
class ClaimsFixture extends TestFixture
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
                'found_item_id' => 1,
                'claim_status' => 'Lorem ipsum dolor sit amet',
                'created_at' => 1781344679,
                'updated_at' => 1781344679,
            ],
        ];
        parent::init();
    }
}
