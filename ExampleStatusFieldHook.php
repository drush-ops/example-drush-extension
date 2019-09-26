<?php

namespace Drush\Commands\example_drush_extension;

use Consolidation\AnnotatedCommand\AnnotationData;
use Consolidation\AnnotatedCommand\CommandData;
use Consolidation\OutputFormatters\Options\FormatterOptions;
use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

/**
 * This hook file demonstrates how to add a new line to the drush
 * core:status command.
 */
class ExampleStatusFieldHook extends DrushCommands
{
    /**
     * Add a new default field, 'example-status' to the core:status command.
     *
     * Without this hook, the 'example-status' field will be added to the
     * output result, but it will not be displayed unless requested, e.g.
     * via `--fields=*` or `--field=example-status`.
     *
     * Note that an alter hook is needed to actually add the field to the
     * core:status command output. Without the alter hook, this hook will
     * cause an error.
     * @see addCoreStatusField()
     *
     * @hook command-event core:status
     */
    public function addCoreStatusDefaultField(ConsoleCommandEvent $event) {
        // gets the command to be executed
        $command = $event->getCommand();
        $definition = $command->getDefinition();
        $options = $definition->getOptions();

        $default_fields = $options['fields']->getDefault();
        $default_fields .= ',example-status';
        $options['fields']->setDefault($default_fields);
    }

    /**
     * Add an 'example-status' field to the core:status command.
     *
     * Note that a command-event hook is necessary to make this field be
     * displayed by default in the `drush core:status` command.
     * @see addCoreStatusDefaultField()
     *
     * @hook alter core:status
     */
    public function addCoreStatusField($result, CommandData $commandData)
    {
        $formatter_options = $commandData->formatterOptions();
        // Add a field label for our new 'whoami' field. This is required
        // in order for this field to be selectable; otherwise, our structured
        // data formatters will remove it.
        $field_labels = $formatter_options->get(FormatterOptions::FIELD_LABELS);
        $field_labels['example-status'] = 'Example Status';
        $formatter_options->setFieldLabels($field_labels);

        // Add our data to the new example field.
        $result['example-status'] = 'Added by ' . __METHOD__;

        return $result;
    }
}
