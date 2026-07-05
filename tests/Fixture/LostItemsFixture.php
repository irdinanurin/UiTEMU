<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LostItemsFixture
 */
class LostItemsFixture extends TestFixture
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
                'item_name' => 'Lorem ipsum dolor sit amet',
                'category' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'location' => 'Lorem ipsum dolor sit amet',
                'date_lost' => '2026-06-13',
                'status' => 'Lorem ipsum dolor sit amet',
                'created_at' => 1781344678,
                'updated_at' => 1781344678,
            ],
        ];
        parent::init();
    }
}
