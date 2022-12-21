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
 *
 * @final
 */
abstract class AbstractDatatable
{
    const LOCALE_EN = "EN";
    const LOCALE_FR = "FR";
    const LOCALE_AR = "AR";

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
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public abstract function createView(): array;

    public function getDataController(): ?string
    {
        return $this->attributes['data-controller'] ?? null;
    }

    public function setDataController(string $controllerName)
    {
        $this->attributes['data-controller'] = $controllerName;
    }

}
