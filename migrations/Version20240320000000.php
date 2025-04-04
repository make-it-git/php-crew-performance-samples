<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240320000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create json_data table and insert test data';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE json_data (id SERIAL NOT NULL, data JSONB NOT NULL, PRIMARY KEY(id))');

        // Insert 10000 records with random JSON data
        for ($i = 0; $i < 10000; $i++) {
            $data = $this->generateRandomJsonData();
            $this->addSql('INSERT INTO json_data (data) VALUES (:data)', [
                'data' => json_encode($data)
            ]);
            gc_collect_cycles();
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE json_data');
    }

    private function generateRandomJsonData(): array
    {
        $data = [];
        $targetSize = 100_000;
        $currentSize = 0;

        while ($currentSize < $targetSize) {
            $item = [
                'id' => uniqid(),
                'timestamp' => time(),
                'values' => array_map(function() {
                    return [
                        'string' => bin2hex(random_bytes(10)),
                        'number' => random_int(-1000000, 1000000),
                        'boolean' => (bool)random_int(0, 1),
                        'array' => array_map(function() {
                            return [
                                'nested_string' => bin2hex(random_bytes(5)),
                                'nested_number' => random_int(-1000, 1000)
                            ];
                        }, range(1, random_int(1, 5)))
                    ];
                }, range(1, random_int(1, 3)))
            ];

            $itemSize = strlen(json_encode($item));
            if ($currentSize + $itemSize > $targetSize) {
                break;
            }

            $data[] = $item;
            $currentSize += $itemSize;
        }

        return $data;
    }
} 