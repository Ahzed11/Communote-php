<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210327134836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE course_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE faculty_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE note_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE note_file_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE school_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE study_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE year_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, note_id INT NOT NULL, author_id INT NOT NULL, body TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C26ED0855 ON comment (note_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE TABLE course (id INT NOT NULL, study_id INT DEFAULT NULL, year_id INT NOT NULL, title VARCHAR(127) NOT NULL, code VARCHAR(31) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_169E6FB9E7B003E9 ON course (study_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB940C1FEA7 ON course (year_id)');
        $this->addSql('CREATE TABLE faculty (id INT NOT NULL, school_id INT NOT NULL, title VARCHAR(127) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_17966043C32A47EE ON faculty (school_id)');
        $this->addSql('CREATE TABLE note (id INT NOT NULL, course_id INT NOT NULL, note_file_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(127) NOT NULL, short_description VARCHAR(127) DEFAULT NULL, description TEXT NOT NULL, slug VARCHAR(127) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFBDFA14989D9B62 ON note (slug)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14591CC992 ON note (course_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFBDFA14126DBDF6 ON note (note_file_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14F675F31B ON note (author_id)');
        $this->addSql('CREATE TABLE note_file (id INT NOT NULL, file_name VARCHAR(255) NOT NULL, original_filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE report (id INT NOT NULL, note_id INT NOT NULL, author_id INT NOT NULL, body TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C42F778426ED0855 ON report (note_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784F675F31B ON report (author_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, note_id INT NOT NULL, author_id INT NOT NULL, score SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C626ED0855 ON review (note_id)');
        $this->addSql('CREATE INDEX IDX_794381C6F675F31B ON review (author_id)');
        $this->addSql('CREATE TABLE school (id INT NOT NULL, title VARCHAR(127) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE study (id INT NOT NULL, faculty_id INT NOT NULL, title VARCHAR(127) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E67F9749680CAB68 ON study (faculty_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, azure_oid VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE user_school (user_id INT NOT NULL, school_id INT NOT NULL, PRIMARY KEY(user_id, school_id))');
        $this->addSql('CREATE INDEX IDX_9CCCC186A76ED395 ON user_school (user_id)');
        $this->addSql('CREATE INDEX IDX_9CCCC186C32A47EE ON user_school (school_id)');
        $this->addSql('CREATE TABLE year (id INT NOT NULL, title VARCHAR(31) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C26ED0855 FOREIGN KEY (note_id) REFERENCES note (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB940C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE faculty ADD CONSTRAINT FK_17966043C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14126DBDF6 FOREIGN KEY (note_file_id) REFERENCES note_file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778426ED0855 FOREIGN KEY (note_id) REFERENCES note (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C626ED0855 FOREIGN KEY (note_id) REFERENCES note (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE study ADD CONSTRAINT FK_E67F9749680CAB68 FOREIGN KEY (faculty_id) REFERENCES faculty (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_school ADD CONSTRAINT FK_9CCCC186A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_school ADD CONSTRAINT FK_9CCCC186C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14591CC992');
        $this->addSql('ALTER TABLE study DROP CONSTRAINT FK_E67F9749680CAB68');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C26ED0855');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F778426ED0855');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C626ED0855');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14126DBDF6');
        $this->addSql('ALTER TABLE faculty DROP CONSTRAINT FK_17966043C32A47EE');
        $this->addSql('ALTER TABLE user_school DROP CONSTRAINT FK_9CCCC186C32A47EE');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB9E7B003E9');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE note DROP CONSTRAINT FK_CFBDFA14F675F31B');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784F675F31B');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE user_school DROP CONSTRAINT FK_9CCCC186A76ED395');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB940C1FEA7');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE course_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE faculty_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE note_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE note_file_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE report_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE school_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE study_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE year_id_seq CASCADE');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE faculty');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE note_file');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE study');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_school');
        $this->addSql('DROP TABLE year');
    }
}
