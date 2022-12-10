<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Twig;

use Aziz403\UX\Datatable\Model\AbstractDatatable;
use Aziz403\UX\Datatable\Model\ComplexDatatable;
use Symfony\WebpackEncoreBundle\Dto\StimulusControllersDto;
use Symfony\WebpackEncoreBundle\Twig\StimulusTwigExtension;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
class DatatableExtension extends AbstractExtension
{
    private StimulusTwigExtension $stimulus;

    public function __construct(StimulusTwigExtension $stimulus)
    {
        $this->stimulus = $stimulus;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_datatable', [$this, 'renderDatatable'], ['needs_environment' => true, 'is_safe' => ['html']]),
            new TwigFunction('datatable_controller', [$this, 'renderController'], ['needs_environment' => true, 'is_safe' => ['html']])
        ];
    }

    public function renderDatatable(Environment $env, AbstractDatatable $datatable, array $attributes = []): string
    {
        $datatable->setAttributes(array_merge($datatable->getAttributes(), $attributes));

        $controllers = [];
        $html = "<table ";
        $html .= $this->renderController($env,$datatable,$controllers);

        foreach ($datatable->getAttributes() as $name => $value) {
            if ('data-controller' === $name) {
                continue;
            }

            if (true === $value) {
                $html .= $name.'="'.$name.'" ';
            } elseif (false !== $value) {
                $html .= $name.'="'.$value.'" ';
            }
        }

        $html .= ">";

        if($datatable instanceof ComplexDatatable){
            $html .= '<thead>';
            $trSerach = '<tr style="display: none;">';
            $trHead = '<tr>';
            foreach ($datatable->getColumns() as $column){
                $trSerach .= '<th class="filter">'.$column['data'].'</th>';
                $trHead .= '<th data-tr-name="'.$column['data'].'">';
                if($column['data']=='action'){
                    $showIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"/></svg>';
                    $trHead .= '<button '.$this->stimulus->renderStimulusAction($env, $controllers,'toggleSearchColumnsVisibility').' data-toggle="hidden" >'.$showIcon.'</button>';
                }
                else{
                    $trHead .= $column['data'];
                }
                $trHead .= '</th>';
            }
            $trSerach .= '</tr>';
            $trHead .= '</tr>';
            $html .= $trSerach;
            $html .= $trHead;
            $html .= '</thead>';
        }

        return trim($html).'</table>';
    }

    public function renderController(Environment $env, AbstractDatatable $datatable,array &$controllers = [])
    {
        if ($datatable->getDataController()) {
            $controllers[$datatable->getDataController()] = [];
        }
        $controllers['@aziz403/ux-datatablejs/'.$datatable->getConfig()] = ['view' => $datatable->createView()];

        if (class_exists(StimulusControllersDto::class)) {
            $dto = new StimulusControllersDto($env);
            foreach ($controllers as $controllerName => $controllerValues) {
                $dto->addController($controllerName, $controllerValues);
            }

            return $dto.'';
        } else {
            return $this->stimulus->renderStimulusController($env, $controllers).'';
        }
    }
}
