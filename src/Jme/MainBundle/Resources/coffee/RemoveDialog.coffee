unless App?
    App = {}

class   App.RemoveDialog
    constructor: (@id, @linkElement) ->

    show: ->
        @createDialog()

    createDialog: ->
        if($(@id).length == 0)
            $dialog = $('<div></div>').attr({id: @id, title: Translator.trans('item.remove')})
            $('body').append($dialog);
        else
            $dialog = $(@id);

        ff = @linkElement

        $dialog.dialog({
            position: 'top',
            modal: true,
            resizable: false,
            width: 'auto',

            buttons: [
                {
                    text: Translator.trans("yes"),
                    click: ->
                        window.location.href = $(ff).attr "href"
                        $(this).dialog("close")
                },
                {
                    text: Translator.trans("no"),
                    click: ->
                        $(this).dialog("close")
                }

            ],
        });

$('document').ready ->

    $('a.article-remove, a.item-remove').bind "click", (event) ->
        event.preventDefault()
        removeDialog = new App.RemoveDialog("#remove-dialog", $(this) )
        removeDialog.show()