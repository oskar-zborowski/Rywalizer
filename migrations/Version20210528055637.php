<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528055637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE login_form (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_94FA760A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, issued_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C74F21955F37A13B (token), UNIQUE INDEX UNIQ_C74F2195A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F2195A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD external_login_form_id INT DEFAULT NULL, ADD active_login_form_id INT NOT NULL, ADD is_verified TINYINT(1) NOT NULL, CHANGE email email VARCHAR(340) DEFAULT NULL, CHANGE first_name first_name VARCHAR(40) NOT NULL, CHANGE last_name last_name VARCHAR(40) NOT NULL, CHANGE profile_picture profile_picture VARCHAR(16) DEFAULT NULL, CHANGE external_authentication external_authentication VARCHAR(340) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64927C83FDB FOREIGN KEY (external_login_form_id) REFERENCES login_form (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649918141A2 FOREIGN KEY (active_login_form_id) REFERENCES login_form (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64927C83FDB ON user (external_login_form_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649918141A2 ON user (active_login_form_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64927C83FDB');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649918141A2');
        $this->addSql('DROP TABLE login_form');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP INDEX IDX_8D93D64927C83FDB ON user');
        $this->addSql('DROP INDEX IDX_8D93D649918141A2 ON user');
        $this->addSql('ALTER TABLE user DROP external_login_form_id, DROP active_login_form_id, DROP is_verified, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE profile_picture profile_picture VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE external_authentication external_authentication VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
