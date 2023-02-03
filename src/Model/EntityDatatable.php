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

use Aziz403\UX\Datatable\Column\AbstractColumn;
use Aziz403\UX\Datatable\Column\TwigColumn;
use Aziz403\UX\Datatable\Helper\DatatableQueriesHelper;
use Aziz403\UX\Datatable\Helper\DatatableTemplatingHelper;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class EntityDatatable extends AbstractDatatable
{
    const DEFAULT_DATATABLE_OPTIONS = [
        'processing' => true,
        'serverSide' => true
    ];

    private string $className = "";

    private ?string $path;
    private array $columns;

    private string $language;

    private ?string $globalController;
    private bool $isLangFromCDN;
    private TranslatorInterface $translator;

    private ?Criteria $criteria = null;

    private ?Request $request = null;

    private DatatableQueriesHelper $queriesService;
    private DatatableTemplatingHelper $templatingService;

    public function __construct(
        string $className,
        EntityRepository $repository,
        Environment $environment,
        TranslatorInterface $translator,
        array $config
    )
    {
        $this->className = $className;
        $this->columns = [];
        $this->attributes['id'] = "table".random_int(9,999);

        $this->options = array_merge(self::DEFAULT_DATATABLE_OPTIONS,$config['options']);
        $this->language = $config['language'];
        $this->isLangFromCDN = $config['language_from_cdn'];
        $this->globalController = $config['global_controller'] ?? null;

        if(isset($config['template_parameters'])){
            if(isset($config['template_parameters']['style'])){
                $this->attributes['data-styling-choicer'] = $config['template_parameters']['style'];
            }
            if(isset($config['template_parameters']['className'])){
                $this->className .= ' '.$config['template_parameters']['className'];
            }
        }

        $this->translator = $translator;
        $this->queriesService = new DatatableQueriesHelper($repository,$this);
        $this->templatingService = new DatatableTemplatingHelper($environment,$this);
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return EntityDatatable
     */
    public function setClassName(string $className): EntityDatatable
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return AbstractColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return AbstractColumn[]
     */
    public function getSearchableColumns(): array
    {
        return array_filter($this->columns,function (AbstractColumn $column) {
            return $column->isSearchable();
        });
    }

    /**
     * @param string $type
     * @return array
     */
    public function getColumnsByType(string $type): array
    {
        return array_filter($this->columns,function (AbstractColumn $column) use ($type) {
            return $column instanceof $type;
        });
    }

    /**
     * @param string $data
     * @return AbstractColumn|null
     */
    public function getColumn(string $data): ?AbstractColumn
    {
        $res = array_filter($this->columns,function (AbstractColumn $column) use($data){
            return $column->getData()===$data;
        });
        if(count($res)==0){
            return null;
        }
        return reset($res);
    }

    /**
     * @param int $index
     * @return AbstractColumn|null
     */
    public function getColumnByIndex(int $index): ?AbstractColumn
    {
        return $this->columns[$index];
    }

    /**
     * @param string $data
     * @return AbstractColumn|null
     */
    public function getColumnIndex(string $data): ?int
    {
        return array_search(
            $this->getColumn($data),
            $this->columns
        );
    }

    /**
     * @param AbstractColumn[] $columns
     * @return EntityDatatable
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param AbstractColumn[] $columns
     * @return EntityDatatable
     */
    public function addColumns(array $columns): self
    {
        $this->columns = array_merge($this->columns,$columns);

        return $this;
    }

    /**
     * @param AbstractColumn $column
     * @return EntityDatatable
     */
    public function addColumn(AbstractColumn $column): self
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @return Criteria|null
     */
    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     */
    public function setCriteria(Criteria $criteria): void
    {
        $this->criteria = $criteria;
    }

    /**
     * @return array
     */
    public function getLanguageData(): array
    {
        if ($this->isLangFromCDN) {
            return ['url' => "//cdn.datatables.net/plug-ins/1.10.15/i18n/$this->language.json"];
        }

        return [
            'processing' => $this->translator->trans('datatable.datatable.processing'),
            'search' => $this->translator->trans('datatable.datatable.search'),
            'lengthMenu' => $this->translator->trans('datatable.datatable.lengthMenu'),
            'info' => $this->translator->trans('datatable.datatable.info'),
            'infoEmpty' => $this->translator->trans('datatable.datatable.infoEmpty'),
            'infoFiltered' => $this->translator->trans('datatable.datatable.infoFiltered'),
            'infoPostFix' => $this->translator->trans('datatable.datatable.infoPostFix'),
            'loadingRecords' => $this->translator->trans('datatable.datatable.loadingRecords'),
            'zeroRecords' => $this->translator->trans('datatable.datatable.zeroRecords'),
            'emptyTable' => $this->translator->trans('datatable.datatable.emptyTable'),
            'searchPlaceholder' => $this->translator->trans('datatable.datatable.searchPlaceholder'),
            'paginate' => [
                'first' => $this->translator->trans('datatable.datatable.paginate.first'),
                'previous' => $this->translator->trans('datatable.datatable.paginate.previous'),
                'next' => $this->translator->trans('datatable.datatable.paginate.next'),
                'last' => $this->translator->trans('datatable.datatable.paginate.last'),
            ],
            'aria' => [
                'sortAscending' => $this->translator->trans('datatable.datatable.aria.sortAscending'),
                'sortDescending' => $this->translator->trans('datatable.datatable.aria.sortDescending'),
            ],
        ];
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLangFromCDN(): bool
    {
        return $this->isLangFromCDN;
    }

    /**
     * @param bool $isLangFromCDN
     */
    public function setLangFromCDN(bool $isLangFromCDN): self
    {
        $this->isLangFromCDN = $isLangFromCDN;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getThemeStyling()
    {
        return $this->attributes['data-styling-choicer'];
    }

    /**
     * @return string
     */
    public function getGlobalController(): ?string
    {
        return $this->globalController;
    }

    /**
     * @return array
     */
    public function getColumnDefs(): array
    {
        $columnDefs = [];
        /** @var AbstractColumn $column */
        foreach ($this->columns as $column){
            $columnDefs[$column->getData()] = [
                'visible' => $column->isSearchable(),
                'searchable' => $column->isVisible()
            ];
        }

        return $columnDefs;
    }

    /**
     * @return bool
     */
    public function isSubmitted():bool
    {
        if(!$this->request){
            return false;
        }
        return $this->request->get('draw',false);
    }

    /**
     * @return array
     */
    public function createView(): array
    {
        return [
            "path" => $this->path,
            "options" => array_merge(
                array_merge(
                    $this->options,
                    ["columnDefs" => $this->getColumnDefs()]
                ),
                ["language" => $this->getLanguageData()]
            ),
        ];
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function handleRequest(Request $request): self
    {
        $this->request = $request;
        $this->path = $request->getPathInfo();
        $this->options['ajax'] = $this->path;

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function getResponse(): JsonResponse
    {
        //check if request handled
        if(!$this->request){
            throw new \Exception(Request::class." Not Found,Maybe you forget send Request in EntityDatatable::handleRequest method");
        }

        $query = [
            'columns' => $this->request->get('columns'),
            'orders' => $this->request->get('order'),
            'search' => $this->request->get('search'),
            'start' => $this->request->get('start'),
            'length' => $this->request->get('length')
        ];
        //create query
        $records = $this->queriesService->findRecords($query);
        $recordsTotal = $this->queriesService->countRecords();
        $recordsFiltered = $this->queriesService->countRecords($query);

        //trait data by environment
        $data = $this->templatingService->renderData($records);

        //clear request
        $draw = $this->request->get('draw',1);
        $this->request = null;

        //save data to later usage
        return new JsonResponse([
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
            "draw" => $draw
        ]);
    }
}