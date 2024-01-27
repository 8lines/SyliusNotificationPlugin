<?php

declare(strict_types=1);

namespace EightLines\SyliusNotificationPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231212135946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration of SyliusNotificationPlugin';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE 8lines_notification (id INT AUTO_INCREMENT NOT NULL, code LONGTEXT NOT NULL, event_code LONGTEXT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_actions (notification_id INT NOT NULL, notification_action_id INT NOT NULL, INDEX IDX_94A39187EF1A9D84 (notification_id), UNIQUE INDEX UNIQ_94A391873BDBB76A (notification_action_id), PRIMARY KEY(notification_id, notification_action_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_channels (notification_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_B0ECE0BCEF1A9D84 (notification_id), INDEX IDX_B0ECE0BC72F5A1AA (channel_id), PRIMARY KEY(notification_id, channel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_action (id INT AUTO_INCREMENT NOT NULL, configuration_id INT NOT NULL, channel_code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C8C073DC73F32DD8 (configuration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_configuration (id INT AUTO_INCREMENT NOT NULL, content_id INT NOT NULL, notify_primary_recipient TINYINT(1) NOT NULL, custom LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_25D84B5384A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_additional_recipients (notification_action_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_913AD7183BDBB76A (notification_action_id), INDEX IDX_913AD7189395C3F3 (customer_id), PRIMARY KEY(notification_action_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_content (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 8lines_notification_content_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, subject LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_8B25C3DC2C2AC5D3 (translatable_id), UNIQUE INDEX 8lines_notification_content_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE 8lines_notification_actions ADD CONSTRAINT FK_94A39187EF1A9D84 FOREIGN KEY (notification_id) REFERENCES 8lines_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_actions ADD CONSTRAINT FK_94A391873BDBB76A FOREIGN KEY (notification_action_id) REFERENCES 8lines_notification_action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_channels ADD CONSTRAINT FK_B0ECE0BCEF1A9D84 FOREIGN KEY (notification_id) REFERENCES 8lines_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_channels ADD CONSTRAINT FK_B0ECE0BC72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_action ADD CONSTRAINT FK_C8C073DC73F32DD8 FOREIGN KEY (configuration_id) REFERENCES 8lines_notification_configuration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_configuration ADD CONSTRAINT FK_25D84B5384A0A3ED FOREIGN KEY (content_id) REFERENCES 8lines_notification_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_additional_recipients ADD CONSTRAINT FK_913AD7183BDBB76A FOREIGN KEY (notification_action_id) REFERENCES 8lines_notification_configuration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_additional_recipients ADD CONSTRAINT FK_913AD7189395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 8lines_notification_content_translation ADD CONSTRAINT FK_8B25C3DC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES 8lines_notification_content (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE 8lines_notification_actions DROP FOREIGN KEY FK_94A39187EF1A9D84');
        $this->addSql('ALTER TABLE 8lines_notification_actions DROP FOREIGN KEY FK_94A391873BDBB76A');
        $this->addSql('ALTER TABLE 8lines_notification_channels DROP FOREIGN KEY FK_B0ECE0BCEF1A9D84');
        $this->addSql('ALTER TABLE 8lines_notification_channels DROP FOREIGN KEY FK_B0ECE0BC72F5A1AA');
        $this->addSql('ALTER TABLE 8lines_notification_action DROP FOREIGN KEY FK_C8C073DC73F32DD8');
        $this->addSql('ALTER TABLE 8lines_notification_configuration DROP FOREIGN KEY FK_25D84B5384A0A3ED');
        $this->addSql('ALTER TABLE 8lines_notification_additional_recipients DROP FOREIGN KEY FK_913AD7183BDBB76A');
        $this->addSql('ALTER TABLE 8lines_notification_additional_recipients DROP FOREIGN KEY FK_913AD7189395C3F3');
        $this->addSql('ALTER TABLE 8lines_notification_content_translation DROP FOREIGN KEY FK_8B25C3DC2C2AC5D3');
        $this->addSql('DROP TABLE 8lines_notification');
        $this->addSql('DROP TABLE 8lines_notification_actions');
        $this->addSql('DROP TABLE 8lines_notification_channels');
        $this->addSql('DROP TABLE 8lines_notification_action');
        $this->addSql('DROP TABLE 8lines_notification_configuration');
        $this->addSql('DROP TABLE 8lines_notification_additional_recipients');
        $this->addSql('DROP TABLE 8lines_notification_content');
        $this->addSql('DROP TABLE 8lines_notification_content_translation');
    }
}
