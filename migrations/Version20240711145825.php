<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711145825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33ABA76ED395');
        $this->addSql('DROP INDEX IDX_959E33ABA76ED395 ON sujets');
        $this->addSql('ALTER TABLE sujets DROP user_id');
        $this->addSql('ALTER TABLE salons DROP FOREIGN KEY FK_982F2238A76ED395');
        $this->addSql('DROP INDEX IDX_982F2238A76ED395 ON salons');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_USERNAME ON user');
    }
}
