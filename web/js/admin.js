/**
 * Created by a.verilhac on 20/05/2015.
 */

$(function () {
    moment.locale('fr', {
        months: "janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre".split("_"),
        monthsShort: "janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.".split("_"),
        weekdays: "dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi".split("_"),
        weekdaysShort: "dim._lun._mar._mer._jeu._ven._sam.".split("_"),
        weekdaysMin: "Di_Lu_Ma_Me_Je_Ve_Sa".split("_"),
        longDateFormat: {
            LT: "HH:mm",
            L: "DD/MM/YYYY",
            LL: "D MMMM YYYY",
            LLL: "D MMMM YYYY LT",
            LLLL: "dddd D MMMM YYYY LT"
        },
        calendar: {
            sameDay: "[Aujourd'hui à] LT",
            nextDay: '[Demain à] LT',
            nextWeek: 'dddd [à] LT',
            lastDay: '[Hier à] LT',
            lastWeek: 'dddd [dernier à] LT',
            sameElse: 'L'
        },
        relativeTime: {
            future: "dans %s",
            past: "il y a %s",
            s: "quelques secondes",
            m: "une minute",
            mm: "%d minutes",
            h: "une heure",
            hh: "%d heures",
            d: "un jour",
            dd: "%d jours",
            M: "un mois",
            MM: "%d mois",
            y: "une année",
            yy: "%d années"
        },
        ordinal: function (number) {
            return number + (number === 1 ? 'er' : 'ème');
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 4  // The week that contains Jan 4th is the first week of the year.
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
    $.datepicker.regional['fr'] = {
        closeText: 'Fermer',
        prevText: '&#x3c;Préc',
        nextText: 'Suiv&#x3e;',
        currentText: 'Courant',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun',
            'Jul', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        dayNamesMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
        weekHeader: 'Sem',
        //dateFormat: 'dd/mm/yy',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['fr']);
    $(".date_picker").datepicker().attr("readonly", "readonly");
    $("#gallery").unitegallery();
    tinymce.init({
        selector: ".tinymce",
        theme: "modern",
        skin: 'light',
        height: 600,
        toolbar: [
            "undo redo | forecolor backcolor | bold italic | link image | alignleft aligncenter alignright | code fullscreen | bullist | numlist | fontsizeselect |underline | paste"
        ],
        menubar: true,
        statusbar: false,
        plugins: ["paste", "textcolor", "image", "code", "fullscreen"],
        paste_auto_cleanup_on_paste: true,
        paste_remove_styles: true,
        paste_remove_styles_if_webkit: true,
        paste_strip_class_attributes: "all",
        paste_preprocess: function (pl, o) {
            o.content = strip_tags(o.content, '');
        }
    });

    /*$('img').okzoom({
     width: 200,
     height: 200,
     round: true,
     background: "#fff",
     backgroundRepeat: "repeat",
     shadow: "0 0 5px #000",
     border: "1px solid black"
     });*/

    setSortable();

    $("#toggle_filter").click(function () {
        if ($("#form_filter").css('display') == 'none') {
            $("#form_filter").show();
            $("#toggle_filter").html('Masquer les filtres');
        } else {
            $("#form_filter").hide();
            $("#toggle_filter").html('Afficher les filtres');
        }
    });

    if ($(".menuParent")) {
        getSubMenu();
    }

    if ($(".catChilds")) {
        getSubCatProduct();
    }

    if ($('.files')) {
        prepareNewFileForm();
    }

    if ($('.counter')) {
        initCounter()
    }
});

function initCounter() {
    var id = 1;
    $(".counter").each(function () {
        var text = $(this);
        var len = text[0].value.length;
        var classes = "div-counter";
        if (text.attr('data-limit') && text.attr('data-limit') < len) {
            classes = classes + " nok";
        } else {
            classes = classes + " ok";
        }
        var divLen = $('<div id="counter-' + id + '" class="' + classes + '">' + len + '</div>');
        divLen.insertAfter(text);
        eventCounter(text, id);
        id = id + 1;
    });
}
function eventCounter(text, id) {
    text.on('keyup', function () {
        var len = text[0].value.length;
        if (text.attr('data-limit') < len) {
            classes = "nok";
        } else {
            classes = "ok";
        }
        $('#counter-' + id).html(len);
        $('#counter-' + id).removeClass("ok");
        $('#counter-' + id).removeClass("nok");
        $('#counter-' + id).addClass(classes);
    });
}

function isEmpty(el) {
    return !$.trim(el.html())
}

function getSubMenu() {
    $(".menuParent").each(function (index) {
        if (isEmpty($('#menuChilds-' + $(this).attr("data-id")))) {
            $.ajax({
                type: "GET",
                url: $(this).attr("data-url"),
                success: function (data) {
                    $('#menuChilds-' + data.id).html(data.render);
                    getSubMenu();
                }
            });
        }
    });
}

function getSubCatProduct() {
    $(".catChilds").each(function (index) {
        if (isEmpty($(this))) {
            var container = $(this);
            $.ajax({
                type: "GET",
                url: $(this).attr("data-url"),
                success: function (data) {
                    container.html(data.render);
                    setSortable();
                    getSubCatProduct();
                }
            });
        }
    });
}

function prepareNewFileForm(result) {
    var collectionHolder = $('ul.files');
    collectionHolder.find('li.formFile').each(function () {
        addFileFormDeleteLink($(this));
    });

    if (result) {
        $.each(result, function (key, value) {
            var id = addFileForm(collectionHolder, value);
            $(collectionHolder.children()[id]).find('[type=hidden]').val(value);
            var img = $(collectionHolder.children()[id]).find('.formFilePicture');
            img.attr('src', key);
        });
    }
}

function addFileForm(collectionHolder, $newLinkLi) {
    var id = collectionHolder.children().length;
    var prototype = collectionHolder.attr('data-prototype');
    var newForm = prototype.replace(/__name__/g, id);
    var $newFormLi = $('<li class="formFile"></li>').append(newForm);
    collectionHolder.append($newFormLi);
    addFileFormDeleteLink($newFormLi);
    return id;
}

function addFileFormDeleteLink($fileFormLi) {
    var $removeFormA = $('<div class="form-group"><div class="col-lg-2"></div><div class="col-lg-10"><a class="deleteFile btn btn-danger" href="#"><i class="fa fa-trash-o"></i> Supprimer ce fichier</a></div></div>');
    if ($fileFormLi.find('.deleteFile').length < 1) {
        $fileFormLi.append($removeFormA);
    }
    $removeFormA.on('click', function (e) {
        e.preventDefault();
        $fileFormLi.remove();
    });
}


function strip_tags(str, allowed_tags) {

    var key = '', allowed = false;
    var matches = [];
    var allowed_array = [];
    var allowed_tag = '';
    var i = 0;
    var k = '';
    var html = '';
    var replacer = function (search, replace, str) {
        return str.split(search).join(replace);
    };

    if (allowed_tags) {
        allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
    }
    str += '';

    matches = str.match(/(<\/?[\S][^>]*>)/gi);
    for (key in matches) {
        if (isNaN(key)) {
            continue;
        }
        html = matches[key].toString();
        allowed = false;
        for (k in allowed_array) {            // Init
            allowed_tag = allowed_array[k];
            i = -1;

            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + '>');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + ' ');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('</' + allowed_tag);
            }

            if (i == 0) {
                allowed = true;
                break;
            }
        }
        if (!allowed) {
            str = replacer(html, "", str);
        }
    }
    return str;
}


function setSortable() {
    $("table.sortable").rowSorter({
        handler: "td.sorter",
        onDrop: function (tbody, row, index, oldIndex) {
            $.each($(tbody).find("tr"), function (key, value) {
                $.ajax({
                    type: "GET",
                    url: $(value).attr("data-url") + '?value=' + key
                });
            });
        }
    });
}

function showPdf(obj){
    var path = $(obj).attr('data-pdf');
    $('.show_pdf iframe').remove();
    $('.show_pdf').append('<iframe src="'+ path +'" width="400" height="600"></iframe>');
}