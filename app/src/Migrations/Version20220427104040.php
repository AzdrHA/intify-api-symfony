<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427104040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E4754B9D732');
        $this->addSql('ALTER TABLE guild DROP FOREIGN KEY FK_75407DAB54B9D732');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610D629F605');
        $this->addSql('CREATE TABLE guild_invite (id BIGINT NOT NULL, guild_id BIGINT DEFAULT NULL, channel_id BIGINT DEFAULT NULL, inviter_id BIGINT DEFAULT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C8D366385F2131EF (guild_id), INDEX IDX_C8D3663872F5A1AA (channel_id), INDEX IDX_C8D36638B79F4F04 (inviter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_friend_ship (id BIGINT NOT NULL, user_id BIGINT DEFAULT NULL, friend_id BIGINT DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C951FE51A76ED395 (user_id), INDEX IDX_C951FE516A5458E8 (friend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guild_invite ADD CONSTRAINT FK_C8D366385F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE guild_invite ADD CONSTRAINT FK_C8D3663872F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id)');
        $this->addSql('ALTER TABLE guild_invite ADD CONSTRAINT FK_C8D36638B79F4F04 FOREIGN KEY (inviter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_friend_ship ADD CONSTRAINT FK_C951FE51A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_friend_ship ADD CONSTRAINT FK_C951FE516A5458E8 FOREIGN KEY (friend_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE file_format');
        $this->addSql('DROP INDEX UNIQ_A2F98E4754B9D732 ON channel');
        $this->addSql('ALTER TABLE channel DROP icon_id');
        $this->addSql('DROP INDEX UNIQ_75407DAB54B9D732 ON guild');
        $this->addSql('ALTER TABLE guild DROP icon_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id BIGINT NOT NULL, format_id BIGINT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, size INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8C9F3610D629F605 (format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE file_format (id BIGINT NOT NULL, mimetype VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610D629F605 FOREIGN KEY (format_id) REFERENCES file_format (id)');
        $this->addSql('DROP TABLE guild_invite');
        $this->addSql('DROP TABLE user_friend_ship');
        $this->addSql('ALTER TABLE channel ADD icon_id BIGINT DEFAULT NULL, CHANGE name name VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE topic topic VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E4754B9D732 FOREIGN KEY (icon_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2F98E4754B9D732 ON channel (icon_id)');
        $this->addSql('ALTER TABLE guild ADD icon_id BIGINT DEFAULT NULL, CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE guild ADD CONSTRAINT FK_75407DAB54B9D732 FOREIGN KEY (icon_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_75407DAB54B9D732 ON guild (icon_id)');
        $this->addSql('ALTER TABLE guild_member CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message_attachment CHANGE path path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE client_name client_name VARCHAR(200) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE refresh_tokens CHANGE refresh_token refresh_token VARCHAR(128) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE firstname firstname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_status CHANGE content content VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
