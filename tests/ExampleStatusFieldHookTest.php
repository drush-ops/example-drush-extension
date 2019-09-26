<?php
namespace ExampleDrushExtension;

use PHPUnit\Framework\TestCase;
use Drush\TestTraits\DrushTestTrait;
use TestUtils\FixturesTrait;

/**
 * This test does a full bootstrap of our Drupal site-under-test,
 * and then tests to see if our example status field hook successfully
 * added the 'example-status' field to the `drush core:status` command.
 */
class ExampleStatusFieldHookTest extends TestCase
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
        if (DRUSH_MAJOR_VERSION == '8') {
            $this->markTestSkipped('Status field injection only works in Drush 9+');
        }

        $this->drush('status', [], ['format' => 'json']);
        $json = $this->getOutputFromJSON();
        $this->assertEquals('Successful', $json['bootstrap']);
        $this->assertEquals('Connected', $json['db-status']);
        $this->assertEquals('Added by Drush\Commands\example_drush_extension\ExampleStatusFieldHook::addCoreStatusField', $json['example-status']);
    }
}
