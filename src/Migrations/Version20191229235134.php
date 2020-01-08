<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191229235134 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_report DROP FOREIGN KEY FK_E3C2F964B89032C');
        $this->addSql('DROP INDEX IDX_E3C2F964B89032C ON comment_report');
        $this->addSql('ALTER TABLE comment_report DROP post_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_report ADD post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_report ADD CONSTRAINT FK_E3C2F964B89032C FOREIGN KEY (post_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_E3C2F964B89032C ON comment_report (post_id)');
    }
}
