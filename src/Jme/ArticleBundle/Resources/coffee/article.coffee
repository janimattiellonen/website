$('document').ready ->

    selected = []
    $('.tag_item_selector .item ul li').each (i) ->
        val = $(this).attr 'data-id'
        val = parseInt val
        selected.push val

    options = {
    mainElement:            '.tag_item_selector',
    autoCompleteElement:    '.item_field',
    containerElement:       '.items ul',
    source:                 url_tagSource,
    saveUrl:                url_tagAddSource,
    selected:               selected,
    canAddNew:              true
    minLength:              3
    }

    tagComplete = new App.Selector(options)

    $('div.item_selector a.remove').live 'click', ->
        return false

    $('.tag_item_selector').find('.add-item-btn').live 'click', ->

        tag = $.trim($('.item_field').val() )

        tagComplete.saveItem tag
        return false;

    tagComplete.bind()

    window.ttkTagComplete = tagComplete

    new App.AjaxElement.Default '.ajax-link', null, [ new App.ElementErrorizer.Default('errorized-element', 0 , -20)]

    new App.ConfirmDialog('.confirm-dialog', {
    width: 720,
    position: [null, 100]
    headerAttribute: 'header'
    refreshDialogOnOpening: true
    }, null)