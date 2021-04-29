<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210427071419 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, sexe VARCHAR(255) NOT NULL, tel VARCHAR(8) NOT NULL, id_photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature_mission (id INT AUTO_INCREMENT NOT NULL, mission_id INT DEFAULT NULL, candidat_id INT DEFAULT NULL, INDEX IDX_7ADE8DEABE6CAE90 (mission_id), INDEX IDX_7ADE8DEA8D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature_offre (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, offre_de_travail_id INT NOT NULL, etat VARCHAR(255) DEFAULT NULL, INDEX IDX_91FCEF3B8D0EB82 (candidat_id), INDEX IDX_91FCEF3BF14D7DA (offre_de_travail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, publication_id INT DEFAULT NULL, user_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_67F068BC38B217A7 (publication_id), INDEX IDX_67F068BCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, INDEX IDX_94D4687F8D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, candidat_expediteur_id INT DEFAULT NULL, candidat_destinataire_id INT DEFAULT NULL, date_dernier_message DATETIME DEFAULT NULL, INDEX IDX_8A8E26E9F19F0F9A (candidat_expediteur_id), INDEX IDX_8A8E26E938DBC740 (candidat_destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE education (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, niveau_education VARCHAR(255) NOT NULL, filiere VARCHAR(255) NOT NULL, etablissement VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, INDEX IDX_DB0A5ED28D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, societe_id INT NOT NULL, titre VARCHAR(255) NOT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, description VARCHAR(255) NOT NULL, all_day TINYINT(1) NOT NULL, INDEX IDX_B26681EFCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_de_travail (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, titre_emploi VARCHAR(255) NOT NULL, nom_entreprise VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, INDEX IDX_4330F3848D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, societe_id INT NOT NULL, nom VARCHAR(255) NOT NULL, filiere VARCHAR(255) NOT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, INDEX IDX_404021BFFCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interview (id INT AUTO_INCREMENT NOT NULL, candidature_offre_id INT DEFAULT NULL, difficulte INT NOT NULL, objet VARCHAR(255) NOT NULL, description VARCHAR(5000) NOT NULL, date_creation DATETIME DEFAULT NULL, INDEX IDX_CF1D3C3423929EC4 (candidature_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, publication_id INT DEFAULT NULL, type_like TINYINT(1) NOT NULL, id_user INT NOT NULL, INDEX IDX_AC6340B338B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, est_proprietaire TINYINT(1) NOT NULL, est_vu TINYINT(1) NOT NULL, INDEX IDX_B6BD307F9AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, societe_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(10383) DEFAULT NULL, date DATE NOT NULL, nombre_heures INT NOT NULL, prix_heure DOUBLE PRECISION NOT NULL, ville VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, INDEX IDX_9067F23CFCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_de_travail (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, societe_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_81CF2B1DBCF5E72D (categorie_id), INDEX IDX_81CF2B1DFCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, date DATETIME DEFAULT NULL, pourcentage_like INT DEFAULT NULL, INDEX IDX_AF3C67798D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, mission_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, reponse VARCHAR(255) NOT NULL, INDEX IDX_B6F7494EBE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revue (id INT AUTO_INCREMENT NOT NULL, candidature_offre_id INT DEFAULT NULL, nb_etoiles INT NOT NULL, objet VARCHAR(255) NOT NULL, description VARCHAR(5000) NOT NULL, date_creation DATETIME DEFAULT NULL, INDEX IDX_76244F0523929EC4 (candidature_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, tel VARCHAR(8) NOT NULL, id_photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, societe_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, is_set_up TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6498D0EB82 (candidat_id), UNIQUE INDEX UNIQ_8D93D649FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature_mission ADD CONSTRAINT FK_7ADE8DEABE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE candidature_mission ADD CONSTRAINT FK_7ADE8DEA8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE candidature_offre ADD CONSTRAINT FK_91FCEF3B8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE candidature_offre ADD CONSTRAINT FK_91FCEF3BF14D7DA FOREIGN KEY (offre_de_travail_id) REFERENCES offre_de_travail (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687F8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9F19F0F9A FOREIGN KEY (candidat_expediteur_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E938DBC740 FOREIGN KEY (candidat_destinataire_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE education ADD CONSTRAINT FK_DB0A5ED28D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EFCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE experience_de_travail ADD CONSTRAINT FK_4330F3848D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFFCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE interview ADD CONSTRAINT FK_CF1D3C3423929EC4 FOREIGN KEY (candidature_offre_id) REFERENCES candidature_offre (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B338B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CFCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE offre_de_travail ADD CONSTRAINT FK_81CF2B1DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE offre_de_travail ADD CONSTRAINT FK_81CF2B1DFCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67798D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE revue ADD CONSTRAINT FK_76244F0523929EC4 FOREIGN KEY (candidature_offre_id) REFERENCES candidature_offre (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature_mission DROP FOREIGN KEY FK_7ADE8DEA8D0EB82');
        $this->addSql('ALTER TABLE candidature_offre DROP FOREIGN KEY FK_91FCEF3B8D0EB82');
        $this->addSql('ALTER TABLE competence DROP FOREIGN KEY FK_94D4687F8D0EB82');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9F19F0F9A');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E938DBC740');
        $this->addSql('ALTER TABLE education DROP FOREIGN KEY FK_DB0A5ED28D0EB82');
        $this->addSql('ALTER TABLE experience_de_travail DROP FOREIGN KEY FK_4330F3848D0EB82');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67798D0EB82');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498D0EB82');
        $this->addSql('ALTER TABLE interview DROP FOREIGN KEY FK_CF1D3C3423929EC4');
        $this->addSql('ALTER TABLE revue DROP FOREIGN KEY FK_76244F0523929EC4');
        $this->addSql('ALTER TABLE offre_de_travail DROP FOREIGN KEY FK_81CF2B1DBCF5E72D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE candidature_mission DROP FOREIGN KEY FK_7ADE8DEABE6CAE90');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBE6CAE90');
        $this->addSql('ALTER TABLE candidature_offre DROP FOREIGN KEY FK_91FCEF3BF14D7DA');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B338B217A7');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EFCF77503');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFFCF77503');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CFCF77503');
        $this->addSql('ALTER TABLE offre_de_travail DROP FOREIGN KEY FK_81CF2B1DFCF77503');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FCF77503');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE candidature_mission');
        $this->addSql('DROP TABLE candidature_offre');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE experience_de_travail');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE interview');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE offre_de_travail');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE revue');
        $this->addSql('DROP TABLE societe');
        $this->addSql('DROP TABLE user');
    }
}
