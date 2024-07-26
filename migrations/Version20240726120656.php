<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240726120656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salon_access DROP FOREIGN KEY FK_560DD7384C91BDE4');
        $this->addSql('ALTER TABLE salon_access DROP FOREIGN KEY FK_560DD738A76ED395');
        $this->addSql('DROP TABLE salon_access');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE salon_access (id INT AUTO_INCREMENT NOT NULL, salon_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_560DD7384C91BDE4 (salon_id), INDEX IDX_560DD738A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE salon_access ADD CONSTRAINT FK_560DD7384C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE salon_access ADD CONSTRAINT FK_560DD738A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
