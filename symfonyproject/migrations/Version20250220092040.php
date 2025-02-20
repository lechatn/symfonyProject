<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220092040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__mail AS SELECT id, user_mail_id, type, description FROM mail');
        $this->addSql('DROP TABLE mail');
        $this->addSql('CREATE TABLE mail (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_mail_id INTEGER NOT NULL, id_group_id INTEGER DEFAULT NULL, id_sender_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, CONSTRAINT FK_5126AC48907A710B FOREIGN KEY (user_mail_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5126AC48AE8F35D2 FOREIGN KEY (id_group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5126AC4876110FBA FOREIGN KEY (id_sender_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO mail (id, user_mail_id, type, description) SELECT id, user_mail_id, type, description FROM __temp__mail');
        $this->addSql('DROP TABLE __temp__mail');
        $this->addSql('CREATE INDEX IDX_5126AC48907A710B ON mail (user_mail_id)');
        $this->addSql('CREATE INDEX IDX_5126AC48AE8F35D2 ON mail (id_group_id)');
        $this->addSql('CREATE INDEX IDX_5126AC4876110FBA ON mail (id_sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__mail AS SELECT id, user_mail_id, type, description FROM mail');
        $this->addSql('DROP TABLE mail');
        $this->addSql('CREATE TABLE mail (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_mail_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, CONSTRAINT FK_5126AC48907A710B FOREIGN KEY (user_mail_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO mail (id, user_mail_id, type, description) SELECT id, user_mail_id, type, description FROM __temp__mail');
        $this->addSql('DROP TABLE __temp__mail');
        $this->addSql('CREATE INDEX IDX_5126AC48907A710B ON mail (user_mail_id)');
    }
}
