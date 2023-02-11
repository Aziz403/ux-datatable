<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Builder;

use Aziz403\UX\Datatable\Model\ArrayDatatable;
use Aziz403\UX\Datatable\Model\EntityDatatable;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableBuilder implements DatatableBuilderInterface
{
    private string $locale;

    private EventDispatcherInterface $dispatcher;
    private TranslatorInterface $translator;

    private array $config;

    public function __construct(
        RequestStack $requestStack,
        EventDispatcherInterface $dispatcher,
        TranslatorInterface $translator,
        array $config
    )
    {
        $this->locale = $requestStack->getCurrentRequest()?->getLocale() ?? 'en';

        $this->dispatcher = $dispatcher;
        $this->translator = $translator;

        $this->config = $config;
    }

    public function createDatatableFromEntity(string $className): EntityDatatable
    {
        return new EntityDatatable(
            $className,
            $this->dispatcher,
            $this->translator,
            $this->config,
            $this->locale
        );
    }

    public function createDatatableFromArray(array $columns, array $data): ArrayDatatable
    {
        return (new ArrayDatatable(
            $this->dispatcher,
            $this->translator,
            $this->config,
            $this->locale,
            $data
        ))
        ->setColumns($columns);
    }
}