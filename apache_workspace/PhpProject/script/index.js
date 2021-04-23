
$(_event => {
    
    $('#button').click(() => {
        $('#toggle').toggle('fast')
    });
   
    
    $('.toggle').click((event) => {
        const tg = $(event.target);
        tg.closest('.card').find('.show').toggle('fast');
    });
    
   $(function() {

        $(".mod").click((event) => {
            event.preventDefault();

            const tg = $(event.target);
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

        })
    });
    
    $('input[type="file"]').on('change', (e) => {
        console.log('change file');
        let newFile = e.currentTarget;
        if (newFile.files && newFile.files[0]) {
            $(newFile).next('.img').html(newFile.files[0].name)
            console.log($(newFile).next('.img').html(newFile.files[0].name))
            let reader = new FileReader();
            reader.onload = () => {
                newFile.closest('.card').children('.main').find('#img').attr('src', e.target.result)
            }
            reader.readAsDataURL(newFile.files[0])
        }
    });


    $(function() {

        $('.submitMod').click((e) => { 
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

            // let id = card.prop('id');
            // id = id.split('-');
            // id = (id.length > 1) ? id[1] : null;
            // if (!id)
            //     return;
            // frm.append(comId, id);
            // console.log(frm);

            $.post({
                url: './com/modifyComVal.php',
                data: frm,
                processData: false,
                contentType: false,
            })
            .done((data, textStatus, jqXHR) => {
                console.log(data)
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                console.log(textStatus, errorThrown);
                alert(`Erreur requête ${textStatus}`);
            })
            .always(() => {

            });
        })
    });

    $(function () {

        $(window).scroll(() => {
            if(($(window).scrollTop() + $(window).height()) == $(document).height())  {

                let id = $('.comment').find('.level-0:last').prop('id');
                id = id.split('-');
                id = (id.length > 1) ? id[1] : null;
                if (!id)
                    return;

                $.get('./com/ajax/getNextCom.php', {comId : id})
                .done((data, textStatus, jqXHR) => {
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
