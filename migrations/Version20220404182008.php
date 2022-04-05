<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404182008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD name VARCHAR(255) NOT NULL, ADD slug VARCHAR(255) NOT NULL, DROP name_c, DROP slug_c');
        $this->addSql('ALTER TABLE product ADD name VARCHAR(255) NOT NULL, ADD picture VARCHAR(255) NOT NULL, ADD slug VARCHAR(255) NOT NULL, DROP name_p, DROP picture_p, DROP slug_p, CHANGE price_p price INT NOT NULL, CHANGE description_p description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD name_c VARCHAR(255) NOT NULL, ADD slug_c VARCHAR(255) NOT NULL, DROP name, DROP slug');
        $this->addSql('ALTER TABLE product ADD name_p VARCHAR(255) NOT NULL, ADD picture_p VARCHAR(255) NOT NULL, ADD slug_p VARCHAR(255) NOT NULL, DROP name, DROP picture, DROP slug, CHANGE price price_p INT NOT NULL, CHANGE description description_p LONGTEXT NOT NULL');
    }
}
