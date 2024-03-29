<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221023154244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
            );

        $this->addSql("CREATE TABLE nuptic
                        (
                            id           VARCHAR(36)  NOT NULL,
                            simulator_id VARCHAR(255) NOT NULL,
                            num          INT          NOT NULL,
                            direction    VARCHAR(5)   NOT NULL,
                            route        INT          NOT NULL,
                            created_at   DATE         NOT NULL COMMENT '(DC2Type:date_immutable)',
                            PRIMARY KEY (id)
                        ) DEFAULT CHARACTER SET utf8mb4
                          COLLATE `utf8mb4_unicode_ci`
                          ENGINE = InnoDB;");

        $this->addSql("CREATE TABLE messenger_messages
                            (
                                id           BIGINT AUTO_INCREMENT NOT NULL,
                                body         LONGTEXT              NOT NULL,
                                headers      LONGTEXT              NOT NULL,
                                queue_name   VARCHAR(190)          NOT NULL,
                                created_at   DATETIME              NOT NULL,
                                available_at DATETIME              NOT NULL,
                                delivered_at DATETIME DEFAULT NULL,
                                INDEX IDX_75EA56E0FB7336F0 (queue_name),
                                INDEX IDX_75EA56E0E3BD61CE (available_at),
                                INDEX IDX_75EA56E016BA31DB (delivered_at),
                                PRIMARY KEY (id)
                            ) DEFAULT CHARACTER SET utf8mb4
                              COLLATE `utf8mb4_unicode_ci`
                              ENGINE = InnoDB;
                    ");
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
            );

        $this->addSql('DROP TABLE nuptic');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
