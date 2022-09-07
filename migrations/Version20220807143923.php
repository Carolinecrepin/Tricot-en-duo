<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220807143923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reviews_product');
        $this->addSql('ALTER TABLE product ADD color1 VARCHAR(255) NOT NULL, ADD color2 VARCHAR(255) DEFAULT NULL, ADD color3 VARCHAR(255) DEFAULT NULL, ADD material1 VARCHAR(255) NOT NULL, ADD material2 VARCHAR(255) DEFAULT NULL, ADD material3 VARCHAR(255) DEFAULT NULL, ADD level VARCHAR(255) NOT NULL, ADD size_needle DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reviews_product (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, note INT NOT NULL, comment LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E0851D6C4584665A (product_id), INDEX IDX_E0851D6CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reviews_product ADD CONSTRAINT FK_E0851D6C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reviews_product ADD CONSTRAINT FK_E0851D6CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE product DROP color1, DROP color2, DROP color3, DROP material1, DROP material2, DROP material3, DROP level, DROP size_needle');
    }
}
