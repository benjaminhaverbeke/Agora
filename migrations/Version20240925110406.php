<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925110406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sujet_user (sujet_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5A4211DE7C4D497E (sujet_id), INDEX IDX_5A4211DEA76ED395 (user_id), PRIMARY KEY(sujet_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sujet_user ADD CONSTRAINT FK_5A4211DE7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sujet_user ADD CONSTRAINT FK_5A4211DEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sujet_user DROP FOREIGN KEY FK_5A4211DE7C4D497E');
        $this->addSql('ALTER TABLE sujet_user DROP FOREIGN KEY FK_5A4211DEA76ED395');
        $this->addSql('DROP TABLE sujet_user');
    }
}
