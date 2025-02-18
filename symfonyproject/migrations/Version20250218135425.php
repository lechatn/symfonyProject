<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218135425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "group" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, score INTEGER NOT NULL, CONSTRAINT FK_6DC044C5F05788E9 FOREIGN KEY (creator_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC044C5F05788E9 ON "group" (creator_id_id)');
        $this->addSql('CREATE TABLE habit_tracking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_habit_id INTEGER NOT NULL, id_user_id INTEGER NOT NULL, status BOOLEAN NOT NULL, date DATE NOT NULL, CONSTRAINT FK_CDBDA719B7755F27 FOREIGN KEY (id_habit_id) REFERENCES habits (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CDBDA71979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CDBDA719B7755F27 ON habit_tracking (id_habit_id)');
        $this->addSql('CREATE INDEX IDX_CDBDA71979F37AE5 ON habit_tracking (id_user_id)');
        $this->addSql('CREATE TABLE habits (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, difficulty VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, frequency VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE score_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_group_id INTEGER NOT NULL, points INTEGER NOT NULL, date DATE NOT NULL, CONSTRAINT FK_463255DFAE8F35D2 FOREIGN KEY (id_group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_463255DFAE8F35D2 ON score_history (id_group_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_group_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, CONSTRAINT FK_8D93D649AE8F35D2 FOREIGN KEY (id_group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649AE8F35D2 ON user (id_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE habit_tracking');
        $this->addSql('DROP TABLE habits');
        $this->addSql('DROP TABLE score_history');
        $this->addSql('DROP TABLE user');
    }
}
