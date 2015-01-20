App.InPlaceEditor =  function(container) {
    this.container = container;
    this.content = null;
    this.$textArea = null;
    this.$innerContent = null;
};

App.InPlaceEditor.prototype = (function()
{
    return {
        toTextArea: function() {
            this.loadInnerContent();
            this.content = this.$innerContent.html();
            this.loadTextArea(this.content);
            this.$innerContent.hide();
            $(this.$textArea).show();
        },

        toHtml: function() {
            this.$innerContent.html(this.$textArea.val());
            this.$textArea.hide();
            this.$innerContent.show();
            this.disableEditControls();
        },

        saveChanges: function() {

        },

        cancelChanges: function() {
            this.$textArea.hide();
            this.$innerContent.html(this.content);
            this.$textArea.val(this.content);
            this.$innerContent.show();
            this.disableEditControls();
        },

        loadInnerContent: function() {
            if (null == this.$innerContent) {
                this.$innerContent = $(this.container).find('.inner-content');
            }
        },

        loadTextArea: function(content) {
            if (null == this.$textArea) {
                this.$textArea = $($('<textarea></textarea>').html(content));
                this.$innerContent.after($(this.$textArea));
            }
        },

        enableEditControls: function() {
            $('.yes-btn', this.container).show()
            $('.no-btn', this.container).show()
            $('.in-place', this.container).hide()
        },

        disableEditControls: function() {
            $('.yes-btn', this.container).hide()
            $('.no-btn', this.container).hide()
            $('.in-place', this.container).show()
        }
    }
})();

$(document).ready(function() {
    var inPlaceEditor = new App.InPlaceEditor('#a-about');

    $('#a-about').on('click', '.in-place', function(e) {
        e.preventDefault();

        inPlaceEditor.toTextArea();
        inPlaceEditor.enableEditControls();
    });

    $('#a-about').on('click', '.yes-btn', function(e) {
        e.preventDefault();

        inPlaceEditor.saveChanges();
        inPlaceEditor.toHtml();
    });

    $('#a-about').on('click', '.no-btn', function(e) {
        e.preventDefault();

        inPlaceEditor.cancelChanges();
        inPlaceEditor.toHtml();
    });
});