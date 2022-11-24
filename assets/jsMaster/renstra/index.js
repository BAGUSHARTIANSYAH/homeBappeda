function _onload(data){
    $('#body').html(data.tmBody);
    myCode=data.code;
    _.info=data.info;
    
    
    _.dinas=data.dinas;
    _.urusan=data.urusan;
    _.kdUrusan=_.urusan[0].value;
    _.bidang=data.bidang;
    _.kdBidang=_.bidang[0].value;
    _.ind=0;
    _.tahun=data.tahun;
    
    $('#bodyTM').html(_form());
    $('#footer').html(data.tmFooter+data.footer);
    
    _startTabel("dt");
}
function _form() {
    // <img class="img-fluid d-block mx-auto" src="`+assert+`fs_css/bgForm.png" alt="sasasa"></img>
    return `
    
    <div class="page-header" style="padding: 20px; margin-top: 4%;">
        <div class="page-block">'
            <div class="row ml-2" id="pinfo">
                `+informasix()+`
            </div>`
            +_formData()
        +`</div>
    </div>`;
}

function _formData() {
    return `<div class="row m-2 shadow">`
                +_formIcon({
                    icon:'<i class="mdi mdi-file-check"></i>'
                    ,text:"<h3>Daftar Sub Kegiatan</h3>",
                    classJudul:' p-2',
                    id:"form1",
                    sizeCol:undefined,
                    bgHeader:"bg-info text-light",
                    attrHeader:`style="height: max-content;"`,
                    bgForm:"#fff; font-size:15px;",
                    isi:_inpComboBox({
                            judul:"Dinas",
                            id:"kdDinas",
                            color:"black",  
                            data:_.dinas,
                            bg:"bg-warning text-dark",
                            method:"sejajar",
                            attr:"font-size:15px;",
                            change:"_getDataOpd(this)",
                            index:true
                        })
                        +_inpComboBox({
                            judul:"Urusan Pemerintahan",
                            id:"kdUrusan",
                            color:"black",  
                            data:_.urusan,
                            bg:"bg-warning text-dark",
                            attrRow:"margin-top:5px;",
                            method:"sejajar",
                            attr:"font-size:15px;",
                            change:"_changeUrusan(this)"
                        })
                        +_inpComboBox({
                            judul:"Bidang Pemerintahan",
                            id:"kdBidang",
                            color:"black",  
                            attrRow:"margin-top:5px;",
                            data:_.bidang,
                            bg:"bg-warning text-dark",
                            method:"sejajar",
                            attr:"font-size:15px;",
                            change:"_changeBidang(this)"
                        })
                        +_lines({attr:'background:white;'})
                        +`<div id='tabelShow' style="margin: auto;">`
                            +setTabel()
                        +`</div>`,
                })
            +`</div>`;
}
function setTabel(){
    html=`
      <thead style="font-size: small;">
        <tr>            
            <th>No</th>
            <th>Program</th>
            <th>Kegiatan</th>
            <th>Sub Kegiatan</th>
            <th>Pra RENJA</th>
            <th>RENJA</th>
            <th>RENJA Final</th>
        </tr>
        </thead>
        <tfoot style="font-size: small;">
            <tr>          
                <th>No</th>
                <th>Program</th>
                <th>Kegiatan</th>
                <th>Sub Kegiatan</th>
                <th>Pra RENJA</th>
                <th>RENJA</th>
                <th>RENJA Final</th>
            </tr>
      </tfoot>
      <tbody style="font-size: small;">`;
        _.dinas[_.ind].data.forEach((v,i) => {
            fkondisi=false;
            if(_.kdUrusan=="all"){
                fkondisi=true;
            }else{
                fkondisi=false;
                if(_.kdUrusan==v.kdUrusan){
                    fkondisi=true;
                }
            }
            if(_.kdBidang=="all" && fkondisi){
                fkondisi=true;
            }else{
                fkondisi=false;
                if(_.kdBidang==v.kdBidang){
                    fkondisi=true;
                }
            }
            ftext=(Number(v.totalPRARKA)>1?_$(v.totalPRARKA):"buka");
            fbtnPra=[];
            fbtnPra.push({ 
                clsBtn:`btn-primary shadow btn-block fzMfc`
                ,func:"_goRincian('1',)"
                ,icon:`<i class="mdi mdi-file-lock  "></i>`+ftext
                ,title:"Buka Data "+_$(v.totalPRARKA)
            });
            if(!Number(v.qpra)){
                fbtnPra.push({ 
                    clsBtn:`btn-success shadow btn-block m-0 fzMfc`
                    ,func:"_goExport('2',)"
                    ,icon:`<i class="mdi mdi-file-lock  "></i>Export`
                    ,title:"Menambahkan ke RENJA"
                });
            }

            ftext=(Number(v.totalRKA)>1?_$(v.totalRKA):"buka");
            fbtnRka=[];
            fbtnRka.push({ 
                clsBtn:`btn-primary shadow btn-block fzMfc`
                ,func:"_goRincian('2',)"
                ,icon:`<i class="mdi mdi-file-lock  "></i>`+ftext
                ,title:"Buka Data "+_$(v.totalRKA)
            });
            if(!Number(v.qrka) && _kdJabatan>1){
                fbtnRka.push({ 
                    clsBtn:`btn-success shadow btn-block m-0 fzMfc`
                    ,func:"_goExport('3',)"
                    ,icon:`<i class="mdi mdi-file-lock  "></i>Export`
                    ,title:"Menambahkan ke RENJA FINAL"
                });
            }

            ftext=(Number(v.totalRKAFINAL)>1?_$(v.totalRKAFINAL):"buka");
            
            if(fkondisi){
                html+=`
                    <tr>
                        <td>`+(i+1)+`</td> 
                        
                        <td>
                            <b>`+v.kdProg+`</b><br>
                            `+v.nmProg+`
                        </td>
                        <td>
                            <b>`+v.kdKeg+`</b><br>
                            `+v.nmKeg+`
                        </td>
                        <td>
                            <b>`+v.kdSub+`</b><br>
                            `+v.nmSub+`
                        </td>
                        <td>`
                            +_btnGroup(fbtnPra,i)
                        +`</td>
                        <td>`
                            +_btnGroup(fbtnRka,i)
                            // <button class="btn btn-primary shadow btn-block" style="padding:5px;font-size:15px;" onclick="_goRincian('2',`+i+`)">simpan Perubahan</button>
                        +`</td>
                        <td>`
                            +_btnGroup([{ 
                                clsBtn:`btn-primary shadow btn-block fzMfc`
                                ,func:"_goRincian('3',)"
                                ,icon:`<i class="mdi mdi-file-lock  "></i>`+ftext
                                ,title:"Buka Data "+_$(v.totalRKAFINAL)
                            }],i)
                            // <button class="btn btn-primary shadow btn-block" style="padding:5px;font-size:15px;" onclick="_goRincian('3',`+i+`)">simpan Perubahan</button>
                        +`</td>`;
                html+=`</tr>`;   
            }
        });
    html+=`</tbody>`;
    infoSupport1=[];
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
            ,isi:html
        });;
}
function _getDataOpd(v) {
    find=Number(v.value);
    if(_.dinas[find].data==undefined ||_.dinas[find].data.length==0){
        _post('proses/getRenstraRealOpd',{kdDinas:_.dinas[find].value}).then(response=>{
            response=JSON.parse(response);
            if(response.exec){
                return _respon(response.data,find);
            }else{
                return _toast({bg:'e', msg:response.msg});
            }
        })
    }else{
        return _respon(null,find);
    }
}
function _respon(data,ind){
    _.ind=ind;
    if(data!=null){
        _.dinas[_.ind]=Object.assign(_.dinas[_.ind],data);
        // _.dinas[_.ind].data=data.data;
        // 
    }
    if(_.dinas[_.ind].data==undefined){
        _.dinas[_.ind].data=[];
    }
    $('#tabelShow').html(setTabel());
    _startTabel("dt");
    $('#pinfo').html(informasix());
}

