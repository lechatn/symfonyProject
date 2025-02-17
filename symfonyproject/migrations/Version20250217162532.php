<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217162532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, id_creator_id INT NOT NULL, name VARCHAR(255) NOT NULL, score INT NOT NULL, UNIQUE INDEX UNIQ_6DC044C5C4A88E71 (id_creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habit_tracking (id INT AUTO_INCREMENT NOT NULL, id_habit_id INT NOT NULL, id_user_id INT NOT NULL, status TINYINT(1) NOT NULL, date DATE NOT NULL, INDEX IDX_CDBDA719B7755F27 (id_habit_id), INDEX IDX_CDBDA71979F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habits (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, difficulty VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, frequency VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score_history (id INT AUTO_INCREMENT NOT NULL, id_group_id INT NOT NULL, points INT NOT NULL, date DATE NOT NULL, INDEX IDX_463255DFAE8F35D2 (id_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, id_group_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, INDEX IDX_8D93D649AE8F35D2 (id_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5C4A88E71 FOREIGN KEY (id_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE habit_tracking ADD CONSTRAINT FK_CDBDA719B7755F27 FOREIGN KEY (id_habit_id) REFERENCES habits (id)');
        $this->addSql('ALTER TABLE habit_tracking ADD CONSTRAINT FK_CDBDA71979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE score_history ADD CONSTRAINT FK_463255DFAE8F35D2 FOREIGN KEY (id_group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AE8F35D2 FOREIGN KEY (id_group_id) REFERENCES `group` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5C4A88E71');
        $this->addSql('ALTER TABLE habit_tracking DROP FOREIGN KEY FK_CDBDA719B7755F27');
        $this->addSql('ALTER TABLE habit_tracking DROP FOREIGN KEY FK_CDBDA71979F37AE5');
        $this->addSql('ALTER TABLE score_history DROP FOREIGN KEY FK_463255DFAE8F35D2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AE8F35D2');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE habit_tracking');
        $this->addSql('DROP TABLE habits');
        $this->addSql('DROP TABLE score_history');
        $this->addSql('DROP TABLE user');
    }
}
