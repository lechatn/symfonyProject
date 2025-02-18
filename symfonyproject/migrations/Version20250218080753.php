<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218080753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE habit_tracking (id INT AUTO_INCREMENT NOT NULL, id_habit_id INT NOT NULL, id_user_id INT NOT NULL, status TINYINT(1) NOT NULL, date DATE NOT NULL, INDEX IDX_CDBDA719B7755F27 (id_habit_id), INDEX IDX_CDBDA71979F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, id_group_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649AE8F35D2 (id_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE habit_tracking ADD CONSTRAINT FK_CDBDA719B7755F27 FOREIGN KEY (id_habit_id) REFERENCES habits (id)');
        $this->addSql('ALTER TABLE habit_tracking ADD CONSTRAINT FK_CDBDA71979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AE8F35D2 FOREIGN KEY (id_group_id) REFERENCES `group` (id)');
        $this->addSql('DROP INDEX UNIQ_6DC044C5C4A88E71 ON `group`');
        $this->addSql('ALTER TABLE `group` CHANGE id_creator_id creator_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5F05788E9 FOREIGN KEY (creator_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC044C5F05788E9 ON `group` (creator_id_id)');
        $this->addSql('ALTER TABLE score_history ADD CONSTRAINT FK_463255DFAE8F35D2 FOREIGN KEY (id_group_id) REFERENCES `group` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5F05788E9');
        $this->addSql('ALTER TABLE habit_tracking DROP FOREIGN KEY FK_CDBDA719B7755F27');
        $this->addSql('ALTER TABLE habit_tracking DROP FOREIGN KEY FK_CDBDA71979F37AE5');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AE8F35D2');
        $this->addSql('DROP TABLE habit_tracking');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_6DC044C5F05788E9 ON `group`');
        $this->addSql('ALTER TABLE `group` CHANGE creator_id_id id_creator_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC044C5C4A88E71 ON `group` (id_creator_id)');
        $this->addSql('ALTER TABLE score_history DROP FOREIGN KEY FK_463255DFAE8F35D2');
    }
}
