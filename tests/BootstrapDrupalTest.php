<?php
namespace ExampleDrushExtension;

use PHPUnit\Framework\TestCase;
use Drush\TestTraits\DrushTestTrait;
use TestUtils\FixturesTrait;

/**
 * This example test does not exercise our Drush extension at all; its
 * only purpose is to demonstrate how to use the fixtures class to install
 * Drupal so that a command that requires a full bootstrap may be tested.
 */
class BootstrapDrupalTest extends TestCase
{
    use FixturesTrait;

    public function setUp()
    {
        $this->fixtures()->createSut();
    }

    public function tearDown()
    {
        $this->fixtures()->tearDown();
    }

    /**
     * Test to see if an example command with a parameter can be called.
     * @covers ExampleCommands::exampleParam
     */
    public function testDrushStatus()
    {
        $this->drush('status', [], ['format' => 'json']);
        $json = $this->getOutputFromJSON();
        $this->assertEquals('Successful', $json['bootstrap']);
        $this->assertEquals('Connected', $json['db-status']);
    }
}
