<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905122731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F7C4D497E');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F7C4D497E');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
