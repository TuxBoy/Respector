<?php declare(strict_types=1);

namespace Respector\Test\Conceptions;

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

    public function testCountMigrations(): void
    {
        $analyzer = Analyze::create('composer.json')
            ->inNamespace('Infrastructure\\Doctrine\\Migrations');

        $this->assertCount(2, $analyzer);
    }
}
