<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210606000742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lang (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(5) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lang_user (lang_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_98F39F2EB213FA4 (lang_id), INDEX IDX_98F39F2EA76ED395 (user_id), PRIMARY KEY(lang_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, lang_id INT NOT NULL, name VARCHAR(255) NOT NULL, is_translated INT NOT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_2FB3D0EE9D86650F (user_id_id), INDEX IDX_2FB3D0EEB213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traduction_source (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, target LONGTEXT NOT NULL, source VARCHAR(255) NOT NULL, blocked TINYINT(1) NOT NULL, INDEX IDX_E51D494166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traduction_target (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, lang_id INT NOT NULL, target LONGTEXT NOT NULL, INDEX IDX_17B4841B953C1C61 (source_id), INDEX IDX_17B4841BB213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lang_user ADD CONSTRAINT FK_98F39F2EB213FA4 FOREIGN KEY (lang_id) REFERENCES lang (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lang_user ADD CONSTRAINT FK_98F39F2EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEB213FA4 FOREIGN KEY (lang_id) REFERENCES lang (id)');
        $this->addSql('ALTER TABLE traduction_source ADD CONSTRAINT FK_E51D494166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE traduction_target ADD CONSTRAINT FK_17B4841B953C1C61 FOREIGN KEY (source_id) REFERENCES traduction_source (id)');
        $this->addSql('ALTER TABLE traduction_target ADD CONSTRAINT FK_17B4841BB213FA4 FOREIGN KEY (lang_id) REFERENCES lang (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lang_user DROP FOREIGN KEY FK_98F39F2EB213FA4');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEB213FA4');
        $this->addSql('ALTER TABLE traduction_target DROP FOREIGN KEY FK_17B4841BB213FA4');
        $this->addSql('ALTER TABLE traduction_source DROP FOREIGN KEY FK_E51D494166D1F9C');
        $this->addSql('ALTER TABLE traduction_target DROP FOREIGN KEY FK_17B4841B953C1C61');
        $this->addSql('ALTER TABLE lang_user DROP FOREIGN KEY FK_98F39F2EA76ED395');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE9D86650F');
        $this->addSql('DROP TABLE lang');
        $this->addSql('DROP TABLE lang_user');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE traduction_source');
        $this->addSql('DROP TABLE traduction_target');
        $this->addSql('DROP TABLE user');
    }
}
