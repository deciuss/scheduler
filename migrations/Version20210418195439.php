<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210418195439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, subject_id INT NOT NULL, INDEX IDX_3BAE0AA723EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, capacity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_feature (room_id INT NOT NULL, feature_id INT NOT NULL, INDEX IDX_F3F5C98654177093 (room_id), INDEX IDX_F3F5C98660E4B879 (feature_id), PRIMARY KEY(room_id, feature_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_event (id INT AUTO_INCREMENT NOT NULL, schedule_id INT NOT NULL, room_id INT NOT NULL, timeslot_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_C7F7CAFBA40BC2D5 (schedule_id), INDEX IDX_C7F7CAFB54177093 (room_id), INDEX IDX_C7F7CAFBF920B9E9 (timeslot_id), INDEX IDX_C7F7CAFB71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, cardinality INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_group_student_group (student_group_source INT NOT NULL, student_group_target INT NOT NULL, INDEX IDX_78D72DF215D21614 (student_group_source), INDEX IDX_78D72DF2C37469B (student_group_target), PRIMARY KEY(student_group_source, student_group_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, student_group_id INT NOT NULL, hours INT NOT NULL, block_size SMALLINT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FBCE3E7A41807E1D (teacher_id), INDEX IDX_FBCE3E7A4DDF95DC (student_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_feature (subject_id INT NOT NULL, feature_id INT NOT NULL, INDEX IDX_BA55D9C423EDC87 (subject_id), INDEX IDX_BA55D9C460E4B879 (feature_id), PRIMARY KEY(subject_id, feature_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeslot (id INT AUTO_INCREMENT NOT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA723EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE room_feature ADD CONSTRAINT FK_F3F5C98654177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_feature ADD CONSTRAINT FK_F3F5C98660E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFBA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFB54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFBF920B9E9 FOREIGN KEY (timeslot_id) REFERENCES timeslot (id)');
        $this->addSql('ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE student_group_student_group ADD CONSTRAINT FK_78D72DF215D21614 FOREIGN KEY (student_group_source) REFERENCES student_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_group_student_group ADD CONSTRAINT FK_78D72DF2C37469B FOREIGN KEY (student_group_target) REFERENCES student_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A4DDF95DC FOREIGN KEY (student_group_id) REFERENCES student_group (id)');
        $this->addSql('ALTER TABLE subject_feature ADD CONSTRAINT FK_BA55D9C423EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subject_feature ADD CONSTRAINT FK_BA55D9C460E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule_event DROP FOREIGN KEY FK_C7F7CAFB71F7E88B');
        $this->addSql('ALTER TABLE room_feature DROP FOREIGN KEY FK_F3F5C98660E4B879');
        $this->addSql('ALTER TABLE subject_feature DROP FOREIGN KEY FK_BA55D9C460E4B879');
        $this->addSql('ALTER TABLE room_feature DROP FOREIGN KEY FK_F3F5C98654177093');
        $this->addSql('ALTER TABLE schedule_event DROP FOREIGN KEY FK_C7F7CAFB54177093');
        $this->addSql('ALTER TABLE schedule_event DROP FOREIGN KEY FK_C7F7CAFBA40BC2D5');
        $this->addSql('ALTER TABLE student_group_student_group DROP FOREIGN KEY FK_78D72DF215D21614');
        $this->addSql('ALTER TABLE student_group_student_group DROP FOREIGN KEY FK_78D72DF2C37469B');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A4DDF95DC');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA723EDC87');
        $this->addSql('ALTER TABLE subject_feature DROP FOREIGN KEY FK_BA55D9C423EDC87');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A41807E1D');
        $this->addSql('ALTER TABLE schedule_event DROP FOREIGN KEY FK_C7F7CAFBF920B9E9');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_feature');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE schedule_event');
        $this->addSql('DROP TABLE student_group');
        $this->addSql('DROP TABLE student_group_student_group');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE subject_feature');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE timeslot');
    }
}
