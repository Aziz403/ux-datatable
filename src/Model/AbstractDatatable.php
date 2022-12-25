<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Model;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
abstract class AbstractDatatable
{
    protected array $options = [];
    protected array $attributes = [];

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDataController(): ?string
    {
        return $this->attributes['data-controller'] ?? null;
    }

    /**
     * @param string $controllerName
     * @return void
     */
    public function setDataController(string $controllerName)
    {
        $this->attributes['data-controller'] = $controllerName;
    }

    public abstract function createView(): array;

}
