<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218091328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, price DOUBLE PRECISION NOT NULL, duration VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD rating DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter ADD CONSTRAINT FK_7E8585C8E899029B FOREIGN KEY (plan_id) REFERENCES subscription_plan (id)');
        $this->addSql('CREATE INDEX IDX_7E8585C8E899029B ON newsletter (plan_id)');
        $this->addSql('ALTER TABLE product ADD picture VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD plan_id INT NOT NULL, ADD user_id INT DEFAULT NULL, ADD status TINYINT(1) NOT NULL, DROP price, DROP duration');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3E899029B FOREIGN KEY (plan_id) REFERENCES subscription_plan (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A3C664D3E899029B ON subscription (plan_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A3C664D3A76ED395 ON subscription (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter DROP FOREIGN KEY FK_7E8585C8E899029B');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3E899029B');
        $this->addSql('DROP TABLE subscription_plan');
        $this->addSql('ALTER TABLE game DROP rating');
        $this->addSql('DROP INDEX IDX_7E8585C8E899029B ON newsletter');
        $this->addSql('ALTER TABLE product DROP picture');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('DROP INDEX IDX_A3C664D3E899029B ON subscription');
        $this->addSql('DROP INDEX UNIQ_A3C664D3A76ED395 ON subscription');
        $this->addSql('ALTER TABLE subscription ADD price DOUBLE PRECISION NOT NULL, ADD duration DATE NOT NULL, DROP plan_id, DROP user_id, DROP status');
    }
}
