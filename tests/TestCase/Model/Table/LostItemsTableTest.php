<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LostItemsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LostItemsTable Test Case
 */
class LostItemsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LostItemsTable
     */
    protected $LostItems;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.LostItems',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('LostItems') ? [] : ['className' => LostItemsTable::class];
        $this->LostItems = $this->getTableLocator()->get('LostItems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->LostItems);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\LostItemsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\LostItemsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
