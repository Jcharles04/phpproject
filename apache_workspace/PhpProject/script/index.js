
$(_event => {
    
    $('#button').click(() => {
        $('#toggle').toggle()
    });
   
    
    $('.toggle').click((event) => {
        const tg = $(event.target);
        tg.closest('.card').find('.show').toggle();
    });
    
    $(function() {

        $(".mod").click((event) => {
            event.preventDefault();

            const tg = $(event.target);
            tg.closest('.card').find('.text').attr("contenteditable" , "true");
            tg.closest('.card').find('.text').css("caret-color", "red");
            $( '.text' ).focus();

        })
    });

    /*
    $(function() {

        // $(".hiden").dialog({
        //     autoOpen : false, modal : true, show : "blind", hide : "blind"
        //   });
      
        $(".mod").click((event) => {
            event.preventDefault();

            const tg = $(event.target);
            let id = tg.closest('.card').prop('id');
            id = id.split('-');
            id = (id.length > 1) ? id[1] : null;
            if (!id)
                return;
            console.log(id);

            // const frm = $('aside .modCom').clone();
            // frm.find('form').append(`<input type="hidden" name="comId" value="${id}" />`);
            // frm.clone().dialog({
            //     autoOpen : true, modal : true, show : "blind", hide : "blind"
            //   });

            $.get('./com/ajax/modCom.php', {comId: id})
            .done((data, textStatus, jqXHR) => {
                $(data).dialog({
                    autoOpen : true, modal : true, show : "blind", hide : "blind"
                  });
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                console.log(textStatus, errorThrown);
                alert(`Erreur requête ${textStatus}`);
            })
            .always(() => {

            });

        });
      });*/
  
});


/*
console.log($(document));

    const toto = $(document.body);
    console.log(toto);
    if (toto.length) {
        console.log("Au moins 1 element dans toto");
    }
    console.log($('.comment .card'));
    $('.comment .card').append($('<div>toto</div>').append('<p>lala</p>'));
    $('.comment .card').on('click', clickEvent => {
        alert('click');
    });*/