<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826170203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD316896931C29');
        $this->addSql('DROP INDEX IDX_BFDD316896931C29 ON articles');
        $this->addSql('ALTER TABLE articles CHANGE films_id_id films_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168939610EE FOREIGN KEY (films_id) REFERENCES films (id)');
        $this->addSql('CREATE INDEX IDX_BFDD3168939610EE ON articles (films_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168939610EE');
        $this->addSql('DROP INDEX IDX_BFDD3168939610EE ON articles');
        $this->addSql('ALTER TABLE articles CHANGE films_id films_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD316896931C29 FOREIGN KEY (films_id_id) REFERENCES films (id)');
        $this->addSql('CREATE INDEX IDX_BFDD316896931C29 ON articles (films_id_id)');
    }
}
