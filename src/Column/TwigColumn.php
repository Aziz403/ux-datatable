<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Column;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class TwigColumn extends AbstractColumn
{
    private string $template;

    public function __construct(string $field,string $template,string $display = null, $visible = true,bool $orderable = true)
    {
        $this->data = $field;
        $this->template = $template;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    public function build()
    {
        return [
            'data' => $this->data,
            'template' => $this->template,
            'text' => $this->text,
            'visible' => $this->visible,
            'orderable' => $this->orderable
        ];
    }
}