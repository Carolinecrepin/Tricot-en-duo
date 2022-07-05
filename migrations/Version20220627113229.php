<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627113229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, more_informations LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, is_new_arrival TINYINT(1) DEFAULT NULL, is_special_offer TINYINT(1) DEFAULT NULL, picture1 VARCHAR(255) NOT NULL, picture2 VARCHAR(255) DEFAULT NULL, picture3 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_categories (product_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_A99419434584665A (product_id), INDEX IDX_A9941943A21214B7 (categories_id), PRIMARY KEY(product_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE related_product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, INDEX IDX_EC53CE084584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reviews_product (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, note INT NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_E0851D6CA76ED395 (user_id), INDEX IDX_E0851D6C4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_product_product (tags_product_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_90ACD5EF273D7ABB (tags_product_id), INDEX IDX_90ACD5EF4584665A (product_id), PRIMARY KEY(tags_product_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A99419434584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A9941943A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE related_product ADD CONSTRAINT FK_EC53CE084584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE reviews_product ADD CONSTRAINT FK_E0851D6CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reviews_product ADD CONSTRAINT FK_E0851D6C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE tags_product_product ADD CONSTRAINT FK_90ACD5EF273D7ABB FOREIGN KEY (tags_product_id) REFERENCES tags_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_product_product ADD CONSTRAINT FK_90ACD5EF4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_categories DROP FOREIGN KEY FK_A9941943A21214B7');
        $this->addSql('ALTER TABLE product_categories DROP FOREIGN KEY FK_A99419434584665A');
        $this->addSql('ALTER TABLE related_product DROP FOREIGN KEY FK_EC53CE084584665A');
        $this->addSql('ALTER TABLE reviews_product DROP FOREIGN KEY FK_E0851D6C4584665A');
        $this->addSql('ALTER TABLE tags_product_product DROP FOREIGN KEY FK_90ACD5EF4584665A');
        $this->addSql('ALTER TABLE tags_product_product DROP FOREIGN KEY FK_90ACD5EF273D7ABB');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_categories');
        $this->addSql('DROP TABLE related_product');
        $this->addSql('DROP TABLE reviews_product');
        $this->addSql('DROP TABLE tags_product');
        $this->addSql('DROP TABLE tags_product_product');
    }
}
