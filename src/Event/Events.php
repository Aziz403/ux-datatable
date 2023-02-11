<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace Aziz403\UX\Datatable\Event;
 
 /**
  * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
  */
 final class Events
 {
    public const PRE_QUERY = 'datatable.pre_query';

    public const PRE_DATA = 'datatable.pre_data';
 }