<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722090156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salons ADD date_campagne DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD date_vote DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP privacy');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salons ADD privacy VARCHAR(255) NOT NULL, DROP date_campagne, DROP date_vote');
    }
}
