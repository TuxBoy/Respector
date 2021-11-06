<?php declare(strict_types=1);

namespace Respector;

use Generator;
use IteratorAggregate;
use Traversable;

/** @implements \IteratorAggregate<int, ParsedFile> */
final class ParsedFileCollection implements IteratorAggregate
{
    /**
     * @param ParsedFile[] $items
     */
    public function __construct(private array $items)
    {
    }

    /** @return \ArrayIterator<int, ParsedFile> */
    public function getIterator(): Traversable
    {
        return (function () {
            foreach ($this->items as $key => $item) {
                yield $key => $item;
            }
        })();
    }

    public function getDataProvider(): Generator
    {
        foreach ($this->items as $key => $item) {
            yield [$item];
        }
    }
}
