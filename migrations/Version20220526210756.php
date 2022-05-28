<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526210756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE poll (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll_poll_answer (poll_id INT NOT NULL, poll_answer_id INT NOT NULL, INDEX IDX_4BA7E7C3C947C0F (poll_id), INDEX IDX_4BA7E7C61E461F3 (poll_answer_id), PRIMARY KEY(poll_id, poll_answer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll_answer (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll_answer_poll (poll_answer_id INT NOT NULL, poll_id INT NOT NULL, INDEX IDX_E8F56E8761E461F3 (poll_answer_id), INDEX IDX_E8F56E873C947C0F (poll_id), PRIMARY KEY(poll_answer_id, poll_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE poll_poll_answer ADD CONSTRAINT FK_4BA7E7C3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poll_poll_answer ADD CONSTRAINT FK_4BA7E7C61E461F3 FOREIGN KEY (poll_answer_id) REFERENCES poll_answer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poll_answer_poll ADD CONSTRAINT FK_E8F56E8761E461F3 FOREIGN KEY (poll_answer_id) REFERENCES poll_answer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poll_answer_poll ADD CONSTRAINT FK_E8F56E873C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poll_poll_answer DROP FOREIGN KEY FK_4BA7E7C3C947C0F');
        $this->addSql('ALTER TABLE poll_answer_poll DROP FOREIGN KEY FK_E8F56E873C947C0F');
        $this->addSql('ALTER TABLE poll_poll_answer DROP FOREIGN KEY FK_4BA7E7C61E461F3');
        $this->addSql('ALTER TABLE poll_answer_poll DROP FOREIGN KEY FK_E8F56E8761E461F3');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE poll_poll_answer');
        $this->addSql('DROP TABLE poll_answer');
        $this->addSql('DROP TABLE poll_answer_poll');
    }
}
