<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\EventListener;

use Aziz403\UX\Datatable\Event\Events;
use Aziz403\UX\Datatable\Event\RenderDataEvent;
use Aziz403\UX\Datatable\Event\RenderQueryEvent;
use Aziz403\UX\Datatable\Helper\DatatableQueriesHelper;
use Aziz403\UX\Datatable\Helper\DatatableTemplatingHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Environment;

/**
 *
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @internal
 */
final class RenderSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $manager;
    private Environment $templating;
    private PropertyAccessorInterface $propertyAccessor;
    private EventDispatcherInterface $dispatcher;

    public function __construct(EntityManagerInterface $manager,
                                Environment $templating,
                                PropertyAccessorInterface $propertyAccessor,
                                EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->templating = $templating;
        $this->propertyAccessor = $propertyAccessor;
        $this->dispatcher = $dispatcher;
    }

    public function onRenderQuery(RenderQueryEvent $event): void
    {
        $datatable = $event->getDatatable();
        $query = $event->getQuery();

        $queryKeys = ['columns','orders','search','start','length'];

        foreach($queryKeys as $key){
            if (!\array_key_exists($key, $query)) {
                throw new \Exception(sprintf("Your query don't have %s key, If you have a %s please don't unset query keys.",$key,"RenderQueryEvent"));
            }
        }
        
        $helper = new DatatableQueriesHelper(
            $this->manager,
            $this->dispatcher,
            $datatable
        );

        $event->setRecordsTotal($helper->countRecords());
        $event->setRecordsFiltered($helper->countRecords($query));
        $event->setRecords($helper->findRecords($query));
    }

    public function onRenderData(RenderDataEvent $event): void
    {
        $datatable = $event->getDatatable();
        $records = $event->getRecords();

        $helper = new DatatableTemplatingHelper(
            $this->templating,
            $this->propertyAccessor,
            $datatable,
            $records
        );

        $event->setRecords($helper->renderData());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::PRE_QUERY => 'onRenderQuery',
            Events::PRE_DATA => 'onRenderData',
        ];
    }
}
