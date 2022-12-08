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
        if ($datatable->getDataController()) {
            $controllers[$datatable->getDataController()] = [];
        }
        $controllers['@aziz403/ux-datatablejs/'.$datatable->getConfig()] = ['view' => $datatable->createView()];

        if (class_exists(StimulusControllersDto::class)) {
            $dto = new StimulusControllersDto($env);
            foreach ($controllers as $controllerName => $controllerValues) {
                $dto->addController($controllerName, $controllerValues);
            }

            $html = '<table '.$dto.' ';
        } else {
            $html = '<table '.$this->stimulus->renderStimulusController($env, $controllers).' ';
        }

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
                $trHead .= '<th data-tr-name="'.$column['data'].'">'.$column['data'];
                if($column['data']=='action'){
                    $trHead .= '<button '.$this->stimulus->renderStimulusAction($env, $controllers,'toggleSearchColumnsVisibility').'><i class="fas fa-eye"></i></button>';
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

    public function renderController(Environment $env, AbstractDatatable $datatable)
    {
        $controllers = [];
        if ($datatable->getDataController()) {
            $controllers[$datatable->getDataController()] = [];
        }
        $controllers['@symfony/ux-datatablejs/'.$datatable->getConfig()] = ['view' => $datatable->createView()];

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
