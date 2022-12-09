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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
interface ResponseBuilderInterface
{
    const DEFAULT_TEMPLATE_PATH = "{entity}/_actions.html.twig";

    public function datatable(Request $request, string $class,string $actionsTemplate = self::DEFAULT_TEMPLATE_PATH): Response;

    public function choices(string $class, string $displayName): Response;
}