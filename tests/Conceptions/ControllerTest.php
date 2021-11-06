<?php declare(strict_types=1);

namespace Respector\Test\Conceptions;

use Generator;
use Respector\Analyze;
use Respector\ParsedFile;

abstract class ControllerTest extends BaseConceptionTest
{
    /**
     * @dataProvider getAnalyzedFiles
     */
    public function testIfControllerHasNewRouteSyntax(ParsedFile $parsedFile): void
    {
        $class = $parsedFile->getClass();
        $this->assertStringEndsWith('Controller', $class->getName());
        $this->assertIsFinal($class);
    }

    /**
     * @dataProvider getAnalyzedFiles
     */
    public function testIfControllerUsedAttributeRoute(ParsedFile $parsedFile): void
    {
        $class = $parsedFile->getClass();
        $this->assertHasAttributeRoute($class);
    }

    public function getAnalyzedFiles(): Generator
    {
        return Analyze::create('composer.json')
            ->inNamespace('Infrastructure\\Symfony\\Controller')
            ->getDataProvider();
    }
}
