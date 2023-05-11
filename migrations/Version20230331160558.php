<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230331160558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Houses CHANGE type type enum(\'Rustic Property\',\'Castle\',\'Palace\',\'Country house\',\'Town House\',\'Tower\',\'Mansion\'), CHANGE state state enum(\'To reform\',\'In good condition\'), CHANGE energy_consum energy_consum enum(\'A\',\'B\',\'C\',\'D\',\'E\',\'F\',\'G\',\'In process\',\'External property\',\'No data yet\')');
        $this->addSql('ALTER TABLE Users ADD nickname VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Houses CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE state state VARCHAR(255) DEFAULT NULL, CHANGE energy_consum energy_consum VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Users DROP nickname');
    }
}
