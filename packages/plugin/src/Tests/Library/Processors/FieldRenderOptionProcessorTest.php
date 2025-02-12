<?php

namespace Solspace\Freeform\Tests\Library\Processors;

use PHPUnit\Framework\TestCase;
use Solspace\Freeform\Fields\Implementations\DropdownField;
use Solspace\Freeform\Fields\Implementations\Pro\TableField;
use Solspace\Freeform\Fields\Implementations\TextField;
use Solspace\Freeform\Form\Form;
use Solspace\Freeform\Library\Processors\FieldRenderOptionProcessor;
use yii\di\Container;

/**
 * @internal
 *
 * @coversNothing
 */
class FieldRenderOptionProcessorTest extends TestCase
{
    private FieldRenderOptionProcessor $processor;

    protected function setUp(): void
    {
        $containerMock = $this->createMock(Container::class);
        $containerMock->method('get')->willReturnCallback(
            fn (string $className) => new $className()
        );

        $this->processor = $this->createPartialMock(FieldRenderOptionProcessor::class, ['getContainer']);
        $this->processor->method('getContainer')->willReturn($containerMock);
    }

    public function testAttributesByType(): void
    {
        $properties = [
            '@global' => [
                'attributes' => [
                    'input' => [
                        'class' => 'one',
                    ],
                ],
            ],
            '@text' => [
                'attributes' => [
                    'input' => [
                        'class' => 'text-class',
                        'placeholder' => 'text-placeholder',
                    ],
                ],
            ],
            '@dropdown, @text' => [
                'attributes' => [
                    'input' => [
                        'class' => 'dropdown-n-text',
                        'placeholder' => 'dropdown-placeholder',
                    ],
                ],
            ],
            '@text, @table' => [
                'attributes' => [
                    'input' => ['class' => 'table-n-text'],
                ],
            ],
            '@table' => [
                'attributes' => [
                    'input' => ['class' => 'table-class'],
                ],
            ],
        ];

        $formMock = $this->createMock(Form::class);
        $field = new TextField($formMock);

        $this->processor->process($properties, $field);
        $output = (string) $field->getAttributes()->getInput();

        $this->assertSame(
            ' class="one text-class dropdown-n-text table-n-text" placeholder="text-placeholder dropdown-placeholder"',
            $output
        );
    }

    public function testAttributesByHandle(): void
    {
        $properties = [
            '@global' => [
                'attributes' => [
                    'input' => [
                        'class' => 'one',
                    ],
                ],
            ],
            'myTextField' => [
                'value' => 'test',
                'attributes' => [
                    'input' => ['id' => 'my-text-field'],
                ],
            ],
        ];

        $formMock = $this->createMock(Form::class);
        $field = $this->getMockBuilder(TextField::class)
            ->setConstructorArgs([$formMock])
            ->onlyMethods(['getHandle'])
            ->getMock()
        ;

        $field->method('getHandle')->willReturn('myTextField');

        $this->processor->process($properties, $field);
        $output = (string) $field->getAttributes()->getInput();

        $this->assertSame(' class="one" id="my-text-field"', $output);
        $this->assertSame('test', $field->getValue());
    }

    public function testAttributesByImplementation(): void
    {
        $properties = [
            '@global' => [
                'attributes' => [
                    'input' => [
                        'class' => 'one',
                    ],
                ],
            ],
            ':options' => [
                'attributes' => [
                    'input' => ['class' => 'options-field'],
                ],
            ],
        ];

        $formMock = $this->createMock(Form::class);
        $field = new DropdownField($formMock);

        $this->processor->process($properties, $field);
        $output = (string) $field->getAttributes()->getInput();

        $this->assertSame(' class="one options-field"', $output);
    }

    public function testAttributesByMetaStatus(): void
    {
        $properties = [
            '@global' => [
                'attributes' => [
                    'label' => [
                        'class' => 'one',
                    ],
                ],
            ],
            ':required' => [
                'attributes' => [
                    'label' => ['class' => 'required'],
                ],
            ],
            ':errors' => [
                'attributes' => [
                    'label' => ['class' => 'has-errors'],
                ],
            ],
        ];

        $formMock = $this->createMock(Form::class);
        $field = $this->getMockBuilder(TextField::class)->setConstructorArgs([$formMock])->onlyMethods(
            ['getHandle', 'isRequired', 'hasErrors']
        )->getMock();

        $field->method('getHandle')->willReturn('myTextField');
        $field->method('isRequired')->willReturn(true);
        $field->method('hasErrors')->willReturn(true);

        $this->processor->process($properties, $field);
        $output = (string) $field->getAttributes()->getLabel();

        $this->assertSame(' class="one required has-errors"', $output);
    }

    public function testAttributesByNotMetaStatus(): void
    {
        $properties = [
            '@global' => [
                'attributes' => [
                    'label' => [
                        'class' => 'one',
                    ],
                ],
            ],
            ':required' => [
                'attributes' => [
                    'label' => ['class' => 'required'],
                ],
            ],
            ':errors' => [
                'attributes' => [
                    'label' => ['class' => 'has-errors'],
                ],
            ],
        ];

        $formMock = $this->createMock(Form::class);
        $field = $this->getMockBuilder(TextField::class)
            ->setConstructorArgs([$formMock])
            ->onlyMethods(['getHandle'])
            ->getMock()
        ;

        $field->method('getHandle')->willReturn('myTextField');

        $this->processor->process($properties, $field);
        $output = (string) $field->getAttributes()->getLabel();

        $this->assertSame(' class="one"', $output);
    }

    public function testAttributesByCombinedStatus(): void
    {
        $properties = [
            '@global' => [
                'attributes' => [
                    'label' => [
                        'class' => 'one',
                    ],
                ],
            ],
            ':required, @text, :placeholder, :errors, #myTextField' => [
                'attributes' => [
                    'label' => ['class' => 'combined'],
                ],
            ],
        ];

        $formMock = $this->createMock(Form::class);
        $field = $this->getMockBuilder(TextField::class)->setConstructorArgs([$formMock])->onlyMethods(
            ['getHandle', 'hasErrors']
        )->getMock();

        $field->method('getHandle')->willReturn('myTextField');
        $field->method('hasErrors')->willReturn(true);

        $this->processor->process($properties, $field);
        $output = (string) $field->getAttributes()->getLabel();

        $this->assertSame(' class="one combined"', $output);
    }

    public function testPassedTableAttributes(): void
    {
        $properties = [
            '@global' => [
                'attributes' => [
                    'label' => [
                        'class' => 'one',
                    ],
                ],
            ],
            '@table' => [
                'attributes' => [
                    'label' => ['class' => 'two'],
                ],
                'tableAttributes' => [
                    'row' => ['test' => 'value'],
                ],
            ],
        ];

        $formMock = $this->createMock(Form::class);
        $field = new TableField($formMock);

        $this->processor->process($properties, $field);

        $output = (string) $field->getAttributes()->getLabel();
        $tableOutput = (string) $field->getTableAttributes()->getRow();

        $this->assertSame(' class="one two"', $output);
        $this->assertSame(' test="value"', $tableOutput);
    }
}
