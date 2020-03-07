<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200307113927 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE blog_post (id SERIAL PRIMARY KEY, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, likes_count INT NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('
        insert into blog_post (
    title, likes_count, created_at, status
)
select
    left(md5(i::text), 20),
    floor(random() * 20 + 1)::int,
    timestamp \'2020-03-01 20:00:00\' +
    random() * (timestamp \'2020-03-20 20:00:00\' -
               timestamp \'2020-03-01 10:00:00\'),
    (array[\'new\', \'rejected\', \'in_progress\', \'completed\'])[floor(random() * 4 + 1)]
from generate_series(1, 10000000) s(i)
        ');

        $this->addSql('CREATE INDEX st_idx ON blog_post USING btree (status)');
        $this->addSql('CREATE INDEX lk_idx ON blog_post USING btree (likes_count)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE blog_post');
    }
}
