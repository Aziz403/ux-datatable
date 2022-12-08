<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
class DataProcess
{
    private Environment $templating;
    private UrlGeneratorInterface $router;

    public function __construct(Environment $templating,UrlGeneratorInterface $router)
    {
        $this->templating = $templating;
        $this->router = $router;
    }

    /**
     * @param array $data
     * @param string $entityName
     * @param string|null $template
     * @return array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function addActionField(array $data, string $class, string $template = null)
    {
        $entityName = $this->getEntityNameFromClassName($class);
        $entityName = $this->getEntityNameFromUpper($entityName);
        if(!$template)
        { $template = $entityName.'/_actions.html.twig'; }

        $process = [];
        foreach ($data as $item) {
            $item['action'] = $this->templating->render($template,[
                $entityName=>[
                    'id'=>$item['id']
                ]
            ]);
            $process[] = $item;
        }
        return $process;
    }

    public function getEntityNameFromLower(string $name):string
    {// convert entities like :( movement_stock to MovementStock )
        $entityName = ucwords($name,'_');
        $entityName = str_replace('_','',$entityName);
        return $entityName;
    }

    public function getEntityNameFromClassName(string $name):string
    {// convert entities like :( App\\MovementStock to MovementStock )
        $indexOfLastSlash = strrpos($name,'\\');
        $entityName = substr($name,$indexOfLastSlash+1);
        return $entityName;
    }

    public function getEntityNameFromUpper(string $name):string
    {// convert entities like :( MovementStock to movement_stock )
        $entityName = "";
        $name = lcfirst($name);
        foreach(str_split($name) as $char){
            if($char==ucfirst($char)){// ila kan char upper case anezid 3elih underscore (like S to _s)
                $entityName .= '_'.lcfirst($char);
            }
            else{
                $entityName .= $char;
            }
        }
        return $entityName;
    }

    public function getColumns(string $class)
    {//return dataTable header columns configuration
        $className = $this->getEntityNameFromLower($class);
        return call_user_func_array(array('App\\Entity\\'.$className, 'getDataTableColumns'), array($this->router));
    }
}