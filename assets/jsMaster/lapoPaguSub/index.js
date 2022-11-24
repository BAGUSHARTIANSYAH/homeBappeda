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
    infoSupport1=[];
    infoSupport1.push({ 
        clsBtn:`btn-success fzMfc`
        ,func:"lapoPaguSub(1,'all')"
        ,icon:`<i class="mdi mdi-file-check"></i>PDF ALL`
        ,title:"Semua Dinas"
    });
    infoSupport1.push({ 
        clsBtn:`btn-primary fzMfc`
        ,func:"lapoPaguSub(0,'all')"
        ,icon:`<i class="mdi mdi-file-check"></i>Excell All`
        ,title:"Semua Dinas"
    });
    return `<div class="row m-2 shadow">`
                +_formIcon({
                    icon:'<i class="mdi mdi-file-check"></i>'
                    ,text:"<h3>Daftar Dinas</h3>",
                    classJudul:' p-2',
                    id:"form1",
                    btn:_btnGroup(infoSupport1),
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
        clsBtn:`btn-success fzMfc`
        ,func:"lapoPaguSubIndex(1,)"
        ,icon:`<i class="mdi mdi-file-check"></i>PDF`
        ,title:"preview"
    });
    infoSupport1.push({ 
        clsBtn:`btn-primary fzMfc`
        ,func:"lapoPaguSubIndex(0,)"
        ,icon:`<i class="mdi mdi-file-check"></i>Excell`
        ,title:"preview"
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
function lapoPaguSub(pdf,ket){
    var data =btoa(JSON.stringify({
        kdDinas:'Semua Dinas',
        nmDinas:'Terhubung',
        pdf:pdf,
        ket:ket
    }));
    _redirectOpen("laporan/laporanPaguSub/"+data);
}
function lapoPaguSubIndex(pdf,i) {
    var data =btoa(JSON.stringify({
        kdDinas:_.dinas[i].kdDinas,
        nmDinas:_.dinas[i].nmDinas,
        pdf:pdf,
        ket:'-'
    }));
    _redirectOpen("laporan/laporanPaguSub/"+data);
}