function _changeUrusan(v) {
    _.kdUrusan=v.value;
    $('#kdBidang').html(_getCombo());
    _responTabel();
}
function _getCombo() {
    ftam=[];
    _.bidang.forEach((v)=> {
        fkondisi=true; // arti nya == all
        if(_.kdUrusan!="all"){
            fkondisi=false;
            if(v.kdUrusan==_.kdUrusan){
                fkondisi=true;
            }
        }
        if(fkondisi){
            ftam.push(v);
        }
    });
    _.kdBidang=ftam[0].value;
    $('#kdBidang').val(ftam[0].valueName);
    return _inpComboBox({
        data:ftam,
        bg:"bg-primary",
        getCombo:true
    });   
}
function _changeBidang(v) {
    _.kdBidang=v.value;
    _responTabel();
}
function _responTabel(){
    $('#tabelShow').html(setTabel());
    _startTabel("dt");
}

function _goRincian(tahapan,ind) {
    var data =btoa(JSON.stringify({
        kdSub:_.dinas[_.ind].data[ind].kdSub,
        kdDinas:_.dinas[_.ind].value,
        tahapan:tahapan
    }));
    if(_.tahun.split("-").length>1){
        return _redirectOpen("control/rincianBPerubahan/"+data);    
    }
    return _redirectOpen("control/rincianBelanja/"+data);
}
function _goExport(tahapan,ind) {
    ftform="Anda yakin ingin mengarsipkan tahapan ini ?";
    fbtn=_btn({
        color:"success shadow",
        judul:"export",
        attr:"style='float:right; padding:5px;font-size: medium;' onclick='_goExported("+tahapan+","+ind+")'",
        class:"btn btn-success"
    });
    fsize="500px";
    // if(_isNull(_.dinas[_.ind].data[ind].lokasiP)||_isNull(_.dinas[_.ind].data[ind].hasil)){
    if(true){
        
        ftform=_findikatorKinerja();
        fbtn=_btn({
            // color:"success shadow",
            judul:"Simpan",
            attr:"style='float:right; padding:5px;font-size: medium;' onclick='_saveGoExported("+tahapan+","+ind+")'",
            class:"btn btn-primary"
        });
        fsize="600px";
    }
    _modalEx1({
        judul:"Konfirmasi".toUpperCase(),
        icon:`<i class="mdi mdi-check"></i>`,
        cform:`text-light`,
        bg:"success",
        minWidth:fsize+"; font-size: medium;",
        isi:ftform,
        footer:_btn({
                    color:"primary shadow",
                    judul:"Close",
                    attr:`style='float:right; padding:5px;font-size: medium;' onclick="_modalHide('modal')"`,
                    class:"btn btn-secondary"
                })
                +fbtn
    });
    fdt=_.dinas[_.ind].data[ind];
    $('#lokasiP').val(fdt.lokasiP);
    $('#waktuP').val(fdt.waktuP);
    $('#kelompokS').val(fdt.kelompokS);
    $('#keluaran').val(fdt.keluaran);
    $('#hasil').val(fdt.hasil);
    $('#keluaranT').val(fdt.keluaranT);
    $('#hasilT').val(fdt.hasilT);
}
function _goExported(tahapan,ind) {
    param={
        kdSub:_.dinas[_.ind].data[ind].kdSub,
        kdDinas:_.dinas[_.ind].value,
        tahapan:Number(tahapan)
    }
    _post('proses/expBelanjaPersatu',param).then(res=>{
        res=JSON.parse(res);
        if(res.exec){
            // _modalHide('modal');
            _reload();
        }else{
            return _toast({bg:'e', msg:res.msg});
        }
    });
}
function _saveGoExported(tahapan,ind) {
    param={
        kdKeg:_.dinas[_.ind].data[ind].kdKeg,
        kdSub:_.dinas[_.ind].data[ind].kdSub,
        kdDinas:_.dinas[_.ind].value,
        tahapan:Number(tahapan),
        lokasiP:$('#lokasiP').val(),
        waktuP:$('#waktuP').val(),
        kelompokS:$('#kelompokS').val(),
        keluaran :$('#keluaran').val(),
        hasil:$('#hasil').val(),
        keluaranT :$('#keluaranT').val(),
        hasilT:$('#hasilT').val(),
        export:$('#export').prop('checked'),
    }
    // return console.log(param);
    if(_isNull(param.lokasiP))return _toast({bg:'e',msg:'Tambahkan lokasi pelaksanaan !!!'});
    if(_isNull(param.waktuP))return _toast({bg:'e',msg:'Tambahkan Waktu pelaksanaan !!!'});
    if(_isNull(param.kelompokS))return _toast({bg:'e',msg:'Tambahkan Kelompok Sasaran !!!'});
    if(_isNull(param.keluaran))return _toast({bg:'e',msg:'Tambahkan Keluaran pelaksanaan!!!'});
    if(_isNull(param.hasil))return _toast({bg:'e',msg:'Tambahkan Hasil pelaksanaan!!!'});
    if(_isNull(param.keluaranT))return _toast({bg:'e',msg:'Tambahkan Target Keluaran !!!'});
    if(_isNull(param.hasilT))return _toast({bg:'e',msg:'Tambahkan Target Hasil !!!'});

    _post('proses/saveExpBelanjaPersatu',param).then(res=>{
        res=JSON.parse(res);
        if(res.exec){
            // _modalHide('modal');
            _reload();
        }else{
            return _toast({bg:'e', msg:res.msg});
        }
    });
}