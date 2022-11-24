function _onload(data){
    // start 
    judul.nm=data.nm;
    judul.nama=data.nama;
    judul.Logo=data.logo;
    judul.copyright=data.copyright;


    
    _.slider=data.slider;
    _.tahun=data.tahun;
    _.dinas=data.dinas;

    _.sfKdDinas=null;
    _.sfUrl='https://bappedalitbangksb.com/';
    _.svKey=false; /// menandakan e master tanpa kode dinas

    viewWebsite=_container({
        container:true,
        center:true,
        size:"col-12",
        full:"-fluid",
        attr:" background-image:url('"+assert+"/fs_css/w5.jpg');",
        form:_formNoHeader({
            shadow:true,
            cls:"",
            id :"idContainer",
            style:`background-image:url('`+assert+`/fs_sistem/slider/`+_.slider[0].nama+`'); background-size: cover; `,
            kolom:[
                {
                    size:"6",form:`<div  class='container' style="height: 100%;padding: 0px;margin: 0px;"></div>`
                },{
                    form:_form2(),
                    style:"background: rgba(41, 0, 74, 0.3);"
                }
            ]
        })
        +_footer({
            id:'tester',
            attr:'background-color:dark',
            cls:'container-fluid bg-warning',
            nama:"Bappeda & Litbang Sumbawa Barat"
        })
    });

    $('#body').html(viewWebsite);
    $('#footer').html(data.footer);

    // $('#username').val('m0-dev');
    // $('#password').val('aaa');
    ind=0;
    kondisi=true;
    setInterval(function() {
        if(_.slider.length>1 && kondisi){
            ind++;
        }
        kondisi=true;
        $("#idContainer").css("background-image", "url('"+assert+"/fs_sistem/slider/"+_.slider[ind].nama+"')");
        if(ind==(_.slider.length-1)){
            ind=0;
            kondisi=false;
            
        }
    },5000)
}
function _form2(){
    hicon="80px";
   return `
    <div class="p-5" style="min-height:600px;">`
        // +_formNoHeader({
        //     shadow:false,
        //     style:`background:none;`,
        //     kolom:[
        //         {
        //             size:"2",form:`<img src="`+assert+`/fs_css/logo/logoKSB.png" style="height:120px;margin-top: 5px;">`
        //         },{
        //             size:"10",form:`
        //                 <h1 class="heading" style='font-family: Georgia, "Times New Roman", Times, serif; color:white;'>
        //                     <b>BAPPEDA</b>
        //                 </h1>
        //                 <h5 class="heading" style='font-family: Georgia, "Times New Roman", Times, serif; color:white;'>
        //                     KABUPATEN SUMBAWA BARAT
        //                 </h5>`
        //         }
        //     ]
        // })
        +`<h1 class="heading" style='font-family: Georgia, "Times New Roman", Times, serif; color:white; text-align:center;'>
            <b>" PARIRI LEMA BARIRI "</b>
        </h1>`
        +_lines({
            attr:'background:white; height:5px;'
        })
        +_sejajar({
            data:[{
                isi:`<div class="item shadow" style='background: rgba(255, 255, 255, 0.3);color:black;'>
                        <button class="btn-block" onclick="_login(1)">
                            <h4 style="text-align: center;" class="btn btn-primary btn-block">E-MUSRENBANG</h4>`
                            +_textCenter({text:`<img src="`+assert+`/fs_css/musrenbang1.svg" alt="" style="height: `+hicon+`;">`})
                            +`<h4 style="text-align: center;" class="btn btn-primary btn-block">Sistem Musyawaran Perencanaan Pembangunan</h4>
                        </button>    
                    </div>`
            },{
                isi:`<div class="item shadow" style='background: rgba(255, 255, 255, 0.3); color:black;'>
                        <button class="btn-block" onclick="_login(2)">
                            <h4 style="text-align: center;" class="btn btn-success btn-block">E-RENJA</h4>`
                            +_textCenter({text:`<img src="`+assert+`/fs_css/budgeting.svg" alt="" style="height: `+hicon+`;">`})
                            +`<h4 style="text-align: center;" class="btn btn-success btn-block">Elektronik Rencana Kerja</h4>
                        </button>
                    </div>`
            }]
        })
        +_sejajar({
            data:[{
                isi:`<div class="item shadow" style='background: rgba(255, 255, 255, 0.3);color:black; margin-top:10px'>
                        <button class="btn-block" onclick="_login(3)">
                            <h4 style="text-align: center;" class="btn btn-secondary btn-block">E-MASTER</h4>`
                            +_textCenter({text:`<img src="`+assert+`/fs_css/setting.png" alt="" style="height: `+hicon+`;">`})
                            +`<h4 style="text-align: center;" class="btn bg-white btn-block">Sistem Pendukung</h4>
                        </button>    
                    </div>`
            },{
                isi:``
            }]
        })
        +`
    </div>`;
    // +_textCenter({text:` <a class="small" href="forgot-password.html">Forgot Password?</a>`})+
    //     _textCenter({text:` <a class="small" href="register.html">Create an Account!</a>`})
}

