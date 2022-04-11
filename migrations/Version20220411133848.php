<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411133848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_article ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE blog_article ADD CONSTRAINT FK_EECCB3E512469DE2 FOREIGN KEY (category_id) REFERENCES blog_category (id)');
        $this->addSql('CREATE INDEX IDX_EECCB3E512469DE2 ON blog_article (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_article DROP FOREIGN KEY FK_EECCB3E512469DE2');
        $this->addSql('DROP INDEX IDX_EECCB3E512469DE2 ON blog_article');
        $this->addSql('ALTER TABLE blog_article DROP category_id');
    }
}
