<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191229173759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post_report (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, reported_by_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, INDEX IDX_F40D93E14B89032C (post_id), INDEX IDX_F40D93E171CE806 (reported_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_report (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, reported_by_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, INDEX IDX_E3C2F96F8697D13 (comment_id), INDEX IDX_E3C2F9671CE806 (reported_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_report ADD CONSTRAINT FK_F40D93E14B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_report ADD CONSTRAINT FK_F40D93E171CE806 FOREIGN KEY (reported_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_report ADD CONSTRAINT FK_E3C2F96F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment_report ADD CONSTRAINT FK_E3C2F9671CE806 FOREIGN KEY (reported_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE post_report');
        $this->addSql('DROP TABLE comment_report');
    }
}
