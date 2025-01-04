<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103165223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD titre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE article ADD texte TEXT NOT NULL');
        $this->addSql('ALTER TABLE article ADD publie BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE article ADD date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN article.date IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE article DROP titre');
        $this->addSql('ALTER TABLE article DROP texte');
        $this->addSql('ALTER TABLE article DROP publie');
        $this->addSql('ALTER TABLE article DROP date');
    }
}
