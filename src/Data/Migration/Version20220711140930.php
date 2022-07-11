<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220711140930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, site_id VARCHAR(255) NOT NULL, bill_id VARCHAR(255) NOT NULL, amount_currency VARCHAR(255) NOT NULL, amount_value INT NOT NULL, status_value VARCHAR(255) NOT NULL, changed_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment VARCHAR(255) NOT NULL, creation_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expiration_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', pay_url VARCHAR(255) NOT NULL, recipient_phone_number VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6D28840DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BIGINT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, balance INT DEFAULT NULL, create_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DA76ED395');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE user');
    }
}
