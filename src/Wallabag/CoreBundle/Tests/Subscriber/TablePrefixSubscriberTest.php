<?php

namespace Wallabag\CoreBundle\Tests\Subscriber;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Wallabag\CoreBundle\Subscriber\TablePrefixSubscriber;

class TablePrefixSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function dataForPrefix()
    {
        return array(
            array('wallabag_', 'Wallabag\UserBundle\Entity\User', '`user`', 'user', 'wallabag_user', '"wallabag_user"', new \Doctrine\DBAL\Platforms\PostgreSqlPlatform()),
            array('wallabag_', 'Wallabag\UserBundle\Entity\User', '`user`', 'user', 'wallabag_user', '`wallabag_user`', new \Doctrine\DBAL\Platforms\MySqlPlatform()),
            array('wallabag_', 'Wallabag\UserBundle\Entity\User', '`user`', 'user', 'wallabag_user', '"wallabag_user"', new \Doctrine\DBAL\Platforms\SqlitePlatform()),

            array('wallabag_', 'Wallabag\UserBundle\Entity\User', 'user', 'user', 'wallabag_user', 'wallabag_user', new \Doctrine\DBAL\Platforms\PostgreSqlPlatform()),
            array('wallabag_', 'Wallabag\UserBundle\Entity\User', 'user', 'user', 'wallabag_user', 'wallabag_user', new \Doctrine\DBAL\Platforms\MySqlPlatform()),
            array('wallabag_', 'Wallabag\UserBundle\Entity\User', 'user', 'user', 'wallabag_user', 'wallabag_user', new \Doctrine\DBAL\Platforms\SqlitePlatform()),

            array('', 'Wallabag\UserBundle\Entity\User', '`user`', 'user', 'user', '"user"', new \Doctrine\DBAL\Platforms\PostgreSqlPlatform()),
            array('', 'Wallabag\UserBundle\Entity\User', '`user`', 'user', 'user', '`user`', new \Doctrine\DBAL\Platforms\MySqlPlatform()),
            array('', 'Wallabag\UserBundle\Entity\User', '`user`', 'user', 'user', '"user"', new \Doctrine\DBAL\Platforms\SqlitePlatform()),

            array('', 'Wallabag\UserBundle\Entity\User', 'user', 'user', 'user', 'user', new \Doctrine\DBAL\Platforms\PostgreSqlPlatform()),
            array('', 'Wallabag\UserBundle\Entity\User', 'user', 'user', 'user', 'user', new \Doctrine\DBAL\Platforms\MySqlPlatform()),
            array('', 'Wallabag\UserBundle\Entity\User', 'user', 'user', 'user', 'user', new \Doctrine\DBAL\Platforms\SqlitePlatform()),
        );
    }

    /**
     * @dataProvider dataForPrefix
     */
    public function testPrefix($prefix, $entityName, $tableName, $tableNameExpected, $finalTableName, $finalTableNameQuoted, $platform)
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $subscriber = new TablePrefixSubscriber($prefix);

        $metaClass = new ClassMetadata($entityName);
        $metaClass->setPrimaryTable(array('name' => $tableName));

        $metaDataEvent = new LoadClassMetadataEventArgs($metaClass, $em);

        $this->assertEquals($tableNameExpected, $metaDataEvent->getClassMetadata()->getTableName());

        $subscriber->loadClassMetadata($metaDataEvent);

        $this->assertEquals($finalTableName, $metaDataEvent->getClassMetadata()->getTableName());
        $this->assertEquals($finalTableNameQuoted, $metaDataEvent->getClassMetadata()->getQuotedTableName($platform));
    }

    /**
     * @dataProvider dataForPrefix
     */
    public function testSubscribedEvents($prefix, $entityName, $tableName, $tableNameExpected, $finalTableName, $finalTableNameQuoted, $platform)
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $metaClass = new ClassMetadata($entityName);
        $metaClass->setPrimaryTable(array('name' => $tableName));

        $metaDataEvent = new LoadClassMetadataEventArgs($metaClass, $em);

        $subscriber = new TablePrefixSubscriber($prefix);

        $evm = new EventManager();
        $evm->addEventSubscriber($subscriber);

        $evm->dispatchEvent('loadClassMetadata', $metaDataEvent);

        $this->assertEquals($finalTableName, $metaDataEvent->getClassMetadata()->getTableName());
        $this->assertEquals($finalTableNameQuoted, $metaDataEvent->getClassMetadata()->getQuotedTableName($platform));
    }

    public function testPrefixManyToMany()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $subscriber = new TablePrefixSubscriber('yo_');

        $metaClass = new ClassMetadata('Wallabag\UserBundle\Entity\Entry');
        $metaClass->setPrimaryTable(array('name' => 'entry'));
        $metaClass->mapManyToMany(array(
            'fieldName' => 'tags',
            'joinTable' => array('name' => null, 'schema' => null),
            'targetEntity' => 'Tag',
            'mappedBy' => null,
            'inversedBy' => 'entries',
            'cascade' => array('persist'),
            'indexBy' => null,
            'orphanRemoval' => false,
            'fetch' => 2,
        ));

        $metaDataEvent = new LoadClassMetadataEventArgs($metaClass, $em);

        $this->assertEquals('entry', $metaDataEvent->getClassMetadata()->getTableName());

        $subscriber->loadClassMetadata($metaDataEvent);

        $this->assertEquals('yo_entry', $metaDataEvent->getClassMetadata()->getTableName());
        $this->assertEquals('yo_entry_tag', $metaDataEvent->getClassMetadata()->associationMappings['tags']['joinTable']['name']);
        $this->assertEquals('yo_entry', $metaDataEvent->getClassMetadata()->getQuotedTableName(new \Doctrine\DBAL\Platforms\MySqlPlatform()));
    }
}
