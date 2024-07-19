<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719154311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, object VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, salon_id INT DEFAULT NULL, created_at DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_DB021E96A76ED395 (user_id), INDEX IDX_DB021E964C91BDE4 (salon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposals (id INT AUTO_INCREMENT NOT NULL, salon_id INT DEFAULT NULL, sujet_id INT DEFAULT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_A5BA3A8F4C91BDE4 (salon_id), INDEX IDX_A5BA3A8F7C4D497E (sujet_id), INDEX IDX_A5BA3A8FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salon_access (id INT AUTO_INCREMENT NOT NULL, salon_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_560DD7384C91BDE4 (salon_id), INDEX IDX_560DD738A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salons (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, privacy VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_982F2238A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujets (id INT AUTO_INCREMENT NOT NULL, salon_id INT DEFAULT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, campagne_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', vote_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_959E33AB4C91BDE4 (salon_id), INDEX IDX_959E33ABA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, proposal_id INT DEFAULT NULL, sujet_id INT DEFAULT NULL, notes VARCHAR(255) NOT NULL, INDEX IDX_518B7ACFF4792058 (proposal_id), INDEX IDX_518B7ACF7C4D497E (sujet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E964C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id)');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id)');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id)');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE salon_access ADD CONSTRAINT FK_560DD7384C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id)');
        $this->addSql('ALTER TABLE salon_access ADD CONSTRAINT FK_560DD738A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE salons ADD CONSTRAINT FK_982F2238A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sujets ADD CONSTRAINT FK_959E33AB4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id)');
        $this->addSql('ALTER TABLE sujets ADD CONSTRAINT FK_959E33ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFF4792058 FOREIGN KEY (proposal_id) REFERENCES proposals (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96A76ED395');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E964C91BDE4');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F4C91BDE4');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F7C4D497E');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8FA76ED395');
        $this->addSql('ALTER TABLE salon_access DROP FOREIGN KEY FK_560DD7384C91BDE4');
        $this->addSql('ALTER TABLE salon_access DROP FOREIGN KEY FK_560DD738A76ED395');
        $this->addSql('ALTER TABLE salons DROP FOREIGN KEY FK_982F2238A76ED395');
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33AB4C91BDE4');
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33ABA76ED395');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFF4792058');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF7C4D497E');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE proposals');
        $this->addSql('DROP TABLE salon_access');
        $this->addSql('DROP TABLE salons');
        $this->addSql('DROP TABLE sujets');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE votes');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
