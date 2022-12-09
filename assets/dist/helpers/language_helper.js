export default class LanguageHelper{
    static FR = {
        "emptyTable": "Aucune donnée disponible dans le tableau",
        "loadingRecords": "Chargement...",
        "processing": "Traitement...",
        "select": {
            "rows": {
                "_": "%d lignes sélectionnées",
                "1": "1 ligne sélectionnée"
            },
            "cells": {
                "1": "1 cellule sélectionnée",
                "_": "%d cellules sélectionnées"
            },
            "columns": {
                "1": "1 colonne sélectionnée",
                "_": "%d colonnes sélectionnées"
            }
        },
        "autoFill": {
            "cancel": "Annuler",
            "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
            "fillHorizontal": "Remplir les cellules horizontalement",
            "fillVertical": "Remplir les cellules verticalement"
        },
        "searchBuilder": {
            "conditions": {
                "date": {
                    "after": "Après le",
                    "before": "Avant le",
                    "between": "Entre",
                    "empty": "Vide",
                    "not": "Différent de",
                    "notBetween": "Pas entre",
                    "notEmpty": "Non vide",
                    "equals": "Égal à"
                },
                "number": {
                    "between": "Entre",
                    "empty": "Vide",
                    "gt": "Supérieur à",
                    "gte": "Supérieur ou égal à",
                    "lt": "Inférieur à",
                    "lte": "Inférieur ou égal à",
                    "not": "Différent de",
                    "notBetween": "Pas entre",
                    "notEmpty": "Non vide",
                    "equals": "Égal à"
                },
                "string": {
                    "contains": "Contient",
                    "empty": "Vide",
                    "endsWith": "Se termine par",
                    "not": "Différent de",
                    "notEmpty": "Non vide",
                    "startsWith": "Commence par",
                    "equals": "Égal à",
                    "notContains": "Ne contient pas",
                    "notEndsWith": "Ne termine pas par",
                    "notStartsWith": "Ne commence pas par"
                },
                "array": {
                    "empty": "Vide",
                    "contains": "Contient",
                    "not": "Différent de",
                    "notEmpty": "Non vide",
                    "without": "Sans",
                    "equals": "Égal à"
                }
            },
            "add": "Ajouter une condition",
            "button": {
                "0": "Recherche avancée",
                "_": "Recherche avancée (%d)"
            },
            "clearAll": "Effacer tout",
            "condition": "Condition",
            "data": "Donnée",
            "deleteTitle": "Supprimer la règle de filtrage",
            "logicAnd": "Et",
            "logicOr": "Ou",
            "title": {
                "0": "Recherche avancée",
                "_": "Recherche avancée (%d)"
            },
            "value": "Valeur",
            "leftTitle": "Désindenter le critère",
            "rightTitle": "Indenter le critère"
        },
        "searchPanes": {
            "clearMessage": "Effacer tout",
            "count": "{total}",
            "title": "Filtres actifs - %d",
            "collapse": {
                "0": "Volet de recherche",
                "_": "Volet de recherche (%d)"
            },
            "countFiltered": "{shown} ({total})",
            "emptyPanes": "Pas de volet de recherche",
            "loadMessage": "Chargement du volet de recherche...",
            "collapseMessage": "Réduire tout",
            "showMessage": "Montrer tout"
        },
        "buttons": {
            "collection": "Collection",
            "colvis": "Visibilité colonnes",
            "colvisRestore": "Rétablir visibilité",
            "copy": "Copier",
            "Export": "Exporter",
            "copySuccess": {
                "1": "1 ligne copiée dans le presse-papier",
                "_": "%d lignes copiées dans le presse-papier"
            },
            "copyTitle": "Copier dans le presse-papier",
            "csv": "CSV",
            "excel": "Excel",
            "pageLength": {
                "-1": "Afficher toutes les lignes",
                "_": "Afficher %d lignes",
                "1": "Afficher 1 ligne"
            },
            "pdf": "PDF",
            "print": "Imprimer",
            "copyKeys": "Appuyez sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
            "createState": "Créer un état",
            "removeAllStates": "Supprimer tous les états",
            "removeState": "Supprimer",
            "renameState": "Renommer",
            "savedStates": "États sauvegardés",
            "stateRestore": "État %d",
            "updateState": "Mettre à jour"
        },
        "decimal": ",",
        "datetime": {
            "previous": "Précédent",
            "next": "Suivant",
            "hours": "Heures",
            "minutes": "Minutes",
            "seconds": "Secondes",
            "unknown": "-",
            "amPm": [
                "am",
                "pm"
            ],
            "months": {
                "0": "Janvier",
                "2": "Mars",
                "3": "Avril",
                "4": "Mai",
                "5": "Juin",
                "6": "Juillet",
                "8": "Septembre",
                "9": "Octobre",
                "10": "Novembre",
                "1": "Février",
                "11": "Décembre",
                "7": "Août"
            },
            "weekdays": [
                "Dim",
                "Lun",
                "Mar",
                "Mer",
                "Jeu",
                "Ven",
                "Sam"
            ]
        },
        "editor": {
            "close": "Fermer",
            "create": {
                "title": "Créer une nouvelle entrée",
                "button": "Nouveau",
                "submit": "Créer"
            },
            "edit": {
                "button": "Editer",
                "title": "Editer Entrée",
                "submit": "Mettre à jour"
            },
            "remove": {
                "button": "Supprimer",
                "title": "Supprimer",
                "submit": "Supprimer",
                "confirm": {
                    "_": "Êtes-vous sûr de vouloir supprimer %d lignes ?",
                    "1": "Êtes-vous sûr de vouloir supprimer 1 ligne ?"
                }
            },
            "multi": {
                "title": "Valeurs multiples",
                "info": "Les éléments sélectionnés contiennent différentes valeurs pour cette entrée. Pour modifier et définir tous les éléments de cette entrée à la même valeur, cliquez ou tapez ici, sinon ils conserveront leurs valeurs individuelles.",
                "restore": "Annuler les modifications",
                "noMulti": "Ce champ peut être modifié individuellement, mais ne fait pas partie d'un groupe. "
            },
            "error": {
                "system": "Une erreur système s'est produite (<a target=\"\\\" rel=\"nofollow\" href=\"\\\">Plus d'information<\/a>)."
            }
        },
        "stateRestore": {
            "removeSubmit": "Supprimer",
            "creationModal": {
                "button": "Créer",
                "order": "Tri",
                "paging": "Pagination",
                "scroller": "Position du défilement",
                "search": "Recherche",
                "select": "Sélection",
                "columns": {
                    "search": "Recherche par colonne",
                    "visible": "Visibilité des colonnes"
                },
                "name": "Nom :",
                "searchBuilder": "Recherche avancée",
                "title": "Créer un nouvel état",
                "toggleLabel": "Inclus :"
            },
            "renameButton": "Renommer",
            "duplicateError": "Il existe déjà un état avec ce nom.",
            "emptyError": "Le nom ne peut pas être vide.",
            "emptyStates": "Aucun état sauvegardé",
            "removeConfirm": "Voulez vous vraiment supprimer %s ?",
            "removeError": "Échec de la suppression de l'état.",
            "removeJoiner": "et",
            "removeTitle": "Supprimer l'état",
            "renameLabel": "Nouveau nom pour %s :",
            "renameTitle": "Renommer l'état"
        },
        "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
        "infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
        "infoFiltered": "(filtrées depuis un total de _MAX_ entrées)",
        "lengthMenu": "Afficher _MENU_ entrées",
        "paginate": {
            "first": "Première",
            "last": "Dernière",
            "next": "Suivante",
            "previous": "Précédente"
        },
        "zeroRecords": "Aucune entrée correspondante trouvée",
        "aria": {
            "sortAscending": " : activer pour trier la colonne par ordre croissant",
            "sortDescending": " : activer pour trier la colonne par ordre décroissant"
        },
        "infoThousands": " ",
        "search": "Rechercher :",
        "thousands": " "
    };
    static AR = {
        "loadingRecords": "جارٍ التحميل...",
        "lengthMenu": "أظهر _MENU_ مدخلات",
        "zeroRecords": "لم يعثر على أية سجلات",
        "info": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
        "search": "ابحث:",
        "paginate": {
            "first": "الأول",
            "previous": "السابق",
            "next": "التالي",
            "last": "الأخير"
        },
        "aria": {
            "sortAscending": ": تفعيل لترتيب العمود تصاعدياً",
            "sortDescending": ": تفعيل لترتيب العمود تنازلياً"
        },
        "select": {
            "rows": {
                "_": "%d قيمة محددة",
                "1": "1 قيمة محددة"
            },
            "cells": {
                "1": "1 خلية محددة",
                "_": "%d خلايا محددة"
            },
            "columns": {
                "1": "1 عمود محدد",
                "_": "%d أعمدة محددة"
            }
        },
        "buttons": {
            "print": "طباعة",
            "copyKeys": "زر <i>ctrl<\/i> أو <i>⌘<\/i> + <i>C<\/i> من الجدول<br>ليتم نسخها إلى الحافظة<br><br>للإلغاء اضغط على الرسالة أو اضغط على زر الخروج.",
            "pageLength": {
                "-1": "اظهار الكل",
                "_": "إظهار %d أسطر",
                "1": "اظهار سطر واحد"
            },
            "collection": "مجموعة",
            "copy": "نسخ",
            "copyTitle": "نسخ إلى الحافظة",
            "csv": "CSV",
            "excel": "Excel",
            "pdf": "PDF",
            "colvis": "إظهار الأعمدة",
            "Export": "تصدير",
            "colvisRestore": "إستعادة العرض",
            "copySuccess": {
                "1": "تم نسخ سطر واحد الى الحافظة",
                "_": "تم نسخ %ds أسطر الى الحافظة"
            },
            "createState": "تكوين حالة",
            "removeAllStates": "ازالة جميع الحالات",
            "removeState": "ازالة حالة",
            "renameState": "تغيير اسم حالة",
            "savedStates": "الحالات المحفوظة",
            "stateRestore": "استرجاع حالة",
            "updateState": "تحديث حالة"
        },
        "searchBuilder": {
            "add": "اضافة شرط",
            "clearAll": "ازالة الكل",
            "condition": "الشرط",
            "data": "المعلومة",
            "logicAnd": "و",
            "logicOr": "أو",
            "value": "القيمة",
            "conditions": {
                "date": {
                    "after": "بعد",
                    "before": "قبل",
                    "between": "بين",
                    "empty": "فارغ",
                    "equals": "تساوي",
                    "notBetween": "ليست بين",
                    "notEmpty": "ليست فارغة",
                    "not": "ليست "
                },
                "number": {
                    "between": "بين",
                    "empty": "فارغة",
                    "equals": "تساوي",
                    "gt": "أكبر من",
                    "lt": "أقل من",
                    "not": "ليست",
                    "notBetween": "ليست بين",
                    "notEmpty": "ليست فارغة",
                    "gte": "أكبر أو تساوي",
                    "lte": "أقل أو تساوي"
                },
                "string": {
                    "not": "ليست",
                    "notEmpty": "ليست فارغة",
                    "startsWith": " تبدأ بـ ",
                    "contains": "تحتوي",
                    "empty": "فارغة",
                    "endsWith": "تنتهي ب",
                    "equals": "تساوي",
                    "notContains": "لا تحتوي",
                    "notStartsWith": "لا تبدأ بـ",
                    "notEndsWith": "لا تنتهي بـ"
                },
                "array": {
                    "equals": "تساوي",
                    "empty": "فارغة",
                    "contains": "تحتوي",
                    "not": "ليست",
                    "notEmpty": "ليست فارغة",
                    "without": "بدون"
                }
            },
            "button": {
                "0": "فلاتر البحث",
                "_": "فلاتر البحث (%d)"
            },
            "deleteTitle": "حذف فلاتر",
            "leftTitle": "محاذاة يسار",
            "rightTitle": "محاذاة يمين",
            "title": {
                "0": "البحث المتقدم",
                "_": "البحث المتقدم (فعال)"
            }
        },
        "searchPanes": {
            "clearMessage": "ازالة الكل",
            "collapse": {
                "0": "بحث",
                "_": "بحث (%d)"
            },
            "count": "عدد",
            "countFiltered": "عدد المفلتر",
            "loadMessage": "جارِ التحميل ...",
            "title": "الفلاتر النشطة",
            "showMessage": "إظهار الجميع",
            "collapseMessage": "إخفاء الجميع",
            "emptyPanes": "لا يوجد مربع بحث"
        },
        "infoThousands": ",",
        "datetime": {
            "previous": "السابق",
            "next": "التالي",
            "hours": "الساعة",
            "minutes": "الدقيقة",
            "seconds": "الثانية",
            "unknown": "-",
            "amPm": [
                "صباحا",
                "مساءا"
            ],
            "weekdays": [
                "الأحد",
                "الإثنين",
                "الثلاثاء",
                "الأربعاء",
                "الخميس",
                "الجمعة",
                "السبت"
            ],
            "months": [
                "يناير",
                "فبراير",
                "مارس",
                "أبريل",
                "مايو",
                "يونيو",
                "يوليو",
                "أغسطس",
                "سبتمبر",
                "أكتوبر",
                "نوفمبر",
                "ديسمبر"
            ]
        },
        "editor": {
            "close": "إغلاق",
            "create": {
                "button": "إضافة",
                "title": "إضافة جديدة",
                "submit": "إرسال"
            },
            "edit": {
                "button": "تعديل",
                "title": "تعديل السجل",
                "submit": "تحديث"
            },
            "remove": {
                "button": "حذف",
                "title": "حذف",
                "submit": "حذف",
                "confirm": {
                    "_": "هل أنت متأكد من رغبتك في حذف السجلات %d المحددة؟",
                    "1": "هل أنت متأكد من رغبتك في حذف السجل؟"
                }
            },
            "error": {
                "system": "حدث خطأ ما"
            },
            "multi": {
                "title": "قيم متعدية",
                "restore": "تراجع",
                "info": "القيم المختارة تحتوى على عدة قيم لهذا المدخل. لتعديل وتحديد جميع القيم لهذا المدخل، اضغط او انتقل هنا، عدا ذلك سيبقى نفس القيم",
                "noMulti": "هذا المدخل مفرد وليس ضمن مجموعة"
            }
        },
        "processing": "جارٍ المعالجة...",
        "emptyTable": "لا يوجد بيانات متاحة في الجدول",
        "infoEmpty": "يعرض 0 إلى 0 من أصل 0 مُدخل",
        "thousands": ".",
        "stateRestore": {
            "creationModal": {
                "columns": {
                    "search": "إمكانية البحث للعمود",
                    "visible": "إظهار العمود"
                },
                "toggleLabel": "تتضمن",
                "button": "تكوين الحالة",
                "name": "اسم الحالة",
                "order": "فرز",
                "paging": "تصحيف",
                "scroller": "مكان السحب",
                "search": "بحث",
                "searchBuilder": "مكون البحث",
                "select": "تحديد",
                "title": "تكوين حالة جديدة"
            },
            "duplicateError": "حالة مكررة بنفس الاسم",
            "emptyError": "لا يسمح بأن يكون اسم الحالة فارغة.",
            "emptyStates": "لا توجد حالة محفوظة",
            "removeConfirm": "هل أنت متأكد من حذف الحالة %s؟",
            "removeError": "لم استطع ازالة الحالة.",
            "removeJoiner": "و",
            "removeSubmit": "حذف",
            "removeTitle": "حذف حالة",
            "renameButton": "تغيير اسم حالة",
            "renameLabel": "الاسم الجديد للحالة %s:",
            "renameTitle": "تغيير اسم الحالة"
        },
        "autoFill": {
            "cancel": "إلغاء الامر",
            "fill": "املأ كل الخلايا بـ <i>%d<\/i>",
            "fillHorizontal": "تعبئة الخلايا أفقيًا",
            "fillVertical": "تعبئة الخلايا عموديا",
            "info": "تعبئة تلقائية"
        },
        "decimal": ",",
        "infoFiltered": "(مرشحة من مجموع _MAX_ مُدخل)",
        "searchPlaceholder": "مثال بحث"
    };

    static getLanguage(lang){
        switch (lang)
        {
            case 'FR':
                return LanguageHelper.FR;
            case 'AR':
                return LanguageHelper.AR;
        }
    }
}