function _onload(data){
    $('#body').html(data.tmBody);
    myCode=data.code;
    // _.info=data.info;
    
    _.apbd=data.apbd;
    _.dinas=data.dinas;
    _.ind=0;
    _.kdApbd=_.apbd[0].value;
    
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
        clsBtn:`btn-secondary fzMfc`
        ,func:"lapoBelanjaPDFAll(1)"
        ,icon:`<i class="mdi mdi-file-check"></i>PRA`
        ,title:"Semua Dinas"
    });
    infoSupport1.push({ 
        clsBtn:`btn-primary fzMfc`
        ,func:"lapoBelanjaPDFAll(2)"
        ,icon:`<i class="mdi mdi-file-check"></i>RENJA`
        ,title:"Semua Dinas"
    });
    infoSupport1.push({ 
        clsBtn:`btn-success fzMfc`
        ,func:"lapoBelanjaPDFAll(3)"
        ,icon:`<i class="mdi mdi-file-check"></i>FINAL`
        ,title:"Semua Dinas"
    });
    return `<div class="row m-2 shadow">`
                +_formIcon({
                    icon:'<i class="mdi mdi-file-check"></i>'
                    ,text:"<h3>Daftar Dinas</h3>",
                    classJudul:' p-2',
                    id:"form1",
                    btn:_btnGroup(infoSupport1),
                    // btn:_btn({
                    //     color:"primary shadow",
                    //     judul:"simpan Perubahan",
                    //     attr:"style='padding:5px;font-size:15px;' onclick='savedOPD()'",
                    //     // class:"btn btn-success btn-block"
                    // }),
                    sizeCol:undefined,
                    bgHeader:"bg-info text-light",
                    attrHeader:`style="height: max-content;"`,
                    bgForm:"#fff; font-size:15px;",
                    isi:_inpComboBox({
                            judul:"Filter",
                            id:"apbd",
                            color:"black",  
                            data:_.apbd,
                            bg:"bg-warning text-dark",
                            method:"sejajar",
                            attr:"font-size:15px;",
                            change:"_setApbd(this)",
                            // index:true
                        })
                        +_lines({attr:"background:white"})
                        +`<div id='tabelShow' style="margin: auto;">`
                            +setTabel()
                        +`</div>`,
                })
            +`</div>`;
}
function setTabel(){
    infoSupport1=[];
    infoSupport1.push({ 
        clsBtn:`btn-warning fzMfc`
        ,func:"lapoBelanjaPDF(1,)"
        ,icon:`<i class="mdi mdi-file-check"></i>PRA`
        ,title:"Lihat laporan"
    });
    infoSupport1.push({ 
        clsBtn:`btn-primary fzMfc`
        ,func:"lapoBelanjaPDF(2,)"
        ,icon:`<i class="mdi mdi-file-check"></i>RENJA`
        ,title:"Lihat laporan"
    });
    infoSupport1.push({ 
        clsBtn:`btn-success fzMfc`
        ,func:"lapoBelanjaPDF(3,)"
        ,icon:`<i class="mdi mdi-file-check"></i>Final`
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
function lapoBelanjaPDF(tahapan,i) {
    var data =btoa(JSON.stringify({
        kdDinas:_.dinas[i].kdDinas,
        nmDinas:_.dinas[i].nmDinas,
        tahapan:tahapan,
        apbd:_.kdApbd
    }));
    _redirectOpen("laporan/lapoBelanja/"+data);
}
function lapoBelanjaPDFAll(tahapan) {
    var data =btoa(JSON.stringify({
        kdDinas:'',
        nmDinas:'',
        tahapan:tahapan,
        apbd:_.kdApbd
    }));
    _redirectOpen("laporan/lapoBelanja/"+data);
}
function _setApbd(v) {
    _.kdApbd=v.value;
}