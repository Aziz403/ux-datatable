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
    const SIMPLE_DATATABLE_CONFIG = "simple-datatable";
    const COMPLEX_DATATABLE_CONFIG = "complex-datatable";

    const LOCALE_FROM_SESSION = "LOCALE_FROM_SESSION";
    const LOCALE_EN = "EN";
    const LOCALE_FR = "FR";
    const LOCALE_AR = "AR";

    protected string $config;
    protected array $options = [];
    protected array $attributes = [];

    public function __construct(string $config)
    {
        $this->attributes['id'] = 'table-'.random_int(10,99);
        $this->config = $config;
    }

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

    /**
     * @return string
     */
    public function getConfig(): string
    {
        return $this->config;
    }

}
