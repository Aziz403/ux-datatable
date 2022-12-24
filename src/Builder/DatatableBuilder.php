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

use Aziz403\UX\Datatable\Model\EntityDatatable;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableBuilder implements DatatableBuilderInterface
{
    private EntityManagerInterface $manager;
    private Environment $templating;

    public function __construct(EntityManagerInterface $manager,Environment $templating)
    {
        $this->manager = $manager;
        $this->templating = $templating;
    }

    public function createDatatableFromEntity(string $className): EntityDatatable
    {
        $repository = $this->manager->getRepository($className);
        if(!$repository){
            throw new \Exception("Not Found Repository For Class Name : ".$className);
        }
        return new EntityDatatable($className,$repository,$this->templating);
    }
}