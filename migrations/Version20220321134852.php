<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321134852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE billet (id INT AUTO_INCREMENT NOT NULL, localisation_id INT NOT NULL, chair_billet INT NOT NULL, voyage_num INT NOT NULL, terminal INT NOT NULL, portail INT NOT NULL, embarquement DATE NOT NULL, INDEX IDX_1F034AF6C68BE09C (localisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_equipement (id INT AUTO_INCREMENT NOT NULL, nom_categorie_equipement VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_267D0C5F2774904 (nom_categorie_equipement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_post (id INT AUTO_INCREMENT NOT NULL, nom_categorie_post VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, date_commentaire DATE NOT NULL, msg_commentaire LONGTEXT NOT NULL, INDEX IDX_67F068BC4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, categorie_equipement_id INT DEFAULT NULL, nom_equipement VARCHAR(255) NOT NULL, etat_equipement VARCHAR(255) NOT NULL, description_equipement LONGTEXT NOT NULL, image_equipement VARCHAR(255) NOT NULL, INDEX IDX_B8B4C6F383A0EE16 (categorie_equipement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localisation (id INT AUTO_INCREMENT NOT NULL, heure_depart_localisation TIME NOT NULL, heure_arrivee_loacalisation TIME NOT NULL, position_depart_localisation VARCHAR(255) NOT NULL, position_arivee_planning VARCHAR(255) NOT NULL, fusee VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, planning_id INT DEFAULT NULL, nom_offre VARCHAR(255) NOT NULL, description_offre LONGTEXT NOT NULL, prix_offre DOUBLE PRECISION NOT NULL, reduction DOUBLE PRECISION DEFAULT NULL, date_debut_offre DATE DEFAULT NULL, date_fin_offre DATE DEFAULT NULL, INDEX IDX_AF86866F3D865311 (planning_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planinng (id INT AUTO_INCREMENT NOT NULL, nom_planning VARCHAR(255) NOT NULL, date_debut_planning DATE NOT NULL, date_fin_planning DATE NOT NULL, destination_planning VARCHAR(255) NOT NULL, description_planning LONGTEXT NOT NULL, periode_planning DOUBLE PRECISION NOT NULL, prix_planning DOUBLE PRECISION NOT NULL, img_planinng VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, categorie_post_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, img_post VARCHAR(255) NOT NULL, description_post LONGTEXT NOT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D140AAD8E (categorie_post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, description_reclamation LONGTEXT NOT NULL, date_reclamation DATE NOT NULL, etat_reclamation VARCHAR(255) DEFAULT NULL, INDEX IDX_CE606404A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, billet_id INT NOT NULL, date_reservation DATE NOT NULL, etat_reservation VARCHAR(255) NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C8495544973C78 (billet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, description_id INT DEFAULT NULL, nom_role VARCHAR(255) NOT NULL, description_role LONGTEXT NOT NULL, INDEX IDX_57698A6AD9F966B (description_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, deadline DATE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, nom_role_id INT DEFAULT NULL, nom_utilisateur VARCHAR(255) DEFAULT NULL, prenom_utilisateur VARCHAR(255) DEFAULT NULL, adresse_utilisateur VARCHAR(255) DEFAULT NULL, mail_utilisateur VARCHAR(255) NOT NULL, sudo_utilisateur VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, is_expired TINYINT(1) NOT NULL, etat_compte VARCHAR(255) DEFAULT NULL, numero_utilisateur VARCHAR(255) DEFAULT NULL, date_n_utilisateur DATE DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, google_access_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649A9C31E6E (mail_utilisateur), INDEX IDX_8D93D6494B89032C (post_id), INDEX IDX_8D93D649238C2964 (nom_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF6C68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F383A0EE16 FOREIGN KEY (categorie_equipement_id) REFERENCES categorie_equipement (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F3D865311 FOREIGN KEY (planning_id) REFERENCES planinng (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D140AAD8E FOREIGN KEY (categorie_post_id) REFERENCES categorie_post (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495544973C78 FOREIGN KEY (billet_id) REFERENCES billet (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AD9F966B FOREIGN KEY (description_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649238C2964 FOREIGN KEY (nom_role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495544973C78');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F383A0EE16');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D140AAD8E');
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF6C68BE09C');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F3D865311');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC4B89032C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494B89032C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649238C2964');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6AD9F966B');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE billet');
        $this->addSql('DROP TABLE categorie_equipement');
        $this->addSql('DROP TABLE categorie_post');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE localisation');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE planinng');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user');
    }
}
