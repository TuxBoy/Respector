<?php declare(strict_types=1);

namespace Respector\Conceptions;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Respector\ParsedFile;
use Symfony\Component\Routing\Annotation\Route;

abstract class BaseConceptionTest extends TestCase
{
    protected function assertHasNoDependencyTo(string $namespace, ParsedFile $parsedFile): void
    {
        /** @var string $classContent */
        $classContent = file_get_contents($parsedFile->getFullClass());
        preg_match_all('/(use).+/', $classContent, $matches);
        if (isset($matches[0])) {
            // Remove "use" word of line
            $uses = str_replace('use', '', $matches[0]);
            $namespaces[$parsedFile->getFilename()] = array_map('trim', $uses);
            foreach (array_filter($namespaces) as $actual) {
                foreach ($actual as $value) {
                    $this->assertFalse(
                        str_contains($value, $namespace),
                        sprintf('The bad dependency with %s in %s class', $namespace, $value)
                    );
                }
            }
        }
    }

    protected function assertIsFinal(ReflectionClass $class): void
    {
        $this->assertTrue($class->isFinal());
    }

    protected function assertHasAttributeRoute(ReflectionClass $class): void
    {
        $this->assertNotEmpty($class->getAttributes(Route::class));
    }
}
