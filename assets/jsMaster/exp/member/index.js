function _onload(data){
    $('#body').html(data.tmBody);
    myCode=data.code;
    
    _.ddata=data.ddata;
    _.dkantor=data.dkantor;
    _.djabatan=data.djabatan;
    _.dinfo=data.dinfo;
    // console.log(data.idBody);
    
    $('#bodyTM').html(_form());
    $('#footer').html(data.tmFooter+data.footer);

    _startTabel("dt");
    _startChart();
}
function _form() {
    // <img class="img-fluid d-block mx-auto" src="`+assert+`fs_css/bgForm.png" alt="sasasa"></img>
    return `
    
    <div class="page-header" style="padding: 20px; margin-top: 4%;">
        <div class="page-block">`
            +informasix()
            +_formData()
            +`
        </div>
    </div>`;
}
function informasix() {
    fhtml='';
    _.dinfo.forEach((v,i) => {
        fhtml+=`
            <div class="col-md-3 mb">
                <div class="white-panel bg-light  shadow">
                    `+_sejajar({
                        attrCol:`style=""`,
                        data:[{
                            isi:`
                            <p><img src="`+assert+v.logo+`" class="img-circle" height="85"></p>
                            <p><b>`+v.valueName+`</b></p>
                            `
                        },{
                            isi:_progressBar({
                                    cls:_bgMinMax(parseFloat(v.persen),true),
                                    value:v.persen+"%"
                                })
                                +_fi2Kolom({
                                    attr:'margin: auto;width: max-content;',
                                    cls:'',
                                    attrName:"",
                                    attrVal:"",
                                    data:[
                                        {
                                            valueName:'BERPROSES',
                                            value:_span({text:_$(v.sub.length),id:"span",cls:'badge bg-warning p-2 text-dark'})
                                        }
                                        ,{
                                            valueName:'Persentase',
                                            value:_span({text:v.persen+" %",id:"span",cls:'badge bg-success p-2 '})
                                        }
                                    ]
                                })
                        }]
                    })
                +`</div>
            </div>
        `;
    });
    return `
    <div class="row">
        `+fhtml+`
        <div class="col-md-3 mb">
            <div class="white-panel bg-light  shadow">
                <canvas id="pie" height="33vh" width="80vw"></canvas>
            </div>
        </div>

    </div>
    `;
}
function _startChart() {
    dt={
        nm:[],
        val:[],
        bg:[
            "#009FFF",
            "#ff6d00",
            "#2CFF00"
        ]
    };

    _.dinfo.forEach(v => {
        dt.nm.push(v.valueName);
        dt.val.push(parseFloat((v.persen*100)/_.dinfo[0].total).toFixed(2));
    });

    var ctx = document.getElementById("pie").getContext('2d');
    new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels:dt.nm,
                datasets: [{
                    backgroundColor:dt.bg,
                    data:dt.val,
                    
                }]
            },
            options: {
                legend: {
                    display: true,
                    position: 'bottom',
                        labels: {
                            fontColor: '#71748d',
                            fontFamily: 'Circular Std Book',
                            fontSize: 10,
                            text:"saass"
                        }
                }
        }

    });
}
function _formData() {
    infoSupport='';
    if(_kdJabatan>1){
        infoSupport=_btn({
            color:"primary shadow",
            judul:"Tambah Data",
            attr:"style='float:right; padding:5px;font-size: medium;' onclick='addData()'",
            // class:"btn btn-success btn-block"
        });
    }
    
    return `<div class="row m-2 shadow">`
                +_formIcon({
                    icon:'<i class="mdi mdi-file-check"></i>'
                    ,text:"<h3>Data Member</h3>",
                    classJudul:' p-2 display-4',
                    btn:infoSupport,
                    id:"form1",
                    sizeCol:undefined,
                    bgHeader:"bg-info display-4 text-light",
                    attrHeader:`style="height: max-content;"`,
                    bgForm:"#fff; font-size: medium;",
                    isi:`<div id='tabelShow' style="margin: auto;">`
                            +setTabel()
                        +`</div>`,
                })
            +`</div>`;
}
function setTabel(){
    infoSupport1=[];
    infoSupport1.push({ 
        clsBtn:`btn-outline-secondary fzMfc`
        ,func:"info()"
        ,icon:`<i class="mdi mdi-information-variant "></i>`
        ,title:"Perbarui"
    });
    infoSupport1.push({ 
        clsBtn:`btn-outline-primary fzMfc`
        ,func:"_refresh()"
        ,icon:`<i class="mdi mdi-refresh"></i>`
        ,title:"Refresh"
    });
    infoSupport1.push({ 
        clsBtn:`btn-outline-warning fzMfc`
        ,func:"updData()"
        ,icon:`<i class="mdi mdi-grease-pencil"></i>`
        ,title:"Perbarui"
    });
    infoSupport1.push({ 
        clsBtn:`btn-outline-danger fzMfc`
        ,func:"delData()"
        ,icon:`<i class="mdi mdi-delete-forever"></i>`
        ,title:"Hapus"
    });
    return _tabelResponsive(
        {
            id:"dt"
            ,isi:_tabel(
                {
                    data:_.ddata
                    ,no:1
                    ,kolom:[
                        "nmKantor","nmJabatan","nmMember","username","password"
                    ]
                    ,namaKolom:[
                        "Kantor","Jabatan","Nama","Username","Password"
                    ],
                    action:infoSupport1
                })
        });;
}
function addData() {
    _modalEx1({
        judul:"Tambah Data".toUpperCase(),
        icon:`<i class="mdi mdi-note-plus"></i>`,
        cform:`text-light`,
        bg:undefined,
        minWidth:"500px; font-size: medium;",
        isi:_fmember(),
        footer:_btn({
                    color:"primary shadow",
                    judul:"Close",
                    attr:`style='float:right; padding:5px;font-size: medium;' onclick="_modalHide('modal')"`,
                    class:"btn btn-secondary"
                })
                +_btn({
                    color:"primary shadow",
                    judul:"SIMPAN",
                    attr:"style='float:right; padding:5px;font-size: medium;' onclick='addDataed()'",
                    class:"btn btn-primary"
                })
    });
}
function addDataed(){
    param={
        nmMember    :$('#nmMember').val(),
        kdKantor    :$('#kdKantor').val(),
        kdJabatan   :$('#kdJabatan').val(),
        kabupaten   :$('#kabupaten').val(),
        kecamatan   :$('#kecamatan').val(),
        desa        :$('#desa').val(),
        noHp        :$('#noHp').val(),
        tambahan    :$('#tambahan').val(),
        username    :$('#username').val(),
        password    :$('#password').val()
    }
    if(_isNull(param.nmMember))return _toast({bg:'e',msg:'Tambahkan Nama Member !!!'});

    if(_isNull(param.username))return _toast({bg:'e',msg:'Tambahkan Username !!!'});
    if(_isNull(param.password))return _toast({bg:'e',msg:'Tambahkan Password !!!'});

    _post('proses/insMember',param).then(res=>{
        res=JSON.parse(res);
        if(res.exec){
            _modalHide('modal');
            _respon(res.data);
        }else{
            return _toast({bg:'e', msg:res.msg});
        }
    });
}
function _respon(data){
    if(data!=null){
        _.ddata=data.ddata;
    }
    $('#tabelShow').html(setTabel());
    _startTabel("dt");
}
function updData(ind) {
    _modalEx1({
        judul:"Perbarui Data".toUpperCase(),
        icon:`<i class="mdi mdi-note-plus"></i>`,
        cform:`text-light`,
        bg:"bg-warning",
        minWidth:"500px; font-size: medium;",
        isi:_fmember(),
        footer:_btn({
                    color:"primary shadow",
                    judul:"Close",
                    attr:`style='float:right; padding:5px;font-size: medium;' onclick="_modalHide('modal')"`,
                    class:"btn btn-secondary"
                })
                +_btn({
                    color:"primary shadow",
                    judul:"SIMPAN",
                    attr:"style='float:right; padding:5px;font-size: medium;' onclick='updDataed("+ind+")'",
                    class:"btn btn-primary"
                })
    });
    _tamp2=_.ddata[ind].username.split("-");
    if(_tamp2.length==1){
        $('#username').val(_.ddata[ind].username);
    }else{
        $('#username').val(_tamp2[1]);
    }
    $('#nmMember').val(_.ddata[ind].nmMember);

    $('#kdKantor').val(_.ddata[ind].kdKantor);
    $('#kdKantor').prop("disabled",true);

    $('#kdJabatan').val(_.ddata[ind].kdJabatan);
    $('#kabupaten').val(_.ddata[ind].kabupaten);
    $('#kecamatan').val(_.ddata[ind].kecamatan);
    $('#desa').val(_.ddata[ind].desa);
    $('#noHp').val(_.ddata[ind].noHp);
    $('#tambahan').val(_.ddata[ind].tambahan);
    
    $('#password').val(_.ddata[ind].password);
}
function updDataed(ind){
    param={
        kdKantor    :_.ddata[ind].kdKantor,
        kdMember    :_.ddata[ind].kdMember,
        nmMember    :$('#nmMember').val(),
        kdJabatan   :$('#kdJabatan').val(),
        kabupaten   :$('#kabupaten').val(),
        kecamatan   :$('#kecamatan').val(),
        desa        :$('#desa').val(),
        noHp        :$('#noHp').val(),
        tambahan    :$('#tambahan').val(),
        username    :_tamp2[0]+"-"+$('#username').val(),
        password    :$('#password').val()
    }
    if(_isNull(param.nmMember))return _toast({bg:'e',msg:'Tambahkan Nama Member !!!'});

    if(_isNull(param.username))return _toast({bg:'e',msg:'Tambahkan Username !!!'});
    if(_isNull(param.password))return _toast({bg:'e',msg:'Tambahkan Password !!!'});
    _post('proses/updMember',param).then(res=>{
        res=JSON.parse(res);
        if(res.exec){
            _modalHide('modal');
            _respon(res.data);
        }else{
            return _toast({bg:'e', msg:res.msg});
        }
    });
}
function delData(ind) {
    _modalEx1({
        judul:"Konfirmasi".toUpperCase(),
        icon:`<i class="mdi mdi-note-plus"></i>`,
        cform:`text-light`,
        bg:"bg-danger",
        minWidth:"500px; font-size: medium;",
        isi:"Hapus Data Ini ?",
        footer:_btn({
                    color:"primary shadow",
                    judul:"Close",
                    attr:`style='float:right; padding:5px;font-size: medium;' onclick="_modalHide('modal')"`,
                    class:"btn btn-secondary"
                })
                +_btn({
                    color:"primary shadow",
                    judul:"SIMPAN",
                    attr:"style='float:right; padding:5px;font-size: medium;' onclick='delDataed("+ind+")'",
                    class:"btn btn-primary"
                })
    });
}
function delDataed(ind){
    param={
        kdKantor    :_.ddata[ind].kdKantor,
        kdMember    :_.ddata[ind].kdMember,
    }
    _post('proses/delMember',param).then(res=>{
        res=JSON.parse(res);
        if(res.exec){
            _modalHide('modal');
            _respon(res.data);
        }else{
            return _toast({bg:'e', msg:res.msg});
        }
    });
}
function info(ind) {
    ftam=[];
    ftam.push({
        valueName:"Kabupaten",
        value:_.ddata[ind].kabupaten
    });
    ftam.push({
        valueName:"Kecamatan",
        value:_.ddata[ind].kecamatan
    });
    ftam.push({
        valueName:"Desa",
        value:_.ddata[ind].desa
    });
    ftam.push({
        valueName:"no HP",
        value:_.ddata[ind].noHp
    });
    ftam.push({
        valueName:"Detail",
        value:_.ddata[ind].tambahan
    });
    _modalEx1({
        judul:"Informasi".toUpperCase(),
        icon:`<i class="mdi mdi-note-plus"></i>`,
        cform:`text-light`,
        bg:"bg-secondary",
        minWidth:"500px; font-size: medium;",
        isi:_fi2Kolom({
            attr:'margin: auto;',
            cls:'',
            attrName:"",
            attrVal:"",
            data:ftam
        }),
        footer:_btn({
                    color:"primary shadow",
                    judul:"Close",
                    attr:`style='float:right; padding:5px;font-size: medium;' onclick="_modalHide('modal')"`,
                    class:"btn btn-secondary"
                })
    });
}
function _refresh(ind){
    _modalEx1({
        judul:"Konfirmasi".toUpperCase(),
        icon:`<i class="mdi mdi-refresh"></i>`,
        cform:`text-light`,
        bg:"bg-primary",
        minWidth:"500px; font-size: medium;",
        isi:"Refresh Hak Akses "+_.ddata[ind].username+" ??",
        footer:_btn({
                color:"primary shadow",
                judul:"Close",
                attr:`style='float:right; padding:5px;font-size: medium;' onclick="_modalHide('modal')"`,
                class:"btn btn-secondary"
            })
            +_btn({
                color:"primary shadow",
                judul:"Refresh",
                attr:"style='float:right; padding:5px;font-size: medium;' onclick='_refreshed("+ind+")'",
                class:"btn btn-primary"
            })
    });
}
function _refreshed(ind){
    param={
        kdJabatan   :_.ddata[ind].kdJabatan,
        kdMember    :_.ddata[ind].kdMember1
    }
    // return console.log(param);
    _post("Proses/refreshHakAkses",param).then(res=>{
        res=JSON.parse(res);
        if(res.exec){
            _modalHide('modal');
            return _toast({bg:'s', msg:"sukses"});
        }else{
            return _toast({bg:'e', msg:res.msg});
        }
    })
}