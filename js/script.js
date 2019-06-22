$(document).ready(() => {
    
    $('#documentation').on('click', () => {
        //$('#page').load('documentacao.html')

        $.get('../pages/documentacao.html', data => { 
            $('#page').html(data)
        })
    })
    $('#support').on('click', () => {
        //$('#page').load('suporte.html')
        $.get('../pages/suporte.html', data => {
            $('#page').html(data)
        })
    })     

    $('#aplicar').on('click', () =>{

        let data_inicio = $('#data_inicio').val()
        let data_fim = $('#data_fim').val()

        $.ajax({
            type: 'GET',
            url: '../server/app.php',
            data: `data_inicio=${data_inicio}&data_fim=${data_fim}`, //x-www-form-urlencoded
            dataType: 'json',
            success: dados => {
            
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
                $('#clientesAtivos').html(dados.clientesAtivos)
                $('#clientesInativos').html(dados.clientesInativos)
                $('#totalReclamacoes').html(dados.totalReclamacoes)
                $('#totalElogios').html(dados.totalElogios)
                $('#totalSugestoes').html(dados.totalSugestoes)
                $('#totalDespesas').html(dados.totalDespesas)
            }, 
            error: erro => { console.log(erro) }
        })
        //metodo, url, dados, sucesso, erro
    })

})