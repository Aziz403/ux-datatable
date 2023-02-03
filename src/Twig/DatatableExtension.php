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

use Aziz403\UX\Datatable\Model\EntityDatatable;
use Symfony\WebpackEncoreBundle\Dto\StimulusControllersDto;
use Symfony\WebpackEncoreBundle\Twig\StimulusTwigExtension;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
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
        ];
    }

    public function renderDatatable(Environment $env,EntityDatatable $datatable, array $attributes = []): string
    {
        $datatable->setAttributes(array_merge($datatable->getAttributes(), $attributes));

        $controllers = [];
        $html = "<table class='{$datatable->getClassName()}' ";

        // add theme (before main controller)
        if(($theme = $datatable->getThemeStyling())!='none'){
            $controllers['@aziz403/ux-datatable/styling_'.$theme] = [];
        }

        // add custom controller if exists
        if ($datatable->getDataController()) {
            $controllers[$datatable->getDataController()] = [];
        }

        // add global controller if exists
        if($datatable->getGlobalController()){
            $controllers[$datatable->getGlobalController()] = [];
        }

        //add main controller
        $controllers['@aziz403/ux-datatable/entity_datatable'] = ['view' => $datatable->createView()];

        if (class_exists(StimulusControllersDto::class)) {
            $dto = new StimulusControllersDto($env);
            foreach ($controllers as $controllerName => $controllerValues) {
                $dto->addController($controllerName, $controllerValues);
            }
            $html .= $dto.'';
        }
        else {
            $html .= $this->stimulus->renderStimulusController($env, $controllers).'';
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

        $html .= '<thead><tr>';
        foreach ($datatable->getColumns() as $column){
            $html .= "<th data-column-name='{$column->getData()}'>{$column->getText()}</th>";
        }
        $html .= '</tr></thead>';

        return trim($html).'</table>';
    }
}
