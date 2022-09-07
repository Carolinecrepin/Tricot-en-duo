<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825132637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP color1, DROP color2, DROP color3, DROP material1, DROP material2, DROP material3, DROP level, DROP size_needle');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD color1 VARCHAR(255) NOT NULL, ADD color2 VARCHAR(255) DEFAULT NULL, ADD color3 VARCHAR(255) DEFAULT NULL, ADD material1 VARCHAR(255) NOT NULL, ADD material2 VARCHAR(255) DEFAULT NULL, ADD material3 VARCHAR(255) DEFAULT NULL, ADD level VARCHAR(255) NOT NULL, ADD size_needle DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE user DROP created_at');
    }
}
