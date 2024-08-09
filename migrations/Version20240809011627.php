<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240809011627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Customers table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('customers');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('first_name', 'string', ['length' => 255]);
        $table->addColumn('last_name', 'string', ['length' => 255]);
        $table->addColumn('email', 'string', ['length' => 255]);
        $table->addColumn('gender', 'string', ['length' => 255]);
        $table->addColumn('picture', 'string', ['length' => 255]);
        $table->addColumn('nationality', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('customers');
    }
}
?>