<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatform\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Tests\Fixtures\TestBundle\ApiResource\IssueXXXX\OperationWithHalFormat;
use ApiPlatform\Tests\SetupClassResourcesTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HalErrorFormatAppKernel extends \AppKernel
{
    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new class implements CompilerPassInterface {
            public function process(ContainerBuilder $container): void
            {
                $def = $container->getDefinition('api_platform.error_listener');
                $def->setArgument(5, ['jsonhal' => ['application/hal+json']]);
            }
        });
    }
}

final class HalErrorFormatTest extends ApiTestCase
{
    use SetupClassResourcesTrait;

    protected static function getKernelClass(): string
    {
        return HalErrorFormatAppKernel::class;
    }

    protected static ?bool $alwaysBootKernel = true;

    /**
     * @return class-string[]
     */
    public static function getResources(): array
    {
        return [OperationWithHalFormat::class];
    }

    public function testValidationErrorInHalFormat(): void
    {
        self::createClient()->request(
            'POST',
            '/operation_with_hal_formats',
            [
                'headers' => [
                    'accept' => 'application/hal+json',
                    'Content-Type' => 'application/json',
                ],

                'json' => [],
            ]
        );

        $this->assertResponseStatusCodeSame(422);

        $this->assertJsonContains(
            [
                'detail' => 'name: This value should not be blank.',
                'violations' => [
                    [
                        'propertyPath' => 'name',
                    ],
                ],
            ]
        );
    }
}
