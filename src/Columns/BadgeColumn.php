<?php

namespace Aziz403\UX\Datatable\Columns;

class BadgeColumn extends AbstractColumn
{
    const COLOR_DEFAULT = 'default';
    const COLOR_PRIMARY = 'primary';
    const COLOR_SECONDARY = 'secondary';
    const COLOR_SUCCESS = 'success';
    const COLOR_INFO = 'info';
    const COLOR_DANGER = 'danger';

    private string $condition;
    private string $color;

    public function __construct(string $field,callable $condition,string $color = self::COLOR_DEFAULT,string $display = '',bool $visible = true,bool $orderable = true)
    {
        $this->data = $field;
        $this->condition = "";//TODO: khas nedir hena callable
        $this->color = $color;
        $this->text = $display ?? $field;
        $this->visible = $visible;
        $this->orderable = $orderable;
    }

    public function build()
    {
        return [
            'data' => $this->data,
            'condition' => $this->condition,
            'color' => $this->color,
            'text' => $this->text,
            'visible' => $this->visible,
            'orderable' => $this->orderable
        ];
    }
}