<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002192257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, customer_id_id INT NOT NULL, cabin_id_id INT NOT NULL, start DATE NOT NULL, end DATE NOT NULL, notes LONGTEXT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_E00CEDDEB171EB6C (customer_id_id), INDEX IDX_E00CEDDEBE92D8F9 (cabin_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEB171EB6C FOREIGN KEY (customer_id_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEBE92D8F9 FOREIGN KEY (cabin_id_id) REFERENCES cabin (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEB171EB6C');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEBE92D8F9');
        $this->addSql('DROP TABLE booking');
    }
}
