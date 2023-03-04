Languages and Translation
=========

### Locally Datatable Translation
``language_from_cdn``: Can you specify if you want use the language from your translation files (using **symfony/translation**), by set it to ``false``.
And in this is situation should you translation missing datatable words:

| Words | 
| ---- |
| datatable.datatable.processing |
| datatable.datatable.search |
| datatable.datatable.lengthMenu |
| datatable.datatable.info |
| datatable.datatable.infoEmpty |
| datatable.datatable.infoFiltered |
| datatable.datatable.infoPostFix |
| datatable.datatable.loadingRecords |
| datatable.datatable.zeroRecords |
| datatable.datatable.emptyTable |
| datatable.datatable.searchPlaceholder |
| datatable.datatable.paginate.first |
| datatable.datatable.paginate.previous |
| datatable.datatable.paginate.next |
| datatable.datatable.paginate.last |
| datatable.datatable.aria.sortAscending |
| datatable.datatable.aria.sortDescending |

### Available Language Options
``language``: Set the language you want in your datatable, the available options is :

| Language | FullName |
| ---- | ---- |
| en | English |
| fr | French |
| de | German |
| es | Spanish |
| it | Italian |
| pt | Portuguese |
| ru | Russian |
| zh | Chinese |
| ja | Japanese |
| ar | Arabic |
| hi | Hindi |
| bn | Bengali |
| sw | Swahili |
| mr | Marathi |
| ta | Tamil |
| tr | Turkish |
| pl | Polish |
| uk | Ukrainian |
| fa | Persian |
| ur | Urdu |
| he | Hebrew |
| th | Thai |
| request | The language base of the Request |


### Usage
We have two why to config language in datatable :

#### 1 - Global Configuration
When you want to configure the global language: 

```yaml
datatable:
  # Load i18n data from DataTables CDN or locally
  language_from_cdn: true
  # Language of the Datatables (if language_from_cdn true)
  language: 'en'
```

#### 2 - Local Configuration
In this is situation the config will be applied just in the current ``$datatable`` instance:

```php
//....
$datatable->setLangFromCDN(true);
$datatable->setLanguage('en');
```