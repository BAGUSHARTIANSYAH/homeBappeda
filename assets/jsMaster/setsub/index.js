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
    
    $('#bodyTM').html(_form());
    $('#footer').html(data.tmFooter+data.footer);
    
    _startTabel("dt");
}
function _form() {
    // <img class="img-fluid d-block mx-auto" src="`+assert+`fs_css/bgForm.png" alt="sasasa"></img>
    return `
    
    <div class="page-header" style="padding: 20px; margin-top: 4%;">
        <div class="page-block">
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
      <thead>
        <tr>            
            <th>No</th>
            <th>Program</th>
            <th>Kegiatan</th>
            <th>Sub Kegiatan</th>
            <th>Terpilih</th>
        </tr>
        </thead>
        <tfoot>
          <tr>          
            <th>No</th>
            <th>Program</th>
            <th>Kegiatan</th>
            <th>Sub Kegiatan</th>
            <th>Terpilih</th>
        </tr>
      </tfoot>
      <tbody>`;
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
                    <td>
                        `+_inp({type:"checkbox" ,attr:" onchange='_updCheck(this,"+i+")'",checked:_trueChecked(1,Number(v['checked']))})+`
                    </td>
                </tr>`;
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
        _post('proses/getRenstraOpd',{kdDinas:_.dinas[find].value}).then(response=>{
            response=JSON.parse(response);
            if(response.exec){
                return _responData(response.data,find);
            }else{
                return _toast({bg:'e', msg:response.msg});
            }
        })
    }else{
        return _responData(null,find);
    }
}
function _responData(data,ind){
    _.ind=ind;
    if(_.dinas[_.ind].data==undefined){
        _.dinas[_.ind].data=[];
    }
    _respon(data)
}
function _respon(data){
    if(data!=null){
        _.dinas[_.ind]=Object.assign(_.dinas[_.ind],data);
        // _.dinas[_.ind].data=data.data;
        // _.dinas[_.ind].tsub=data.tsub;
        // _.dinas[_.ind].tsubProses=data.tsubProses;
        // _.dinas[_.ind].tpaguPra=data.tpaguPra;
        // _.dinas[_.ind].tpaguRka=data.tpaguRka;
        // _.dinas[_.ind].tpaguFinal=data.tpaguFinal;
    }
    $('#tabelShow').html(setTabel());
    _startTabel("dt");
    $('#pinfo').html(informasix());
}

function _updCheck(v,ind) {
    if(!Number(_.dinas[_.ind].data[ind].upd)){//false
        _.dinas[_.ind].data[ind].upd=true;
    }else{
        _.dinas[_.ind].data[ind].upd=false;
    }
    _.dinas[_.ind].data[ind].checked=Number(v.checked);
}
function savedOPD() {
    fdt=[];
    _.dinas[_.ind].data.forEach((v) => {
        console.log(v.upd);
        if(Number(v.upd)){
            fdt.push({
                kdSub:v.kdSub,
                kdKeg:v.kdKeg,
                nmSub:v.nmSub,
                act:v.checked
            })
        }
    });
    // return console.log(fdt);
    param={
        kdDinas:_.dinas[_.ind].value,
        data:fdt
    }
    _post('proses/saveRenstraOpd',param).then(res=>{
        res=JSON.parse(res);
        if(res.exec){
            _respon(res.data);
            // _redirect("control/dashboard/"+btoa(JSON.stringify(param)));
            // window.location.href = _.sfUrl+btoa(JSON.stringify(param));
        }else{
            return _toast({bg:'e', msg:res.msg});
        }
    });
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