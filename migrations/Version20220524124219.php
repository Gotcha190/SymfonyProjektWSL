<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524124219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE poll (id INT AUTO_INCREMENT NOT NULL, poll_response_id INT DEFAULT NULL, question VARCHAR(255) NOT NULL, answers LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_84BCFA45C9915F42 (poll_response_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll_response (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, response INT NOT NULL, INDEX IDX_88E1734BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE poll ADD CONSTRAINT FK_84BCFA45C9915F42 FOREIGN KEY (poll_response_id) REFERENCES poll_response (id)');
        $this->addSql('ALTER TABLE poll_response ADD CONSTRAINT FK_88E1734BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA45C9915F42');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE poll_response');
    }
}
