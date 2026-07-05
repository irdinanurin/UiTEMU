<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCertificates extends AbstractMigration
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
    $table = $this->table('certificates');

    $table
        ->addColumn('user_id', 'integer')
        ->addColumn('certificate_no', 'string')
        ->addColumn('issue_date', 'date')
        ->addTimestamps()
        ->create();
}
}
