jQuery(document).ready(function($) {

    /* List members  */
    let membersCounter = $('#list-members-wrapper .list-member-item').length;

    $(document).on('click', '.btn-add-list-member', function(){
        var $template = $('#template-list-members');
        var html = $template.html().replace(/\{INDEX\}/gi, membersCounter);

        $('#list-members-wrapper').append(html);

        membersCounter++;

        return false;
    });

    $(document).on('click', '.btn-delete-list-member', function(){
        $(this).closest('div.list-member-item').fadeOut('slow', function(){
            $(this).remove();
        });
        return false;
    });
});
