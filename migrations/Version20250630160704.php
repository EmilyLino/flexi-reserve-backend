<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630160704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE area (id_area INT AUTO_INCREMENT NOT NULL, area_name VARCHAR(255) NOT NULL, area_description VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, modification_user VARCHAR(255) DEFAULT NULL, creation_user VARCHAR(255), status VARCHAR(255) NOT NULL, PRIMARY KEY(id_area)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id_reservation INT AUTO_INCREMENT NOT NULL, area_id INT NOT NULL, schedule_id INT NOT NULL, user_id INT NOT NULL, event_name VARCHAR(255) NOT NULL, event_description LONGTEXT DEFAULT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_42C84955BD0F409C (area_id), INDEX IDX_42C84955A40BC2D5 (schedule_id), INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id_reservation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id_schedule INT AUTO_INCREMENT NOT NULL, hour_ini TIME NOT NULL, hour_fin TIME NOT NULL, creation_date DATETIME NOT NULL, creation_user VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, modification_user VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_schedule)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id_user INT AUTO_INCREMENT NOT NULL, userName VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955BD0F409C FOREIGN KEY (area_id) REFERENCES area (id_area)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id_schedule)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955BD0F409C');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A40BC2D5');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
