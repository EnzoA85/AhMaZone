<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109093637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Acheter MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON Acheter');
        $this->addSql('ALTER TABLE Acheter DROP id');
        $this->addSql('ALTER TABLE Acheter ADD PRIMARY KEY (id_Produit, id_commande)');
        $this->addSql('ALTER TABLE Acheter RENAME INDEX id_produit TO IDX_6E0E911838857CCB');
        $this->addSql('ALTER TABLE Acheter RENAME INDEX id_commande TO IDX_6E0E91183E314AE8');
        $this->addSql('ALTER TABLE Commande CHANGE id_client id_client INT DEFAULT NULL, CHANGE prixTotalTTC prix_total_ttc DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE Commande RENAME INDEX id_client TO IDX_6EEAA67DE173B1B8');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE acheter ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE acheter RENAME INDEX idx_6e0e91183e314ae8 TO id_commande');
        $this->addSql('ALTER TABLE acheter RENAME INDEX idx_6e0e911838857ccb TO id_Produit');
        $this->addSql('ALTER TABLE commande CHANGE id_client id_client INT NOT NULL, CHANGE prix_total_ttc prixTotalTTC DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE commande RENAME INDEX idx_6eeaa67de173b1b8 TO id_client');
    }
}
