<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001223635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE price_modifiers (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT DEFAULT NULL, start DATE DEFAULT NULL, end DATE DEFAULT NULL, cyclic TINYINT(1) NOT NULL, modifier DOUBLE PRECISION NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cabin CHANGE price_multiplier price_multiplier DOUBLE PRECISION NOT NULL, CHANGE custom_price custom_price DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE price_modifiers');
        $this->addSql('ALTER TABLE cabin CHANGE price_multiplier price_multiplier VARCHAR(255) NOT NULL, CHANGE custom_price custom_price VARCHAR(255) DEFAULT NULL');
    }
}
