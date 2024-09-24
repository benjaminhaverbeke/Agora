<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924123505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sujet DROP has_vote');
        $this->addSql('ALTER TABLE user ADD voted_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499EC6B0EE FOREIGN KEY (voted_id) REFERENCES proposal (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6499EC6B0EE ON user (voted_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sujet ADD has_vote TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499EC6B0EE');
        $this->addSql('DROP INDEX IDX_8D93D6499EC6B0EE ON user');
        $this->addSql('ALTER TABLE user DROP voted_id');
    }
}
