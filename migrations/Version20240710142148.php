<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710142148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE votes CHANGE proposal_id proposal_id INT DEFAULT NULL, CHANGE sujet_id sujet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFF4792058 FOREIGN KEY (proposal_id) REFERENCES proposals (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id)');
        $this->addSql('CREATE INDEX IDX_518B7ACFF4792058 ON votes (proposal_id)');
        $this->addSql('CREATE INDEX IDX_518B7ACF7C4D497E ON votes (sujet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFF4792058');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF7C4D497E');
        $this->addSql('DROP INDEX IDX_518B7ACFF4792058 ON votes');
        $this->addSql('DROP INDEX IDX_518B7ACF7C4D497E ON votes');
        $this->addSql('ALTER TABLE votes CHANGE proposal_id proposal_id INT NOT NULL, CHANGE sujet_id sujet_id INT NOT NULL');
    }
}
