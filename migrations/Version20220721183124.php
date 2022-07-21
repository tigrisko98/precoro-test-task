<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721183124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('DROP INDEX IDX_E52FFDEEA76ED395 ON orders');
        $this->addSql('ALTER TABLE orders ADD total_price DOUBLE PRECISION NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE user_id user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE products_to_orders RENAME INDEX idx_91801255fcdaeaaa TO IDX_918012558D9F6D38');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `orders` DROP total_price, DROP created_at, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE `orders` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON `orders` (user_id)');
        $this->addSql('ALTER TABLE `products_to_orders` RENAME INDEX idx_918012558d9f6d38 TO IDX_91801255FCDAEAAA');
    }
}
