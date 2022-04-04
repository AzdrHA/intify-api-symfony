<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404102220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE channel_user (channel_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_11C7753772F5A1AA (channel_id), INDEX IDX_11C77537A76ED395 (user_id), PRIMARY KEY(channel_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild_member (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, guild_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, join_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_7FD58C97A76ED395 (user_id), INDEX IDX_7FD58C975F2131EF (guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_attachment (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B68FF524537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_embed (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C7753772F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C77537A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE guild_member ADD CONSTRAINT FK_7FD58C97A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE guild_member ADD CONSTRAINT FK_7FD58C975F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE message_attachment ADD CONSTRAINT FK_B68FF524537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE channel ADD parent_id INT DEFAULT NULL, ADD icon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E47727ACA70 FOREIGN KEY (parent_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E4754B9D732 FOREIGN KEY (icon_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_A2F98E47727ACA70 ON channel (parent_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2F98E4754B9D732 ON channel (icon_id)');
        $this->addSql('ALTER TABLE file ADD message_attachment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36104DC9A0F3 FOREIGN KEY (message_attachment_id) REFERENCES message_attachment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C9F36104DC9A0F3 ON file (message_attachment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36104DC9A0F3');
        $this->addSql('DROP TABLE channel_user');
        $this->addSql('DROP TABLE guild_member');
        $this->addSql('DROP TABLE message_attachment');
        $this->addSql('DROP TABLE message_embed');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E47727ACA70');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E4754B9D732');
        $this->addSql('DROP INDEX IDX_A2F98E47727ACA70 ON channel');
        $this->addSql('DROP INDEX UNIQ_A2F98E4754B9D732 ON channel');
        $this->addSql('ALTER TABLE channel DROP parent_id, DROP icon_id, CHANGE name name VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE topic topic VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_8C9F36104DC9A0F3 ON file');
        $this->addSql('ALTER TABLE file DROP message_attachment_id, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE path path VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE file_format CHANGE mimetype mimetype VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE guild CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_status CHANGE content content VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
