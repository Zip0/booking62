<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003100512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEB171EB6C');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEBE92D8F9');
        $this->addSql('DROP INDEX IDX_E00CEDDEB171EB6C ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDEBE92D8F9 ON booking');
        $this->addSql('ALTER TABLE booking ADD customer_id INT NOT NULL, ADD cabin_id INT NOT NULL, DROP customer_id_id, DROP cabin_id_id');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE8560181F FOREIGN KEY (cabin_id) REFERENCES cabin (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE9395C3F3 ON booking (customer_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE8560181F ON booking (cabin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE9395C3F3');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE8560181F');
        $this->addSql('DROP INDEX IDX_E00CEDDE9395C3F3 ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDE8560181F ON booking');
        $this->addSql('ALTER TABLE booking ADD customer_id_id INT NOT NULL, ADD cabin_id_id INT NOT NULL, DROP customer_id, DROP cabin_id');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEB171EB6C FOREIGN KEY (customer_id_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEBE92D8F9 FOREIGN KEY (cabin_id_id) REFERENCES cabin (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E00CEDDEB171EB6C ON booking (customer_id_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEBE92D8F9 ON booking (cabin_id_id)');
    }
}
