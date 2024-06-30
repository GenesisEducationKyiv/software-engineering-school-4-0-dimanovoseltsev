<?php

namespace tests\helpers;

use Codeception\Module;
use Codeception\TestInterface;
use yii\test\FixtureTrait;

/**
 * This helper is used to populate database with needed fixtures before any tests should be run.
 * For example - populate database with demo login user that should be used in acceptance and functional tests.
 * All fixtures will be loaded before suite will be starded and unloaded after it.
 */
class FixtureHelper extends Module
{

    /**
     * Redeclare visibility because codeception includes all public methods that not starts from "_"
     * and not excluded by module settings, in actor class.
     */
    use FixtureTrait {
        loadFixtures as public;
        fixtures as protected;
        globalFixtures as protected;
        unloadFixtures as protected;
        getFixtures as public;
        getFixture as public;
    }

    /**
     * Method called before any suite tests run. Loads User fixture login user
     * to use in acceptance and functional tests.
     * @param array $settings
     */
    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }

    /**
     * Method is called after all suite tests run
     */
    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    public function _before(TestInterface $test)
    {
        $this->unloadFixtures();
        $this->loadFixtures();
    }

    /**
     * @inheritdoc
     */
    public function fixtures(): array
    {
        return [
        ];
    }
}
