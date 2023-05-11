<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230412132531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE house_location_services (house_id INT NOT NULL, location_services_id INT NOT NULL, INDEX IDX_72279D3C6BB74515 (house_id), INDEX IDX_72279D3CB8204185 (location_services_id), PRIMARY KEY(house_id, location_services_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_services (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE house_location_services ADD CONSTRAINT FK_72279D3C6BB74515 FOREIGN KEY (house_id) REFERENCES Houses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE house_location_services ADD CONSTRAINT FK_72279D3CB8204185 FOREIGN KEY (location_services_id) REFERENCES location_services (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Houses DROP FOREIGN KEY FK_927BF0FD64D218E');
        $this->addSql('DROP INDEX UNIQ_927BF0FD64D218E ON Houses');
        $this->addSql('ALTER TABLE Houses DROP location_id, CHANGE type type enum(\'Rustic Property\',\'Castle\',\'Palace\',\'Country house\',\'Town House\',\'Tower\',\'Mansion\'), CHANGE state state enum(\'To reform\',\'In good condition\'), CHANGE energy_consum energy_consum enum(\'A\',\'B\',\'C\',\'D\',\'E\',\'F\',\'G\',\'In process\',\'External property\',\'No data yet\')');
        $this->addSql('ALTER TABLE Locations DROP FOREIGN KEY FK_9517C8196BB74515');
        $this->addSql('DROP INDEX UNIQ_9517C8196BB74515 ON Locations');
        $this->addSql('ALTER TABLE Locations DROP house_id');
        $this->addSql('ALTER TABLE Users ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AED64D218E FOREIGN KEY (location_id) REFERENCES Locations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D5428AED64D218E ON Users (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house_location_services DROP FOREIGN KEY FK_72279D3C6BB74515');
        $this->addSql('ALTER TABLE house_location_services DROP FOREIGN KEY FK_72279D3CB8204185');
        $this->addSql('DROP TABLE house_location_services');
        $this->addSql('DROP TABLE location_services');
        $this->addSql('ALTER TABLE Houses ADD location_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE state state VARCHAR(255) DEFAULT NULL, CHANGE energy_consum energy_consum VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Houses ADD CONSTRAINT FK_927BF0FD64D218E FOREIGN KEY (location_id) REFERENCES Locations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_927BF0FD64D218E ON Houses (location_id)');
        $this->addSql('ALTER TABLE Locations ADD house_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Locations ADD CONSTRAINT FK_9517C8196BB74515 FOREIGN KEY (house_id) REFERENCES Houses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9517C8196BB74515 ON Locations (house_id)');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AED64D218E');
        $this->addSql('DROP INDEX UNIQ_D5428AED64D218E ON Users');
        $this->addSql('ALTER TABLE Users DROP location_id');
    }
}
