<?php
/*
 * This file is part of prooph/snapshot-doctrine-adapter.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 10/11/15 - 9:22 PM
 */

namespace Prooph\EventStore\Snapshot\Adapter\Doctrine\Schema;

use Doctrine\DBAL\Schema\Schema;

/**
 * Class SnapshotStoreSchema
 *
 * Use this helper in a doctrine migrations script to set up the snapshot store schema
 *
 * @package Prooph\EventStore\Snapshot\Adapter\Doctrine\Schema
 */
final class SnapshotStoreSchema
{
    /**
     * Use this method when you work with a single stream strategy
     *
     * @param Schema $schema
     * @param string $snapshotName Defaults to 'snapshot'
     */
    public static function create(Schema $schema, $snapshotName = 'snapshot')
    {
        $snapshot = $schema->createTable($snapshotName);

        // UUID4 of linked aggregate
        $snapshot->addColumn('aggregate_id', 'string', ['fixed' => true, 'length' => 36]);
        // Class of the linked aggregate
        $snapshot->addColumn('aggregate_type', 'string', ['length' => 150]);
        // Version of the aggregate after event was recorded
        $snapshot->addColumn('last_version', 'integer', ['unsigned' => true]);
        // DateTime ISO8601 + microseconds UTC stored as a string e.g. 2016-02-02T11:45:39.000000
        $snapshot->addColumn('created_at', 'string', ['fixed' => true, 'length' => 26]);
        $snapshot->addColumn('aggregate_root', 'blob');

        $snapshot->addIndex(['aggregate_id', 'aggregate_type']);
    }

    /**
     * Drop a stream schema
     *
     * @param Schema $schema
     * @param string $snapshotName Defaults to 'snapshot'
     */
    public static function drop(Schema $schema, $snapshotName = 'snapshot')
    {
        $schema->dropTable($snapshotName);
    }
}
