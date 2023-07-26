<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726112212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, fk_categories_id INT DEFAULT NULL, fk_team_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, auteur VARCHAR(50) NOT NULL, date DATE NOT NULL, texte VARCHAR(255) NOT NULL, INDEX IDX_BFDD3168C6586175 (fk_categories_id), INDEX IDX_BFDD3168D943E582 (fk_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, fk_user_id INT DEFAULT NULL, fk_articles_id INT DEFAULT NULL, auteur VARCHAR(50) NOT NULL, date_heure DATETIME NOT NULL, texte VARCHAR(255) NOT NULL, commentaire VARCHAR(100) NOT NULL, INDEX IDX_D9BEC0C45741EEB9 (fk_user_id), INDEX IDX_D9BEC0C4774C5AF8 (fk_articles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168C6586175 FOREIGN KEY (fk_categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168D943E582 FOREIGN KEY (fk_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C45741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4774C5AF8 FOREIGN KEY (fk_articles_id) REFERENCES articles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168C6586175');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168D943E582');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C45741EEB9');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4774C5AF8');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE commentaires');
    }
}
