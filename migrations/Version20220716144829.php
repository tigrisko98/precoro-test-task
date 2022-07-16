<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220716144829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `products_to_orders` (product_id INT NOT NULL, order_id INT NOT NULL, quantity INT NOT NULL, product_price DOUBLE PRECISION NOT NULL, INDEX IDX_918012554584665A (product_id), INDEX IDX_91801255FCDAEAAA (order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `products_to_orders` ADD CONSTRAINT FK_918012554584665A FOREIGN KEY (product_id) REFERENCES `products` (id)');
        $this->addSql('ALTER TABLE `products_to_orders` ADD CONSTRAINT FK_91801255FCDAEAAA FOREIGN KEY (order_id) REFERENCES `orders` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `products_to_orders`');
    }
}
