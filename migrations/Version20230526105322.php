<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526105322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Comments (id INT AUTO_INCREMENT NOT NULL, publication_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(100) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, score INT NOT NULL, date DATE NOT NULL, time TIME NOT NULL, INDEX IDX_A6E8F47C38B217A7 (publication_id), INDEX IDX_A6E8F47CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Companies (id INT AUTO_INCREMENT NOT NULL, cif_company VARCHAR(9) NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, location VARCHAR(255) NOT NULL, link_web LONGTEXT DEFAULT NULL, phone INT NOT NULL, UNIQUE INDEX UNIQ_B52899FEEFA11A (cif_company), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Features (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Houses (id INT AUTO_INCREMENT NOT NULL, type enum(\'Chalet pareado\',\'Chalet adosado\',\'Apartamento\',\'Piso\',\'Vivienda\'), n_bedrooms INT NOT NULL, toilets INT NOT NULL, price DOUBLE PRECISION NOT NULL, useful_livin_area VARCHAR(20) NOT NULL, builded_surface VARCHAR(20) NOT NULL, floors INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE house_feature (house_id INT NOT NULL, feature_id INT NOT NULL, INDEX IDX_8B00F37B6BB74515 (house_id), INDEX IDX_8B00F37B60E4B879 (feature_id), PRIMARY KEY(house_id, feature_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE house_location_services (house_id INT NOT NULL, location_services_id INT NOT NULL, INDEX IDX_72279D3C6BB74515 (house_id), INDEX IDX_72279D3CB8204185 (location_services_id), PRIMARY KEY(house_id, location_services_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Images (id INT AUTO_INCREMENT NOT NULL, publication_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E7B3BB5C38B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Locations (id INT AUTO_INCREMENT NOT NULL, name_via VARCHAR(255) NOT NULL, number INT NOT NULL, region VARCHAR(100) NOT NULL, province VARCHAR(100) NOT NULL, postal_code VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Publications (id INT AUTO_INCREMENT NOT NULL, id_company_id INT NOT NULL, house_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, date DATE NOT NULL, hour TIME NOT NULL, details LONGTEXT NOT NULL, INDEX IDX_2A49E10C32119A01 (id_company_id), UNIQUE INDEX UNIQ_2A49E10C6BB74515 (house_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Users (id INT AUTO_INCREMENT NOT NULL, cif_company_id INT DEFAULT NULL, location_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', img_profile LONGTEXT DEFAULT NULL, password VARCHAR(255) NOT NULL, phone INT DEFAULT NULL, nickname VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_D5428AEDE7927C74 (email), UNIQUE INDEX UNIQ_D5428AEDF0599129 (cif_company_id), UNIQUE INDEX UNIQ_D5428AED64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_publication (user_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_627AEECA76ED395 (user_id), INDEX IDX_627AEEC38B217A7 (publication_id), PRIMARY KEY(user_id, publication_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Videos (id INT AUTO_INCREMENT NOT NULL, publication_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_2E06610438B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_services (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Comments ADD CONSTRAINT FK_A6E8F47C38B217A7 FOREIGN KEY (publication_id) REFERENCES Publications (id)');
        $this->addSql('ALTER TABLE Comments ADD CONSTRAINT FK_A6E8F47CA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('ALTER TABLE house_feature ADD CONSTRAINT FK_8B00F37B6BB74515 FOREIGN KEY (house_id) REFERENCES Houses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE house_feature ADD CONSTRAINT FK_8B00F37B60E4B879 FOREIGN KEY (feature_id) REFERENCES Features (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE house_location_services ADD CONSTRAINT FK_72279D3C6BB74515 FOREIGN KEY (house_id) REFERENCES Houses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE house_location_services ADD CONSTRAINT FK_72279D3CB8204185 FOREIGN KEY (location_services_id) REFERENCES location_services (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Images ADD CONSTRAINT FK_E7B3BB5C38B217A7 FOREIGN KEY (publication_id) REFERENCES Publications (id)');
        $this->addSql('ALTER TABLE Publications ADD CONSTRAINT FK_2A49E10C32119A01 FOREIGN KEY (id_company_id) REFERENCES Companies (id)');
        $this->addSql('ALTER TABLE Publications ADD CONSTRAINT FK_2A49E10C6BB74515 FOREIGN KEY (house_id) REFERENCES Houses (id)');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AEDF0599129 FOREIGN KEY (cif_company_id) REFERENCES Companies (id)');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AED64D218E FOREIGN KEY (location_id) REFERENCES Locations (id)');
        $this->addSql('ALTER TABLE user_publication ADD CONSTRAINT FK_627AEECA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_publication ADD CONSTRAINT FK_627AEEC38B217A7 FOREIGN KEY (publication_id) REFERENCES Publications (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Videos ADD CONSTRAINT FK_2E06610438B217A7 FOREIGN KEY (publication_id) REFERENCES Publications (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Comments DROP FOREIGN KEY FK_A6E8F47C38B217A7');
        $this->addSql('ALTER TABLE Comments DROP FOREIGN KEY FK_A6E8F47CA76ED395');
        $this->addSql('ALTER TABLE house_feature DROP FOREIGN KEY FK_8B00F37B6BB74515');
        $this->addSql('ALTER TABLE house_feature DROP FOREIGN KEY FK_8B00F37B60E4B879');
        $this->addSql('ALTER TABLE house_location_services DROP FOREIGN KEY FK_72279D3C6BB74515');
        $this->addSql('ALTER TABLE house_location_services DROP FOREIGN KEY FK_72279D3CB8204185');
        $this->addSql('ALTER TABLE Images DROP FOREIGN KEY FK_E7B3BB5C38B217A7');
        $this->addSql('ALTER TABLE Publications DROP FOREIGN KEY FK_2A49E10C32119A01');
        $this->addSql('ALTER TABLE Publications DROP FOREIGN KEY FK_2A49E10C6BB74515');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AEDF0599129');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AED64D218E');
        $this->addSql('ALTER TABLE user_publication DROP FOREIGN KEY FK_627AEECA76ED395');
        $this->addSql('ALTER TABLE user_publication DROP FOREIGN KEY FK_627AEEC38B217A7');
        $this->addSql('ALTER TABLE Videos DROP FOREIGN KEY FK_2E06610438B217A7');
        $this->addSql('DROP TABLE Comments');
        $this->addSql('DROP TABLE Companies');
        $this->addSql('DROP TABLE Features');
        $this->addSql('DROP TABLE Houses');
        $this->addSql('DROP TABLE house_feature');
        $this->addSql('DROP TABLE house_location_services');
        $this->addSql('DROP TABLE Images');
        $this->addSql('DROP TABLE Locations');
        $this->addSql('DROP TABLE Publications');
        $this->addSql('DROP TABLE Users');
        $this->addSql('DROP TABLE user_publication');
        $this->addSql('DROP TABLE Videos');
        $this->addSql('DROP TABLE location_services');
    }
}
