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
use Aziz403\UX\Datatable\Helper\Constants;
use Aziz403\UX\Datatable\Helper\DatatableTemplatingHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
abstract class AbstractDatatable
{
    protected array $options = [];
    protected array $attributes = [];

    protected array $columns;

    protected ?string $globalController;

    protected string $language;
    protected string $locale;
    protected bool $isLangFromCDN;

    protected TranslatorInterface $translator;
    protected EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        TranslatorInterface $translator,
        array $config,
        string $locale
    )
    {
        $this->columns = [];
        $this->attributes['id'] = "datatable";
        
        $this->language = $config['language'];
        $this->isLangFromCDN = $config['language_from_cdn'];
        $this->globalController = $config['global_controller'] ?? null;
        $this->locale = $locale;

        if(isset($config['template_parameters'])){
            if(isset($config['template_parameters']['style'])){
                $this->attributes['data-styling-choicer'] = $config['template_parameters']['style'];
            }
            if(isset($config['template_parameters']['className'])){
                $this->attributes['class'] = ' '.$config['template_parameters']['className'];
            }
        }

        $this->translator = $translator;
        $this->dispatcher = $dispatcher;
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
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function addOptions(array $options): self
    {
        $this->options = array_merge($this->options,$options);

        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function addOption(string $key, $value): self
    {
        $this->options[$key] = $value;

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
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes,$attributes);

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addAttribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;

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
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param AbstractColumn[] $columns
     */
    public function addColumns(array $columns): self
    {
        $this->columns = array_merge($this->columns,$columns);

        return $this;
    }

    /**
     * @param AbstractColumn $column
     */
    public function addColumn(AbstractColumn $column): self
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @return array
     */
    public function getLanguageData(): array
    {
        $this->getLanguage();

        if ($this->isLangFromCDN) {
            return ['url' => "//cdn.datatables.net/plug-ins/1.10.15/i18n/{$this->getFullLanguage()}.json"];
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
        if($this->language==null || $this->language=='request'){
            $this->language = $this->locale;
        }

        return $this->language;
    }

    /**
     * @return string
     */
    public function getFullLanguage(): string
    {
        if(!isset(LANGUAGES[$this->language])){
            throw new \Exception(sprintf("'%s' Not Accepted, The Language needs to be a shortcut and one of: %s, or 'request'",$this->language,implode(",",array_keys(LANGUAGES))));
        }

        return LANGUAGES[$this->language];
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
     * @param string|null $globalController
     */
    public function setGlobalController(?string $globalController): AbstractDatatable
    {
        $this->globalController = $globalController;
        return $this;
    }

    /**
     * @return array
     */
    public function getColumnDefs(): array
    {
        $columnDefs = [];
        $i = 0;
        /** @var AbstractColumn $column */
        foreach ($this->columns as $column){
            $columnDefs[] = [
                'targets' => $i,
                'visible' => $column->isVisible(),
                'orderable' => $column->isOrderable()
            ];
            $i++;
        }

        return $columnDefs;
    }

    public abstract function createView(): array;

}

const LANGUAGES = array(
    'en' => 'English',
    'fr' => 'French',
    'de' => 'German',
    'es' => 'Spanish',
    'it' => 'Italian',
    'pt' => 'Portuguese',
    'ru' => 'Russian',
    'zh' => 'Chinese',
    'ja' => 'Japanese',
    'ar' => 'Arabic',
    'hi' => 'Hindi',
    'bn' => 'Bengali',
    'sw' => 'Swahili',
    'mr' => 'Marathi',
    'ta' => 'Tamil',
    'tr' => 'Turkish',
    'pl' => 'Polish',
    'uk' => 'Ukrainian',
    'fa' => 'Persian',
    'ur' => 'Urdu',
    'he' => 'Hebrew',
    'th' => 'Thai'
);