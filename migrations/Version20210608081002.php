<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608081002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE login_service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_F1B084AF5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE password (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_35C246D5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, issued_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C74F21955F37A13B (token), UNIQUE INDEX UNIQ_C74F2195A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(340) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', first_name VARCHAR(40) NOT NULL, last_name VARCHAR(40) NOT NULL, profile_picture VARCHAR(16) DEFAULT NULL, joining_date DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, is_blocked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649C5659115 (profile_picture), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE username (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, username_type_id INT NOT NULL, login_service_id INT NOT NULL, username VARCHAR(428) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_F85E0677F85E0677 (username), INDEX IDX_F85E0677A76ED395 (user_id), INDEX IDX_F85E06773A81B246 (username_type_id), INDEX IDX_F85E067720EB315F (login_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE username_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_5252C3E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE password ADD CONSTRAINT FK_35C246D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F2195A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE username ADD CONSTRAINT FK_F85E0677A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE username ADD CONSTRAINT FK_F85E06773A81B246 FOREIGN KEY (username_type_id) REFERENCES username_type (id)');
        $this->addSql('ALTER TABLE username ADD CONSTRAINT FK_F85E067720EB315F FOREIGN KEY (login_service_id) REFERENCES login_service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE username DROP FOREIGN KEY FK_F85E067720EB315F');
        $this->addSql('ALTER TABLE password DROP FOREIGN KEY FK_35C246D5A76ED395');
        $this->addSql('ALTER TABLE refresh_token DROP FOREIGN KEY FK_C74F2195A76ED395');
        $this->addSql('ALTER TABLE username DROP FOREIGN KEY FK_F85E0677A76ED395');
        $this->addSql('ALTER TABLE username DROP FOREIGN KEY FK_F85E06773A81B246');
        $this->addSql('DROP TABLE login_service');
        $this->addSql('DROP TABLE password');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE username');
        $this->addSql('DROP TABLE username_type');
    }
}
