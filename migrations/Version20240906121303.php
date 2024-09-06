<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240906121303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E964C91BDE4');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E964C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id)');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F4C91BDE4');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F7C4D497E');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id)');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id)');
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33AB4C91BDE4');
        $this->addSql('ALTER TABLE sujets ADD CONSTRAINT FK_959E33AB4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id)');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF7C4D497E');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFF4792058');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFF4792058 FOREIGN KEY (proposal_id) REFERENCES proposals (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F4C91BDE4');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F7C4D497E');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E964C91BDE4');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E964C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sujets DROP FOREIGN KEY FK_959E33AB4C91BDE4');
        $this->addSql('ALTER TABLE sujets ADD CONSTRAINT FK_959E33AB4C91BDE4 FOREIGN KEY (salon_id) REFERENCES salons (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFF4792058');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF7C4D497E');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFF4792058 FOREIGN KEY (proposal_id) REFERENCES proposals (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujets (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
