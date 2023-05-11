<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414133752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Houses ADD useful_livin_area VARCHAR(20) NOT NULL, ADD builded_surface VARCHAR(20) NOT NULL, ADD floors INT NOT NULL, ADD parking_space INT NOT NULL, DROP state, DROP energy_consum, DROP year_construction, DROP community_spend, CHANGE type type enum(\'Rustic Property\',\'Castle\',\'Palace\',\'Country house\',\'Town House\',\'Tower\',\'Mansion\')');
        $this->addSql('ALTER TABLE Publications ADD details LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Houses ADD state VARCHAR(255) DEFAULT NULL, ADD energy_consum VARCHAR(255) DEFAULT NULL, ADD year_construction INT DEFAULT NULL, ADD community_spend DOUBLE PRECISION DEFAULT NULL, DROP useful_livin_area, DROP builded_surface, DROP floors, DROP parking_space, CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Publications DROP details');
    }
}
