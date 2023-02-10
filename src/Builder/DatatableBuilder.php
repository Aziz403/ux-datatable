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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableBuilder implements DatatableBuilderInterface
{
    private string $locale;

    private EntityManagerInterface $manager;
    private Environment $templating;
    private TranslatorInterface $translator;

    private array $config;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $manager,
        Environment $templating,
        TranslatorInterface $translator,
        array $config
    )
    {
        $this->locale = $requestStack->getCurrentRequest()?->getLocale() ?? 'en';

        $this->manager = $manager;
        $this->templating = $templating;
        $this->translator = $translator;

        $this->config = $config;
    }

    public function createDatatableFromEntity(string $className): EntityDatatable
    {
        $repository = $this->manager->getRepository($className);
        if(!$repository){
            throw new \Exception("Not Found Repository For Class Name : ".$className);
        }
        return new EntityDatatable(
            $className,
            $repository,
            $this->templating,
            $this->translator,
            $this->config,
            $this->locale
        );
    }

    public function createDatatableFromArray(array $columns, array $data): ArrayDatatable
    {
        return (new ArrayDatatable(
            $this->templating,
            $this->translator,
            $this->config,
            $data,
            $this->locale
        ))
        ->setColumns($columns);
    }
}