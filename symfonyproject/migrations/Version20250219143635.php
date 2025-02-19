<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219143635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__habit_tracking AS SELECT id, id_habit_id, id_user_id, status, date FROM habit_tracking');
        $this->addSql('DROP TABLE habit_tracking');
        $this->addSql('CREATE TABLE habit_tracking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_habit_id INTEGER NOT NULL, id_user_id INTEGER NOT NULL, id_group_id INTEGER DEFAULT NULL, status BOOLEAN NOT NULL, date DATE NOT NULL, CONSTRAINT FK_CDBDA719B7755F27 FOREIGN KEY (id_habit_id) REFERENCES habits (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CDBDA71979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CDBDA719AE8F35D2 FOREIGN KEY (id_group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO habit_tracking (id, id_habit_id, id_user_id, status, date) SELECT id, id_habit_id, id_user_id, status, date FROM __temp__habit_tracking');
        $this->addSql('DROP TABLE __temp__habit_tracking');
        $this->addSql('CREATE INDEX IDX_CDBDA71979F37AE5 ON habit_tracking (id_user_id)');
        $this->addSql('CREATE INDEX IDX_CDBDA719B7755F27 ON habit_tracking (id_habit_id)');
        $this->addSql('CREATE INDEX IDX_CDBDA719AE8F35D2 ON habit_tracking (id_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__habit_tracking AS SELECT id, id_habit_id, id_user_id, status, date FROM habit_tracking');
        $this->addSql('DROP TABLE habit_tracking');
        $this->addSql('CREATE TABLE habit_tracking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_habit_id INTEGER NOT NULL, id_user_id INTEGER NOT NULL, status BOOLEAN NOT NULL, date DATE NOT NULL, CONSTRAINT FK_CDBDA719B7755F27 FOREIGN KEY (id_habit_id) REFERENCES habits (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CDBDA71979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO habit_tracking (id, id_habit_id, id_user_id, status, date) SELECT id, id_habit_id, id_user_id, status, date FROM __temp__habit_tracking');
        $this->addSql('DROP TABLE __temp__habit_tracking');
        $this->addSql('CREATE INDEX IDX_CDBDA719B7755F27 ON habit_tracking (id_habit_id)');
        $this->addSql('CREATE INDEX IDX_CDBDA71979F37AE5 ON habit_tracking (id_user_id)');
    }
}
