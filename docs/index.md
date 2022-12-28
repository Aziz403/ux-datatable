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
                 * Datatables.net documentation to discover them all
                 */
            ])

            return $this->render('home/index.html.twig', [
                'datatable' => $datatable->createView(),
            ]);
        }
    }


Once created in PHP, a datatable can be displayed using Twig if installed
(requires `Symfony Webpack Encore`):

    {# home/index.html.twig #}
    <table {{ stimulus_controller('@aziz403/ux-datatablejs',datatable) }}>
        {# table body: the content of table body based of what you want do, check Datatables.net for more details #}
    </table>

Also can you use your custom stimulus controller to create datatable :

    // mydatatable_controller.js

    import { Controller } from '@hotwired/stimulus';
    
    export default class extends Controller {
        connect() {
            this.element.addEventListener('datatable:before-connect', this._onPreConnect);
            this.element.addEventListener('datatable:connect', this._onConnect);
        }
    
        disconnect() {
            // You should always remove listeners when the controller is disconnected to avoid side effects
            this.element.removeEventListener('datatable:before-connect', this._onPreConnect);
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

and use the controller in twig template by :

    {# home/index.html.twig #}
    <table {{ stimulus_controller('@aziz403/ux-datatablejs mydatatable',datatable) }}>
        {# table body: the content of table body based of what you want do, check Datatables.net for more details #}
    </table>

### Entity datatable usage:
Entity datatable is awesome tool, it helps you by create ready queries to render entity data as datatable.  

To use Symfony UX Datatable Bundle with EntityDatatable and ready backend api by :

    // ...
    use Symfony\UX\Datatable\Model\EntityDatatable;
    use Symfony\Component\HttpFoundation\Request;
    use Aziz403\UX\Datatable\Builder\DatatableBuilderInterface;
    use Aziz403\UX\Datatable\Column\BadgeColumn;
    use Aziz403\UX\Datatable\Column\BooleanColumn;
    use Aziz403\UX\Datatable\Column\DateColumn;
    use Aziz403\UX\Datatable\Column\TextColumn;
    use Aziz403\UX\Datatable\Column\TwigColumn;

    class HomeController extends AbstractController
    {
        #[Route('/', name: 'app_homepage')]
        public function index(Request $request,DatatableBuilderInterface $builder): Response
        {
            //create EntityDatatable instance with the builder
            $datatable = $builder->createDatatableFromEntity(Post::class);

            //add the columns(proprities) of your ``Post`` entity
            /**
             *  you can change the column type class base on your proprity type
             *  and how to want its will
             */
            $datatable
                ->addColumn(new TextColumn('id'))       
                ->addColumn(new BadgeColumn('category'))
                ->addColumn(new TextColumn('slug'))
                ->addColumn(new DateColumn('publishAt'))
                ->addColumn(new BooleanColumn('isActive'))
                ->addColumn(new TwigColumn("actions","post/_actions.html.twig",displayName: ""))
                
            /**
             * by handling request you can send response when $datatable->isSubmitted()
             * the response its will be the datatable result
             */
            $datatable->handleRequest($request);

            if($datatable->isSubmitted()) {
                return $datatable->getResponse();
            }
    
            return $this->render('home/index.html.twig', [
                'datatable' => $datatable   //send datatable without createView() this time 
            ]);
        }
    }

And now can you easy use ``{{ render_datatable(datatable) }}`` to render datatable for ``Post`` entity data as datatable.


What's the Next
------------
- More About ``EntityDatatable``
- Know the Columns Types with available options
- Global Datatables configuration
- ``Datatables.net`` Themes
- Languages Support: CDN & Locally(with ``Symfony Translation``)
