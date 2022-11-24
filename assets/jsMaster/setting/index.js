function _onload(data){
    $('#body').html(data.tmBody);
    myCode=data.code;
    
    $('#bodyTM').html(_form());
    $('#footer').html(data.tmFooter+data.footer);
    
    _startTabel("dt");
    $('#username').val(_nama);
}
function _form() {
    // <img class="img-fluid d-block mx-auto" src="`+assert+`fs_css/bgForm.png" alt="sasasa"></img>
    return `
    
    <div class="page-header" style="padding: 20px; margin-top: 4%;">
        <div class="page-block">`
            +_formData()
        +`</div>
    </div>`;
    
}

function _formData() {
    return `<div class="row m-2 shadow">`
                +_formIcon({
                    icon:'<i class="mdi mdi-file-check"></i>'
                    ,text:"<h3>Perbarui Akun</h3>",
                    classJudul:' p-2',
                    id:"form1",
                    sizeCol:undefined,
                    bgHeader:"bg-info text-light",
                    attrHeader:`style="height: max-content;"`,
                    bgForm:"#fff; font-size:15px;",
                    isi:_fupdMember(),
                })
            +`</div>`;
}
function _perbaruiAkun() {
    param={
        username:$('#username').val(),
        passOld:$('#passwordOld').val(),
        passNew:$('#passwordNew').val()
    }
    _post('proses/perbaruiAkun',param).then(res=>{
        res=JSON.parse(res);
        if(res.exec){
            // _modalHide('modal');
            _redirect("control/logout");
        }else{
            return _toast({bg:'e', msg:res.msg});
        }
    });
}