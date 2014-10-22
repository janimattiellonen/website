$('document').ready ->
    $('body').on 'click', '.url-btn', (e) ->
        #$(this).parent().find('span').toggle()

        dialogId = 'dialog-' + $(this).parent().data("id")
        show_dialog(dialogId)

    $('.dialog').dialog
        modal: true
        autoOpen: false
        buttons:
            Ok: ->
                $(this).dialog "close"
                return

    show_dialog = (dialogId) ->
        $('#' + dialogId).dialog("open")