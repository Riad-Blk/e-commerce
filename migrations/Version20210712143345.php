<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210712143345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, stock INT NOT NULL, couleur VARCHAR(30) DEFAULT NULL, taille VARCHAR(30) DEFAULT NULL, actif TINYINT(1) NOT NULL, promo INT NOT NULL, FULLTEXT INDEX IDX_BE2DDF8CFF7747B46DE44026 (titre, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_category (produits_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_F97AE113CD11A2CF (produits_id), INDEX IDX_F97AE11312469DE2 (category_id), PRIMARY KEY(produits_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produits_category ADD CONSTRAINT FK_F97AE113CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_category ADD CONSTRAINT FK_F97AE11312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits_category DROP FOREIGN KEY FK_F97AE11312469DE2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CCD11A2CF');
        $this->addSql('ALTER TABLE produits_category DROP FOREIGN KEY FK_F97AE113CD11A2CF');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE produits_category');
    }
}
