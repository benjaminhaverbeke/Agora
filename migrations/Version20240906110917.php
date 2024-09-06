<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240906110917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A76ED395');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8FA76ED395');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE salons DROP FOREIGN KEY FK_982F2238A76ED395');
        $this->addSql('ALTER TABLE salons ADD CONSTRAINT FK_982F2238A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33ABA76ED395');
        $this->addSql('ALTER TABLE sujets ADD CONSTRAINT FK_959E33ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A76ED395');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE salons DROP FOREIGN KEY FK_982F2238A76ED395');
        $this->addSql('ALTER TABLE salons ADD CONSTRAINT FK_982F2238A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8FA76ED395');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33ABA76ED395');
        $this->addSql('ALTER TABLE sujets ADD CONSTRAINT FK_959E33ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
