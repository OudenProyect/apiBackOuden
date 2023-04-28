<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428111959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Houses DROP parking_space, CHANGE type type enum(\'Chalet pareado\',\'Chalet adosado\',\'Apartamento\',\'Piso\',\'Vivienda\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Houses ADD parking_space INT NOT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL');
    }
}
