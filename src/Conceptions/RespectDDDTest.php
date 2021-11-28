<?php declare(strict_types=1);

namespace Respector\Conceptions;

use Respector\Analyze;

abstract class RespectDDDTest extends BaseConceptionTest
{
    public function testNotInfrastructureReferencesInDomain(): void
    {
        $analyzer = Analyze::create('composer.json')->inNamespace('Domain\\');

        foreach ($analyzer as $parsedFile) {
            $this->assertHasNoDependencyTo('Infrastructure\\', $parsedFile);
            $this->assertHasNoDependencyTo('Symfony\\', $parsedFile);
        }
    }

    /**
     * Domain namespace as analyse
     */
    abstract public function domainNamespace(): string;
}
