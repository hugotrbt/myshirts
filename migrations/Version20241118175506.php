<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118175506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE locker_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE member (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, locker_room_id INTEGER NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, CONSTRAINT FK_70E4FA78DECFADCC FOREIGN KEY (locker_room_id) REFERENCES locker_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70E4FA78DECFADCC ON member (locker_room_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON member (email)');
        $this->addSql('CREATE TABLE shirt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, locker_room_id INTEGER NOT NULL, team VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INTEGER DEFAULT NULL, updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , content_type VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_8BA5EC10DECFADCC FOREIGN KEY (locker_room_id) REFERENCES locker_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8BA5EC10DECFADCC ON shirt (locker_room_id)');
        $this->addSql('CREATE TABLE stadium (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, published BOOLEAN NOT NULL, CONSTRAINT FK_E604044F61220EA6 FOREIGN KEY (creator_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E604044F61220EA6 ON stadium (creator_id)');
        $this->addSql('CREATE TABLE stadium_shirt (stadium_id INTEGER NOT NULL, shirt_id INTEGER NOT NULL, PRIMARY KEY(stadium_id, shirt_id), CONSTRAINT FK_281E25B07E860E36 FOREIGN KEY (stadium_id) REFERENCES stadium (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_281E25B02E108D4C FOREIGN KEY (shirt_id) REFERENCES shirt (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_281E25B07E860E36 ON stadium_shirt (stadium_id)');
        $this->addSql('CREATE INDEX IDX_281E25B02E108D4C ON stadium_shirt (shirt_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE locker_room');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE shirt');
        $this->addSql('DROP TABLE stadium');
        $this->addSql('DROP TABLE stadium_shirt');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
