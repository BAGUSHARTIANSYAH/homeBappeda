function _onload(data){
    $('#body').html(data.tmBody);
    myCode=data.code;
    // _.info=data.info;
    
    
    _.dinas=data.dinas;
    _.ind=0;
    
    $('#bodyTM').html(_form());
    $('#footer').html(data.tmFooter+data.footer);
    
    _startTabel("dt");
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
                    ,text:"<h3>Daftar Dinas</h3>",
                    classJudul:' p-2',
                    id:"form1",
                    btn:_btn({
                        color:"primary shadow",
                        judul:"simpan Perubahan",
                        attr:"style='padding:5px;font-size:15px;' onclick='savedOPD()'",
                        // class:"btn btn-success btn-block"
                    }),
                    sizeCol:undefined,
                    bgHeader:"bg-info text-light",
                    attrHeader:`style="height: max-content;"`,
                    bgForm:"#fff; font-size:15px;",
                    isi:`<div id='tabelShow' style="margin: auto;">`
                            +setTabel()
                        +`</div>`,
                })
            +`</div>`;
}
function setTabel(){
    infoSupport1=[];
    infoSupport1.push({ 
        clsBtn:`btn-outline-primary fzMfc`
        ,func:"lapoOpdPDF()"
        ,icon:`<i class="mdi mdi-file-check"></i>pdf`
        ,title:"Lihat laporan"
    });
    infoSupport1.push({ 
        clsBtn:`btn-outline-success fzMfc`
        ,func:"lapoOpdExcel()"
        ,icon:`<i class="mdi mdi-file-check"></i>excell`
        ,title:"Lihat laporan"
    });
    return _tabelResponsive(
        {
            id:"dt"
            ,isi:_tabel(
                {
                    data:_.dinas
                    ,no:1
                    ,kolom:[
                        "nmDinas","pra$","renja$","final$"
                    ]
                    ,namaKolom:[
                        "Nama Dinas","PRA","RENJA","FINAL"
                    ],
                    action:infoSupport1
                })
        });
}
function lapoOpdPDF(i) {
    var data =btoa(JSON.stringify({
        kdDinas:_.dinas[i].kdDinas,
        nmDinas:_.dinas[i].nmDinas,
        pdf:true
    }));
    _redirectOpen("laporan/lapoOpd/"+data);
}
function lapoOpdExcel(i) {
    var data =btoa(JSON.stringify({
        kdDinas:_.dinas[i].kdDinas,
        nmDinas:_.dinas[i].nmDinas,
        pdf:false
    }));
    _redirectOpen("laporan/lapoOpd/"+data);
}