function _login(key) {
    fjudul="E - MASTER";
    _.sfUrl+='master/control/dashboard/';
    _.svKey=false;
    _.sfKdDinas='-';
    switch (key) {
        case 1:
            fjudul="E - MUSRENBANG";
            _.sfUrl='emusrenbang/index.php/control/dashboard/';
            _.svKey=true;
        break;
        case 2:
            fjudul="E - RENJA";
            _.sfUrl='control/dashboard/';
            _.svKey=true;
        break;
    }
    _modalEx1({
        judul:"SISTEM "+fjudul.toUpperCase(),
        icon:`<i class="mdi mdi-note-plus"></i>`,
        cform:`text-light`,
        bg:"bg-light",
        minWidth:"500px; ;",
        isi:_flogin(_.svKey),
        footer:_btn({
                    color:"primary shadow",
                    judul:"Close",
                    attr:`style='float:right; padding:5px;;' onclick="_modalHide('modal')"`,
                    class:"btn btn-secondary"
                })
                +_btn({
                    color:"primary shadow",
                    judul:"Login",
                    attr:"style='float:right; padding:5px;;' onclick='_logined()'",
                    class:"btn btn-primary"
                })
    });
}
function _changeValDinas(v) {
    if(!$('#dinas').hasClass('show')){
        $('#dinas').addClass("show");
    }
    _multiDropdonwSearch({
        data:_.dinas,
        idData:"ddinas",
        id:"dinas",
        value:v.value,
        func:"_selectDinas",
        idDropdonw:"idInpDropDinas",
    })
}
function _selectDinas(idForDrop,id,value,valueName){
    _.sfKdDinas=value;
    $("#"+id).val(valueName.substring(0,50));
    return _showForDropSelect(idForDrop);
}
function _formSearchDinas(v){
    _multiDropdonwSearch({
        data:_.dinas,
        idData:"ddinas",
        id:"dinas",
        value:v.value,
        func:"_selectDinas",
        idDropdonw:"idInpDropDinas",
    })
}
function _logined() {
    param={
        username:$('#username').val(),
        password:$('#password').val(),
        kdDinas:_.sfKdDinas,
        tahun:$('#tahun').val(),
    }
    if(_.svKey){
        if(param.kdDinas=='-')return _toast({bg:'e',msg:'Pilih Dinas !!!'});
        if(_isNull(param.kdDinas))return _toast({bg:'e',msg:'Pilih Dinas !!!'});
    }
    if(_isNull(param.username))return _toast({bg:'e',msg:'Tambahkan username !!!'});
    if(_isNull(param.password))return _toast({bg:'e',msg:'Tambahkan password !!!'});
    
    _post('proses/checkUser',param).then(response=>{
        response=JSON.parse(response);
        if(response.exec){
            // _redirect("control/dashboard/"+btoa(JSON.stringify(param)));
            window.location.href = _.sfUrl+btoa(JSON.stringify(param));
        }else{
            return _toast({bg:'e', msg:response.msg});
        }
    });
}