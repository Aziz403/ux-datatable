Array Datatable:
=========

Array Datatable is awesome tool, it helps you by create datatable by passing columns & data as array.

To use UX Datatable Bundle with ``ArrayDatatable`` :

```
// ...
use Symfony\UX\Datatable\Model\ArrayDatatable;
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
        //create ArrayDatatable instance with the builder
        $datatable = $builder->createDatatableFromArray(
            [
                new TextColumn('id'),
                new BadgeColumn('category'),
                new TextColumn('slug'),
                new DateColumn('publishAt'),
                new BooleanColumn('isActive'),
                new TwigColumn("actions","post/_actions.html.twig",displayName: ""),
            ],
            [
                {
                    "name":       "Tiger Nixon",
                    "position":   "System Architect",
                    "salary":     "$3,120",
                    "start_date": "2011/04/25",
                    "office":     "Edinburgh",
                    "extn":       "5421"
                },
                {
                    "name":       "Garrett Winters",
                    "position":   "Director",
                    "salary":     "$5,300",
                    "start_date": "2011/07/25",
                    "office":     "Edinburgh",
                    "extn":       "8422"
                }
            ]
        );

        return $this->render('home/index.html.twig', [
            'datatable' => $datatable
        ]);
    }
}
```

And now can you easy use ``{{ render_datatable(datatable) }}`` to render datatable!

