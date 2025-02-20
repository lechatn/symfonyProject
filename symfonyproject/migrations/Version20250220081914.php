<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220081914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score_history ADD COLUMN description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__score_history AS SELECT id, id_group_id, points, date FROM score_history');
        $this->addSql('DROP TABLE score_history');
        $this->addSql('CREATE TABLE score_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_group_id INTEGER NOT NULL, points INTEGER NOT NULL, date DATE NOT NULL, CONSTRAINT FK_463255DFAE8F35D2 FOREIGN KEY (id_group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO score_history (id, id_group_id, points, date) SELECT id, id_group_id, points, date FROM __temp__score_history');
        $this->addSql('DROP TABLE __temp__score_history');
        $this->addSql('CREATE INDEX IDX_463255DFAE8F35D2 ON score_history (id_group_id)');
    }
}
