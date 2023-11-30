<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115213449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add cin column to user table and foreign key to reset_password_request';
    }

    public function up(Schema $schema): void
    {
        // Add cin column to user table
        $this->addSql('ALTER TABLE user ADD cin VARCHAR(8) DEFAULT NULL');

        // Add foreign key constraint to reset_password_request
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // Drop foreign key constraint from reset_password_request
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');

        // Remove cin column from user table
        $this->addSql('ALTER TABLE user DROP COLUMN cin');
    }
}
