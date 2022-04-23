<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220423233254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE channel (id BIGINT NOT NULL, guild_id BIGINT DEFAULT NULL, parent_id BIGINT DEFAULT NULL, icon_id BIGINT DEFAULT NULL, type INT DEFAULT NULL, position INT NOT NULL, name VARCHAR(100) DEFAULT NULL, topic VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_A2F98E475F2131EF (guild_id), INDEX IDX_A2F98E47727ACA70 (parent_id), UNIQUE INDEX UNIQ_A2F98E4754B9D732 (icon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE channel_user (channel_id BIGINT NOT NULL, user_id BIGINT NOT NULL, INDEX IDX_11C7753772F5A1AA (channel_id), INDEX IDX_11C77537A76ED395 (user_id), PRIMARY KEY(channel_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id BIGINT NOT NULL, message_attachment_id BIGINT DEFAULT NULL, format_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, size INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8C9F36104DC9A0F3 (message_attachment_id), INDEX IDX_8C9F3610D629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_format (id BIGINT NOT NULL, mimetype VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild (id BIGINT NOT NULL, icon_id BIGINT DEFAULT NULL, owner_id BIGINT DEFAULT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_75407DAB54B9D732 (icon_id), INDEX IDX_75407DAB7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild_member (id BIGINT NOT NULL, user_id BIGINT DEFAULT NULL, guild_id BIGINT DEFAULT NULL, name VARCHAR(100) NOT NULL, join_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_7FD58C97A76ED395 (user_id), INDEX IDX_7FD58C975F2131EF (guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id BIGINT NOT NULL, owner_id BIGINT DEFAULT NULL, channel_id BIGINT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B6BD307F7E3C61F9 (owner_id), INDEX IDX_B6BD307F72F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_attachment (id BIGINT NOT NULL, message_id BIGINT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B68FF524537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_embed (id BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BIGINT NOT NULL, status_id BIGINT DEFAULT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, enabled TINYINT(1) DEFAULT 0 NOT NULL, last_login_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6496BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_status (id BIGINT NOT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E475F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E47727ACA70 FOREIGN KEY (parent_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E4754B9D732 FOREIGN KEY (icon_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C7753772F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C77537A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36104DC9A0F3 FOREIGN KEY (message_attachment_id) REFERENCES message_attachment (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610D629F605 FOREIGN KEY (format_id) REFERENCES file_format (id)');
        $this->addSql('ALTER TABLE guild ADD CONSTRAINT FK_75407DAB54B9D732 FOREIGN KEY (icon_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE guild ADD CONSTRAINT FK_75407DAB7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE guild_member ADD CONSTRAINT FK_7FD58C97A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE guild_member ADD CONSTRAINT FK_7FD58C975F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE message_attachment ADD CONSTRAINT FK_B68FF524537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496BF700BD FOREIGN KEY (status_id) REFERENCES user_status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E47727ACA70');
        $this->addSql('ALTER TABLE channel_user DROP FOREIGN KEY FK_11C7753772F5A1AA');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F72F5A1AA');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E4754B9D732');
        $this->addSql('ALTER TABLE guild DROP FOREIGN KEY FK_75407DAB54B9D732');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610D629F605');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E475F2131EF');
        $this->addSql('ALTER TABLE guild_member DROP FOREIGN KEY FK_7FD58C975F2131EF');
        $this->addSql('ALTER TABLE message_attachment DROP FOREIGN KEY FK_B68FF524537A1329');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36104DC9A0F3');
        $this->addSql('ALTER TABLE channel_user DROP FOREIGN KEY FK_11C77537A76ED395');
        $this->addSql('ALTER TABLE guild DROP FOREIGN KEY FK_75407DAB7E3C61F9');
        $this->addSql('ALTER TABLE guild_member DROP FOREIGN KEY FK_7FD58C97A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F7E3C61F9');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496BF700BD');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE channel_user');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE file_format');
        $this->addSql('DROP TABLE guild');
        $this->addSql('DROP TABLE guild_member');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_attachment');
        $this->addSql('DROP TABLE message_embed');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_status');
    }
}
