<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClaimsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClaimsTable Test Case
 */
class ClaimsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClaimsTable
     */
    protected $Claims;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Claims',
        'app.Users',
        'app.FoundItems',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Claims') ? [] : ['className' => ClaimsTable::class];
        $this->Claims = $this->getTableLocator()->get('Claims', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Claims);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ClaimsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ClaimsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
