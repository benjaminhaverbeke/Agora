<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240712103901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salons ADD CONSTRAINT FK_982F2238A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_982F2238A76ED395 ON salons (user_id)');
        $this->addSql('ALTER TABLE sujets ADD user_id INT NOT NULL, CHANGE salon_id salon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sujets ADD CONSTRAINT FK_959E33AB4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id)');
        $this->addSql('ALTER TABLE sujets ADD CONSTRAINT FK_959E33ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_959E33AB4C91BDE4 ON sujets (salon_id)');
        $this->addSql('CREATE INDEX IDX_959E33ABA76ED395 ON sujets (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33AB4C91BDE4');
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33ABA76ED395');
        $this->addSql('DROP INDEX IDX_959E33AB4C91BDE4 ON sujets');
        $this->addSql('DROP INDEX IDX_959E33ABA76ED395 ON sujets');
        $this->addSql('ALTER TABLE sujets DROP user_id, CHANGE salon_id salon_id INT NOT NULL');
        $this->addSql('ALTER TABLE salons DROP FOREIGN KEY FK_982F2238A76ED395');
        $this->addSql('DROP INDEX IDX_982F2238A76ED395 ON salons');
    }
}
