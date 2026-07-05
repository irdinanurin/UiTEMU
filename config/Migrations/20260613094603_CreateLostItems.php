<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLostItems extends AbstractMigration
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
    $table = $this->table('lost_items');

    $table
        ->addColumn('user_id', 'integer')
        ->addColumn('item_name', 'string')
        ->addColumn('category', 'string')
        ->addColumn('description', 'text')
        ->addColumn('location', 'string')
        ->addColumn('date_lost', 'date')
        ->addColumn('status', 'string', [
            'default' => 'Pending'
        ])
        ->addTimestamps()
        ->create();
}
}
