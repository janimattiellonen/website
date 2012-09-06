unless App?
    App = {}

class App.RemoveDialog
    constructor: (@id, @linkElement) ->

    show: ->
        @createDialog()

    createDialog: ->
        if($(@id).length == 0)
            $dialog = $('<div></div>').attr({id: @id, title: ExposeTranslation.get('item.remove')})
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
                    text: ExposeTranslation.get("yes"),
                    click: ->
                        window.location.href = $(ff).attr "href"
                        $(this).dialog("close")
                },
                {
                    text: ExposeTranslation.get("no"),
                    click: ->
                        $(this).dialog("close")
                }

            ],
        });

$('document').ready ->

    $('a.article-remove').bind "click", (event) ->
        event.preventDefault()
        removeDialog = new App.RemoveDialog("#remove-dialog", $(this) )
        removeDialog.show()