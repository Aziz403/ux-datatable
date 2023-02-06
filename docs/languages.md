## Languages Configuration

when you want to configure the global language, you will need two keys: 

```yaml
datatable:
  # Load i18n data from DataTables CDN or locally
  language_from_cdn: true
  # Language of the Datatables (if language_from_cdn true)
  language: 'en'
```

### Specify if Local or from CDN
``language_from_cdn``: can you specify if you want use the language from your translation files (using symfony/translation), or load language from Datatables.net CDN.

``language``: set the language you want in your datatable, the available options is :
  * en = English
  * fr = French
  * de = German
  * es = Spanish
  * it = Italian
  * pt = Portuguese
  * ru = Russian
  * zh = Chinese
  * ja = Japanese
  * ar = Arabic
  * hi = Hindi
  * bn = Bengali
  * sw = Swahili
  * mr = Marathi
  * ta = Tamil
  * tr = Turkish
  * pl = Polish
  * uk = Ukrainian
  * fa = Persian
  * ur = Urdu
  * he = Hebrew
  * th = Thai
  * request = The language base of the Request