<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Tests\Fixtures;

use Aziz403\UX\Datatable\DatatableBundle;
use Aziz403\UX\Datatable\Tests\Fixtures\Controller\CategoryController;
use Aziz403\UX\Datatable\Tests\Fixtures\Controller\PostController;
use Aziz403\UX\Datatable\Tests\Fixtures\Controller\Product2Controller;
use Aziz403\UX\Datatable\Tests\Fixtures\Controller\ProductController;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;
use Zenstruck\Foundry\ZenstruckFoundryBundle;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private array $datatableConfig = [
        'language_from_cdn'=> true,
        'language'=> 'en',
        'template_parameters'=> [
            'style'=> 'bootstrap5',
            'className'=> 'table table-bordered'
        ]
    ];

    public function mergeDatatableConfig(array $config){
        $this->datatableConfig = array_merge($this->datatableConfig,$config);
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new TwigBundle();
        yield new WebpackEncoreBundle();
        yield new DoctrineBundle();
        yield new DatatableBundle();
        yield new ZenstruckFoundryBundle();
    }

    protected function configureContainer(ContainerConfigurator $c): void
    {
        $c->extension('framework', [
            'secret' => '$ecret',
            'http_method_override' => false,
            'test' => true,
            'router' => ['utf8' => true],
            'secrets' => false,
            'session' => ['storage_factory_id' => 'session.storage.factory.mock_file']
        ]);

        $c->extension('twig', [
            'default_path' => '%kernel.project_dir%/tests/Fixtures/templates',
        ]);

        $c->extension('webpack_encore', [
            'output_path' => '%kernel.project_dir%/public/build'
        ]);

        $c->extension('zenstruck_foundry', [
            'auto_refresh_proxies' => false,
        ]);

        $c->extension('doctrine', [
            'dbal' => ['url' => '%env(resolve:DATABASE_URL)%'],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'auto_mapping' => true,
                'mappings' => [
                    'Test' => [
                        'is_bundle' => false,
                        'dir' => '%kernel.project_dir%/tests/Fixtures/Entity',
                        'prefix' => 'Aziz403\UX\Datatable\Tests\Fixtures\Entity',
                        'type' => 'attribute',
                        'alias' => 'Test',
                    ],
                ],
            ],
        ]);

        $c->extension('zenstruck_foundry', [
            'auto_refresh_proxies' => false,
        ]);

        $c->extension('datatable', $this->datatableConfig);

        $services = $c->services();
        $services
            ->defaults()
                ->autowire()
                ->autoconfigure()
            ->set('logger', NullLogger::class)
            ->load(__NAMESPACE__.'\\', __DIR__)
            ->exclude(['Kernel.php','Entity'])
        ;

        $services->alias('public.datatable.builder', 'datatable.builder')
            ->public();

        $services->alias('public.datatable.twig_extension', 'datatable.twig_extension')
            ->public();
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes
            ->add('test_category', '/test-category')
            ->controller(CategoryController::class);
        $routes
            ->add('test_product2', '/test-product2')
            ->controller(Product2Controller::class);
        $routes
            ->add('test_product', '/test-product/{id}')
            ->controller(ProductController::class);
        $routes
            ->add('test_post_empty', '/test-post/empty')
            ->controller([PostController::class,'empty']);
        $routes
            ->add('test_post', '/test-post')
            ->controller([PostController::class,'index']);
    }
}
