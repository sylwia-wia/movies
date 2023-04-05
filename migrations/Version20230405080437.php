<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405080437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE movie ADD slug VARCHAR(255)');
        $this->addSql("UPDATE movie SET slug=CONCAT(LOWER(title), '-', release_year) WHERE slug IS NULL");
        $this->addSql("ALTER TABLE movie MODIFY slug VARCHAR(255) NOT NULL");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
