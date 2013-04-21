commentController = function()
{

    var self = this;
    var errors = [];

    this.init = function()
    {
        $('#some_form').unbind();
        $('#some_form input[type=submit]').unbind();

        self.sendComment();

        $('#last_name').hide();
    }

    this.redirectTo = function(uri)
    {
        window.location.href = uri;

        return false;
    }

    this.sendComment = function()
    {
        var form = $('#some_form');
        var options = {
            beforeSubmit:  function(){
                $.each(errors, function(k,v){
                    v.removeClass('error');
                });
                errors = [];
            },
            success:       function(data){
                if (data.errors) {
                    $.each(data.errors, self.showInputError );
                } else if (data.success) {
                    if (data.redirect) {
                        self.redirectTo(data.redirect);
                    } else if (data.html) {
                        $('div.last-comment > ul').prepend(data.html);
                        $('body').animate({scrollTop:$('li#comment_'+data.id).offset().top},500)
                    }

                    form.trigger( 'reset' );
                    form.animate({
                        opacity: 0.25,
                        left: '+=50',
                        height: 'toggle'
                    }, 3000, function() {
                        form.parent().remove();
                    });
                }
            },
            dataType:  'json',
            type: 'post',
            timeout:   5000
        };

        form.ajaxForm(options);
    }

    this.goToComments = function()
    {
        $('body').animate({scrollTop:$('form#some_form').offset().top},500)

        return false;
    }

    this.showInputError = function(name, message)
    {
        if (name == 'text') {
            var item = $('textarea[name='+name+']');
        } else if (name == 'name' || name == 'email') {
            var item = $('input[name='+name+']');
        } else {

        }

        var parentItem = item.parent();

        errors[errors.length] = parentItem;

        var errorSpan = parentItem.find('span.error-text');

        parentItem.addClass('error');
        errorSpan.html(message);

        return false;
    }
}

var commentController = new commentController();
$(document).ready(function(){
    commentController.init();
});