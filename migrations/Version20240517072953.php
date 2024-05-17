<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517072953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE films_categories (films_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_3C3B0D66939610EE (films_id), INDEX IDX_3C3B0D66A21214B7 (categories_id), PRIMARY KEY(films_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE films_categories ADD CONSTRAINT FK_3C3B0D66939610EE FOREIGN KEY (films_id) REFERENCES films (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE films_categories ADD CONSTRAINT FK_3C3B0D66A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE films_categories DROP FOREIGN KEY FK_3C3B0D66939610EE');
        $this->addSql('ALTER TABLE films_categories DROP FOREIGN KEY FK_3C3B0D66A21214B7');
        $this->addSql('DROP TABLE films_categories');
    }
}