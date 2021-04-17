<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210417203634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_group_student_group (student_group_source INT NOT NULL, student_group_target INT NOT NULL, INDEX IDX_78D72DF215D21614 (student_group_source), INDEX IDX_78D72DF2C37469B (student_group_target), PRIMARY KEY(student_group_source, student_group_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_group_student_group ADD CONSTRAINT FK_78D72DF215D21614 FOREIGN KEY (student_group_source) REFERENCES student_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_group_student_group ADD CONSTRAINT FK_78D72DF2C37469B FOREIGN KEY (student_group_target) REFERENCES student_group (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE student_group_intersection');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_group_intersection (id INT AUTO_INCREMENT NOT NULL, group_intersecting_id INT NOT NULL, group_intersected_id INT NOT NULL, INDEX IDX_89D7FA386C850F4D (group_intersected_id), INDEX IDX_89D7FA38D791D15 (group_intersecting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE student_group_intersection ADD CONSTRAINT FK_89D7FA386C850F4D FOREIGN KEY (group_intersected_id) REFERENCES student_group (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE student_group_intersection ADD CONSTRAINT FK_89D7FA38D791D15 FOREIGN KEY (group_intersecting_id) REFERENCES student_group (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE student_group_student_group');
    }
}
