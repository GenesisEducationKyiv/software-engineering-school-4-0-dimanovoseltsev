<?php

namespace tests\arch;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Yii;

class ArchTest
{
    /**
     * @return Rule
     */
    public function testDomainDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('app\domain'),
            )
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('app\application'),
                Selector::inNamespace('app\infrastructure'),
                Selector::classname(Yii::class),
            )
            ->because('domain layers doesnt depend from other layers');
    }

    /**
     * @return Rule
     */
    public function testApplicationFromInfrastructure(): Rule
    {
        return PHPat::rule()
            ->classes(
                Selector::inNamespace('app\application'),
            )
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('app\infrastructure'),
                Selector::classname(Yii::class),
            )
            ->because('application layers doesnt depend from infrastructure layers');
    }
}
