<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FoundItemsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FoundItemsTable Test Case
 */
class FoundItemsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FoundItemsTable
     */
    protected $FoundItems;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.FoundItems',
        'app.Users',
        'app.Claims',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FoundItems') ? [] : ['className' => FoundItemsTable::class];
        $this->FoundItems = $this->getTableLocator()->get('FoundItems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FoundItems);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FoundItemsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\FoundItemsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
