<?php declare(strict_types=1);

namespace Respector;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Respector\Exception\ComposerFileNotFound;
use SplFileInfo;

final class Analyze
{
    public function __construct(private string $composerFilePath)
    {
    }

    /**
     * @return static
     * @throws \Exception
     */
    public static function create(string $composerJson): self
    {
        if (! file_exists($composerJson)) {
            throw new ComposerFileNotFound('The composer file does not exist.');
        }

        return new self($composerJson);
    }

    public function inNamespace(string $namespace): ParsedFileCollection
    {
        $parsedFileCollection = [];
        $dependencies = $this->getComposerDependencies($namespace);
        if ($dependencies === null) {
            throw new \Exception('Not dependencies found for the namespace : ' . $namespace);
        }
        /** @var SplFileInfo[] $objects */
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dependencies));

        foreach ($objects as $object) {
            if (in_array($object->getFilename(), ['.', '..'])) {
                continue;
            }
            $parsedFileCollection[] = new ParsedFile($object->getPath(), $object->getFilename());
        }

        return new ParsedFileCollection($parsedFileCollection);
    }

    private function getComposerDependencies(string $namespaceFilter): ?string
    {
        /** @var string $contentFile */
        $contentFile = file_get_contents($this->composerFilePath);
        $composerContentFile = json_decode($contentFile, true);
        if (isset($composerContentFile['autoload']['psr-4'][$namespaceFilter])) {
            return $composerContentFile['autoload']['psr-4'][$namespaceFilter];
        }
        $composerPath = pathinfo($this->composerFilePath, PATHINFO_DIRNAME);
        $composerFilePath = $this->searchComposerFilePath('composer.json', $composerPath);
        $vendorPath = pathinfo($composerPath, PATHINFO_DIRNAME);
        $mappers = require $vendorPath . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . 'autoload_classmap.php';
        foreach ($mappers as $namespace => $classFile) {
            if (str_contains($namespace, $namespaceFilter)) {
                return pathinfo($classFile, PATHINFO_DIRNAME);
            }
        }

        return null;
    }

    private function searchComposerFilePath(string $composerFileName, string $directory): ?string
    {
        $current = null;
        $path = null;
        /** @var int<1, max> $i */
        $i = 1;
        do {
            $currentDir = dirname($directory, $i);
            $files = scandir($currentDir);
            if ($files === false) {
                throw new \Exception('Search composer file path error.');
            }
            foreach ($files as $file) {
                if ($file === $composerFileName) {
                    $current = $file;
                    /** @var string $path */
                    $path = realpath($currentDir . DIRECTORY_SEPARATOR . $composerFileName);
                    break;
                }
            }
            $i++;
        } while ($composerFileName !== $current);

        return $path;
    }
}
