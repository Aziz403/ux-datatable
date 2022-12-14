Symfony UX Datatables.net
===================

Symfony UX Datatables.net is a Symfony bundle integrating the
`Datatables.net` library in Symfony applications.

Installation
------------

Before you start, make sure you have `Symfony UX configured in your app`.

Then, install this bundle using Composer and Symfony Flex:

    $ composer require aziz403/ux-datatablejs

    # Don't forget to install the JavaScript dependencies as well and compile
    $ npm install --force
    $ npm run watch

    # or use yarn
    $ yarn install --force
    $ yarn watch

Also make sure you have at least version 3.0 of `@symfony/stimulus-bridge`
in your ``package.json`` file.

Usage
-----

### Simple Usage

To use Symfony UX Datatable Bundle, For the simple usage create instance from ``Datatable`` model:

    // ...
    use Symfony\UX\Datatable\Model\Datatable;

    class HomeController extends AbstractController
    {
        #[Route('/', name: 'app_homepage')]
        public function index(): Response
        {
            $datatable = new Datatable();
            $datatable->setOptions([
                'serverSide' => true,
                'processing' => true,
                'pageLength' => 16,
                'ajax' => $this->generateUrl('app_data'),
                /**
                 * All options and data are provided as-is to Datatables.net. You can read
                 * `Datatables.net documentation`_ to discover them all
                 */
            ])

            return $this->render('home/index.html.twig', [
                'datatable' => $datatable,
            ]);
        }
    }


Once created in PHP, a datatable can be displayed using Twig if installed
(requires `Symfony Webpack Encore`):

    {# home/index.html.twig #}

    {{ render_datatable(datatable) }}

    {# also can you add other attributes to table tag : #}    
    {{ render_datatable(datatable,{'class':'ui celled table dataTable'}) }}

Also can you use your custom stimulus controller to create datatable :

    // mydatatable_controller.js

    import { Controller } from '@hotwired/stimulus';
    
    export default class extends Controller {
        connect() {
            this.element.addEventListener('datatable:pre-connect', this._onPreConnect);
            this.element.addEventListener('datatable:connect', this._onConnect);
        }
    
        disconnect() {
            // You should always remove listeners when the controller is disconnected to avoid side effects
            this.element.removeEventListener('datatable:pre-connect', this._onPreConnect);
            this.element.removeEventListener('datatable:connect', this._onConnect);
        }
    
        _onPreConnect(event) {
            // The datatable is not yet created
            console.log(event.detail.options); // You can access the table options using the event details
    
            // For instance you can change the options:
            event.detail.options = {
                processing: true,
                serverSide: true,
                ajax: '/server/processing',
            }
        }
    
        _onConnect(event) {
            // The datatable was just created
            console.log(event.detail.table,event.detail.options); // You can access the datatable instance using the event details
        }
    }

and use the controller in view by :

    {{ render_datatable(datatable,{'data-controller':'mydatatable'}) }}

### Complex datatable usage:

To use Symfony UX Datatable Bundle with complex view and ready backend api in three steps :

#### 1 - extends ``DataTable`` class in your entity and add ``getFields()`` method :

    // ...
    use Aziz403\UX\Datatable\Entity\DataTable;

    class Post extends DataTable
    {
        public static function getFields(): array
        {
            return [
                self::simpleColumn('id',defualtVisibility: false),
                self::simpleColumn('title')
            ];
        }
        
        // ...
    }

Here you have the options about how to display your fields :
<table></table>

#### 2 - extends ``DatatableRepository`` in your repository and change the construct to be like this :

    // ...
    
    class PostRepository extends DatatableRepository
    {
        public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
        {
            parent::__construct($registry,$manager,Post::class,'p');    // the last argument is the alias of the entity
        }
    
        // ...
    }

#### 3 - create instance from ``ComplexDatatable`` model:

    // ...
    use Symfony\UX\Datatable\Model\ComplexDatatable;

    class HomeController extends AbstractController
    {
        #[Route('/', name: 'app_homepage')]
        public function index(): Response
        {
            $datatable = new ComplexDatatable(
                'tableId',                              // datatable id to save the ``stateSave``
                $this->generateUrl('app_post_data'),    // the ajax option value will be route of the method data
                Todo::getFields()                       // datatable fields
            );
    
            // also can you customize the datatable options
            $datatable->setOptions([
               'order'=>[[1, 'asc']]
            ]);
            // not required, but if you add button to export datatable to excel
            $datatable->setPathExcel($this->generateUrl('app_post_excel'));

            return $this->render('home/index.html.twig', [
                'datatable' => $datatable,
            ]);
        }
        
        #[Route('/data', name: 'app_post_data')]
        public function data(Request $request,ResponseBuilderInterface $responseBuilder): Response
        {
            return $responseBuilder->datatable(
                $request,
                Post::class,
                null            // template actions : can you create twig template to add actions inside it (like show,edit,delete)
            );
        }
        
        #[Route('/excel', name: 'app_post_excel')]
        public function excel(Request $request,ResponseBuilderInterface $responseBuilder): Response
        {
            return $responseBuilder->generateExcel($request,Todo::class);
        }
    }

And now can you easy use ``{{ render_datatable(datatable) }}`` to render datatable for ``Post`` entity.

