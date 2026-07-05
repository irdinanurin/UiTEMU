<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateClaims extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
{
    $table = $this->table('claims');

    $table
        ->addColumn('user_id', 'integer')
        ->addColumn('found_item_id', 'integer')
        ->addColumn('claim_status', 'string', [
            'default' => 'Pending'
        ])
        ->addTimestamps()
        ->create();
}
}
