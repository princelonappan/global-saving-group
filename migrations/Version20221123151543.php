<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221123151543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customer_id_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, status INT NOT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, INDEX IDX_F5299398B171EB6C (customer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_voucher (id INT AUTO_INCREMENT NOT NULL, order_id_id INT NOT NULL, customer_id_id INT NOT NULL, voucher_id_id INT NOT NULL, UNIQUE INDEX UNIQ_E5E80C93FCDAEAAA (order_id_id), INDEX IDX_E5E80C93B171EB6C (customer_id_id), UNIQUE INDEX UNIQ_E5E80C93BAFFC869 (voucher_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voucher (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(100) NOT NULL, type INT NOT NULL, discount_amount INT NOT NULL, expires_at DATETIME NOT NULL, status INT NOT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B171EB6C FOREIGN KEY (customer_id_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE order_voucher ADD CONSTRAINT FK_E5E80C93FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_voucher ADD CONSTRAINT FK_E5E80C93B171EB6C FOREIGN KEY (customer_id_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE order_voucher ADD CONSTRAINT FK_E5E80C93BAFFC869 FOREIGN KEY (voucher_id_id) REFERENCES voucher (id)');
        $this->addSql('INSERT INTO `customer` (`id`, `name`) VALUES (NULL, "Nick");');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B171EB6C');
        $this->addSql('ALTER TABLE order_voucher DROP FOREIGN KEY FK_E5E80C93FCDAEAAA');
        $this->addSql('ALTER TABLE order_voucher DROP FOREIGN KEY FK_E5E80C93B171EB6C');
        $this->addSql('ALTER TABLE order_voucher DROP FOREIGN KEY FK_E5E80C93BAFFC869');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_voucher');
        $this->addSql('DROP TABLE voucher');
    }
}
