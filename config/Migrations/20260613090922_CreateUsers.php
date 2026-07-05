<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
  public function change(): void
{
    $table = $this->table('users');

    $table
        ->addColumn('name', 'string')
        ->addColumn('email', 'string')
        ->addColumn('password', 'string')
        ->addColumn('role', 'string', [
            'default' => 'user'
        ])
        ->addTimestamps()
        ->create();
}

} 

