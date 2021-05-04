
$(_event => {
    
    $('#button').click(() => {
        $('#toggle').toggle('fast')
    });
   
    
    $('.toggle').click((e) => {
        const tg = $(e.target);
        tg.closest('.card').find('.show').toggle('fast');
    });
    
    
    $(document.body).on('click', '.mod', (e) => {
        e.preventDefault();

        const tg = $(e.target);
        const parent = tg.closest('.card');
        const text = tg.closest('.card').find('.text');
        if(text.attr("contenteditable") == "false") {
            text.attr("contenteditable" , "true");
        } else {
            text.attr("contenteditable" , "false");
        }
        text.css("caret-color", "red");
        text.focus();
        parent.find('.inVisible').toggle('fast');

    });

    
    $(document.body).on('change', '.upload', (e) => {

        const file = $("input[type=file]").get(0).files[0];
        if(file){
            let reader = new FileReader();
            reader.onload = function(){
                $("#previewImg").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    });

    $(document.body).on('click', '.submitMod', (e) => {
        e.preventDefault();

        const tg = $(e.target);
        const imgFrm = tg.closest('form');
        const card = imgFrm.closest('.card');
        if (!imgFrm.length) {
            console.log('NO form for ', tg);
            return;
        }
        let frm = new FormData(imgFrm[0]);
        frm.append('ajax', true);
        let text = card.children('.main').find('.text').text();
        frm.append('text', text);

        let dI = imgFrm.find('#deleteImage').prop('value');
        frm.append(deleteImage, dI);

        $.post({
            url: './com/modifyComVal.php',
            data: frm,
            processData: false,
            contentType: false,
        })
        .done((data, textStatus, jqXHR) => {
            card.replaceWith(data);
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.log(textStatus, errorThrown);
            alert(`Erreur requête ${textStatus}`);
        })
        .always(() => {

        });
    
    });


    $(window).scroll(() => {
        if(($(window).scrollTop() + $(window).height()) == $(document).height())  {

            let id = $('.comment').find('.level-0:last').prop('id');
            id = id.split('-');
            id = (id.length > 1) ? id[1] : null;
            if (!id)
                return;

            $.get('./com/ajax/getNextCom.php', {comId : id})
            .done((data, textStatus, jqXHR) => {
                console.log(data);
                const elem = $('<div></div>');
                elem.html(data);
                elem.children().appendTo($('.comment'));
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                console.log(textStatus, errorThrown);
                alert(`Erreur requête ${textStatus}`);
            })
            .always(() => {

            });
            
        }
    });


    $(document.body).on('click', '.submitLike', (e) => {
        e.preventDefault();

        const tg = $(e.target);
        const likeFrm = tg.closest('form');
        const card = likeFrm.closest('.card');

        let frm = new FormData();
        frm.append('ajax', true);
        

        let id = card.prop('id');
        id = id.split('-');
        id = (id.length > 1) ? id[1] : null;
        if (!id)
            return;
        frm.append('comId', id);

        $.post({
            url: './com/like.php',
            data: frm,
            processData: false,
            contentType: false,
        })
        .done((data, textStatus, jqXHR) => {
            card.replaceWith(data);
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.log(textStatus, errorThrown);
            alert(`Erreur requête ${textStatus}`);
        })
        .always(() => {

        })
    
    });


    $(document.body).on('click', '.submitDisLike', (e) => {
        e.preventDefault();

        const tg = $(e.target);
        const likeFrm = tg.closest('form');
        const card = likeFrm.closest('.card');

        let frm = new FormData();
        frm.append('ajax', true);

        let id = card.prop('id');
        id = id.split('-');
        id = (id.length > 1) ? id[1] : null;
        if (!id)
            return;
        frm.append('comId', id);
        
        $.post({
            url: './com/unLike.php',
            data: frm,
            processData: false,
            contentType: false,
        })
        .done((data, textStatus, jqXHR) => {
            card.replaceWith(data);
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.log(textStatus, errorThrown);
            alert(`Erreur requête ${textStatus}`);
        })
        .always(() => {

        })
        
    });
    

    
    // $(function() {

    //     // $(".hiden").dialog({
    //     //     autoOpen : false, modal : true, show : "blind", hide : "blind"
    //     //   });
      
    //     $(".upload").click((event) => {
    //         event.preventDefault();

    //         // const frm = $('aside .modCom').clone();
    //         // frm.find('form').append(`<input type="hidden" name="comId" value="${id}" />`);
    //         // frm.clone().dialog({
    //         //     autoOpen : true, modal : true, show : "blind", hide : "blind"
    //         //   });

    //         $.get('./com/component/popCom.php', ) 
            
    //         .done((data, textStatus, jqXHR) => {
    //             $(data).dialog({
    //                 autoOpen : true, modal : true, show : "blind", hide : "blind"
    //               });
    //         })
    //         .fail((jqXHR, textStatus, errorThrown) => {
    //             console.log(textStatus, errorThrown);
    //             alert(`Erreur requête ${textStatus}`);
    //         })
    //         .always(() => {

    //         });

    //     });
    //   });
  
});

