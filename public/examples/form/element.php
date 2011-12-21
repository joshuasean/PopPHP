<?php

require_once '../../bootstrap.php';

use Pop\Form\Element,
    Pop\Form\Element\Checkbox,
    Pop\Form\Element\Radio,
    Pop\Form\Element\Select,
    Pop\Form\Element\Textarea,
    Pop\Form\Form;

try {

    $input = new Element('text', 'email', 'Enter your email here...');
    $input->setAttributes('size', 30);
    $input->output();

    echo '<br />' . PHP_EOL;

    $values = array('Red' => 'Red', 'Green' => 'Green', 'Blue' => 'Blue');
    $checkbox = new Select('hours', Select::HOURS_24);
    $checkbox->output();

    echo '<br />' . PHP_EOL;

    $textarea = new Textarea('comments', 'Please type a comment...');
    $textarea->setAttributes('rows', '10')
             ->setAttributes('cols', '50');
    $textarea->output();

    echo '<br />' . PHP_EOL . PHP_EOL;
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL . PHP_EOL;
}

?>