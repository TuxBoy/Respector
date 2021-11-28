<?php declare(strict_types=1);

namespace Respector\Test;

use PHPUnit\Framework\TestCase;
use Respector\Analyze;
use Respector\Exception\ComposerFileNotFound;
use Respector\ParsedFile;
use Respector\ParsedFileCollection;

final class AnalyzerTest extends TestCase
{
    public function testCreateAnalyzerWithComposerJson(): void
    {
        $analyzer = Analyze::create(__DIR__ . '/composer_fake.json');

        $this->assertInstanceOf(Analyze::class, $analyzer);
    }

    public function testCreateAnalyzerWithComposerJsonDoesNotExist(): void
    {
        $this->expectException(ComposerFileNotFound::class);
        $this->expectExceptionMessage('The composer file does not exist.');

        Analyze::create(__DIR__ . '/unknown.json');
    }

    public function testIsInNamespaceReturnParsedCollectionFile(): void
    {
        $analyzer = Analyze::create(__DIR__ . '/composer_fake.json');
        $parsedFileCollection = $analyzer->inNamespace('Respector\Test\Fake');

        $this->assertInstanceOf(ParsedFileCollection::class, $parsedFileCollection);
        $this->assertEquals([
            $this->makeParsedFile(sprintf('tests%sFake%sController', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR), 'DummyController.php'),
            $this->makeParsedFile(path: 'tests/Fake', filename: 'Foo.php'),
        ], iterator_to_array($parsedFileCollection));
    }

    /** TODO implements test case */
    public function IsNamespaceWithComposerMapper(): void
    {
        $analyzer = Analyze::create(__DIR__ . '/composer_fake.json');
        $parsedFileCollection = $analyzer->inNamespace('Respector\Test\Vendor');

        $this->assertInstanceOf(ParsedFileCollection::class, $parsedFileCollection);
        $this->assertEquals([
            $this->makeParsedFile(sprintf('tests%sFake%sController', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR), 'DummyController.php'),
            $this->makeParsedFile(path: 'tests/Fake', filename: 'Foo.php'),
        ], iterator_to_array($parsedFileCollection));
    }

    private function makeParsedFile(string $path = '/path/to/', string $filename = 'foo', string $namespace = 'Respector\Test\Fake'): ParsedFile
    {
        return new ParsedFile($path, $filename, $namespace);
    }
}
