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
                Selector::inNamespace('app\currencies\domain'),
                Selector::inNamespace('app\shared\domain'),
                Selector::inNamespace('app\subscriptions\domain'),
            )
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('app\currencies\application'),
                Selector::inNamespace('app\currencies\infrastructure'),
                Selector::inNamespace('app\shared\application'),
                Selector::inNamespace('app\shared\infrastructure'),
                Selector::inNamespace('app\subscriptions\application'),
                Selector::inNamespace('app\subscriptions\infrastructure'),
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
                Selector::inNamespace('app\currencies\application'),
                Selector::inNamespace('app\shared\application'),
                Selector::inNamespace('app\subscriptions\application'),
            )
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('app\currencies\infrastructure'),
                Selector::inNamespace('app\shared\infrastructure'),
                Selector::inNamespace('app\subscriptions\infrastructure'),
                Selector::classname(Yii::class),
            )
            ->because('application layers doesnt depend from infrastructure layers');
    }
}
