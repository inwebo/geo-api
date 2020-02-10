<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209164117 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bookmarks__bookmark_tag DROP FOREIGN KEY FK_A36EDFC0ACE71D7F');
        $this->addSql('ALTER TABLE bookmarks__bookmark_tag DROP FOREIGN KEY FK_A36EDFC0BAD26311');
        $this->addSql('CREATE TABLE commune (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, lat DOUBLE PRECISION DEFAULT NULL, population INT DEFAULT NULL, postal VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE Bookmarks');
        $this->addSql('DROP TABLE bookmarks__bookmark_tag');
        $this->addSql('DROP TABLE bookmarks__bookmarks');
        $this->addSql('DROP TABLE bookmarks__tag');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Bookmarks (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, url TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, title TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, tags TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, description TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`, dt INT NOT NULL, isPublic TINYINT(1) NOT NULL, UNIQUE INDEX hash (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bookmarks__bookmark_tag (bookmarks_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_A36EDFC0ACE71D7F (bookmarks_id), INDEX IDX_A36EDFC0BAD26311 (tag_id), PRIMARY KEY(bookmarks_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bookmarks__bookmarks (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATETIME NOT NULL, public TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_52740FCFF47645AE (url), UNIQUE INDEX UNIQ_52740FCFD1B862B8 (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bookmarks__tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_EC2E1DE85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bookmarks__bookmark_tag ADD CONSTRAINT FK_A36EDFC0ACE71D7F FOREIGN KEY (bookmarks_id) REFERENCES bookmarks__bookmarks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmarks__bookmark_tag ADD CONSTRAINT FK_A36EDFC0BAD26311 FOREIGN KEY (tag_id) REFERENCES bookmarks__tag (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE commune');
    }
}
