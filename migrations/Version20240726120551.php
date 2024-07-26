<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240726120551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE salons_user (salons_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C155336F405A0AF9 (salons_id), INDEX IDX_C155336FA76ED395 (user_id), PRIMARY KEY(salons_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE salons_user ADD CONSTRAINT FK_C155336F405A0AF9 FOREIGN KEY (salons_id) REFERENCES salons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE salons_user ADD CONSTRAINT FK_C155336FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salons_user DROP FOREIGN KEY FK_C155336F405A0AF9');
        $this->addSql('ALTER TABLE salons_user DROP FOREIGN KEY FK_C155336FA76ED395');
        $this->addSql('DROP TABLE salons_user');
    }
}
