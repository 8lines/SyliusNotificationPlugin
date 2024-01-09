<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212135946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration of SyliusNotificationPlugin';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE 8lines_notification (id INT AUTO_INCREMENT NOT NULL, code LONGTEXT NOT NULL, event LONGTEXT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_actions (notification_id INT NOT NULL, notification_action_id INT NOT NULL, INDEX IDX_94A39187EF1A9D84 (notification_id), UNIQUE INDEX UNIQ_94A391873BDBB76A (notification_action_id), PRIMARY KEY(notification_id, notification_action_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_channels (notification_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_B0ECE0BCEF1A9D84 (notification_id), INDEX IDX_B0ECE0BC72F5A1AA (channel_id), PRIMARY KEY(notification_id, channel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_action (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C8C073DC537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_message (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_message_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, content LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_B87037A32C2AC5D3 (translatable_id), UNIQUE INDEX 8lines_notification_message_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE 8lines_notification_actions ADD CONSTRAINT FK_94A39187EF1A9D84 FOREIGN KEY (notification_id) REFERENCES 8lines_notification (id)');
        $this->addSql('ALTER TABLE 8lines_notification_actions ADD CONSTRAINT FK_94A391873BDBB76A FOREIGN KEY (notification_action_id) REFERENCES 8lines_notification_action (id)');
        $this->addSql('ALTER TABLE 8lines_notification_channels ADD CONSTRAINT FK_B0ECE0BCEF1A9D84 FOREIGN KEY (notification_id) REFERENCES 8lines_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_channels ADD CONSTRAINT FK_B0ECE0BC72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_action ADD CONSTRAINT FK_C8C073DC537A1329 FOREIGN KEY (message_id) REFERENCES 8lines_notification_message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_message_translation ADD CONSTRAINT FK_B87037A32C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES 8lines_notification_message (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 8lines_notification_actions DROP FOREIGN KEY FK_94A39187EF1A9D84');
        $this->addSql('ALTER TABLE 8lines_notification_actions DROP FOREIGN KEY FK_94A391873BDBB76A');
        $this->addSql('ALTER TABLE 8lines_notification_channels DROP FOREIGN KEY FK_B0ECE0BCEF1A9D84');
        $this->addSql('ALTER TABLE 8lines_notification_channels DROP FOREIGN KEY FK_B0ECE0BC72F5A1AA');
        $this->addSql('ALTER TABLE 8lines_notification_action DROP FOREIGN KEY FK_C8C073DC537A1329');
        $this->addSql('ALTER TABLE 8lines_notification_message_translation DROP FOREIGN KEY FK_B87037A32C2AC5D3');
        $this->addSql('DROP TABLE 8lines_notification');
        $this->addSql('DROP TABLE 8lines_notification_actions');
        $this->addSql('DROP TABLE 8lines_notification_channels');
        $this->addSql('DROP TABLE 8lines_notification_action');
        $this->addSql('DROP TABLE 8lines_notification_message');
        $this->addSql('DROP TABLE 8lines_notification_message_translation');
    }
}
