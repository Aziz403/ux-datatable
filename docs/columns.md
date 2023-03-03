## Columns:
When you're trying to render ``EntityDatatable`` the most important thing is specified the columns, to represent the entity fields and data as datatable columns.

In this is why we have many ``Columns`` types with different jobs:

### TextColumn 
The simple column type, helps you to render the text of your entity.
### BadgeColumn 
Another simple column type, but its writes your entity field inside a html badge.
### BooleanColumn 
In the main it's for the boolean fields, to display for example 'Yes' & 'No' keywords place of 'true' & 'false',
but can you use it with another field types with the by added ``render`` in your BooleanColumn.
### DateColumn
To render Date & DateTime objects as fields in your entity.
### EntityColumn
by EntityColumn can you display a related entity field inside your datatable.
### TwigColumn
To render templates as a datatable column.
### InlineTwigColumn
To render string templates.

