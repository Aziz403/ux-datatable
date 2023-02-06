## Entity datatable usage:
Entity datatable is awesome tool, it helps you by create ready queries to render entity data as datatable.

To use UX Datatable Bundle with EntityDatatable and ready backend api by :

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

And now can you easy use ``{{ render_datatable(datatable) }}`` to render datatable for ``Post`` entity !

