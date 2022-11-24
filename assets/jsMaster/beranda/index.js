function _onload(data){
    $('#body').html(data.tmBody);
    // myCode=data.code;

    // start 
    judul.nm=data.nm;
    judul.nama=data.nama;
    judul.Logo=data.logo;
    judul.copyright=data.copyright;


    _.slider=data.slider;
    // _.tahun=data.tahun;
    _.dinas=data.dinas;

    _.sfKdDinas=null;
    _.svKey=false; /// menandakan e master tanpa kode dinas
    $('#menuAPP').html(_form2());
    // $('#bodyTM').html(_form2());
    $('#footer').html(data.footer+data.tmFooter);

    // // $('#username').val('m0-dev');
    // // $('#password').val('aaa');
    $("#slider").css("background-image", "url('"+assert+"/fs_sistem/slider/"+_.slider[0].nama+"')");
    ind=0;
    kondisi=true;
    // setInterval(function() {
    //     if(_.slider.length>1 && kondisi){
    //         ind++;
    //     }
    //     kondisi=true;
    //     $("#slider").css("background-image", "url('"+assert+"/fs_sistem/slider/"+_.slider[ind].nama+"')");
    //     if(ind==(_.slider.length-1)){
    //         ind=0;
    //         kondisi=false;
            
    //     }
    // },10000)
    $('p').addClass("d-lg-none d-xl-none");
    // $('p').click(function(e){
    //     $("#japp1").removeClass();
    // })
}
function _form2(){
    hicon="40px";
    fsize="60px";
    fsizeW="160px";
   return `
    <div class="" style="min-height:600px; margin: auto;padding: 0px;float: right; margin-top:150px;">`
        +`<div class="navbar bg-dark"  style="padding:10px;">`
            +`<ul class="navbar-nav">
                <li class="nav-item">
                    <button class="nav-link text-white" onclick="_login(1)" rel="tooltip" title="" data-original-title="E-MUSRENBANG"
                        style="background: none;border-style: none;">
                        <i class="mdi mdi-dots-circle mdi-spin text-success" style="font-size:25px;"></i>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" onclick="_login(2)" rel="tooltip" title="" data-original-title="E-RENJA"
                        style="background: none;border-style: none;">
                        <i class="mdi mdi-book-open-page-variant text-primary" style="font-size:25px;"></i>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" onclick="_login(3)" rel="tooltip" title="" data-original-title="DATAKU"
                        style="background: none;border-style: none;">
                        <i class="mdi mdi-atom mdi-spin text-light" style="font-size:25px;"></i>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" onclick="_login(4)" rel="tooltip" title="" data-original-title="SIPD"
                        style="background: none;border-style: none;">
                        <i class="mdi mdi-google-drive text-danger" style="font-size:25px;"></i>
                    </button>
                </li>
            </ul>
            `
        +`</div>
    </div>`;
    // +_textCenter({text:` <a class="small" href="forgot-password.html">Forgot Password?</a>`})+
    //     _textCenter({text:` <a class="small" href="register.html">Create an Account!</a>`})
}

function _login(key) {
    _.sfUrl=router;
    fjudul="DATA KU";
    _.sfUrl+='dataku/control/dashboard/';
    _.svKey=true;
    _.sfKdDinas='-';
    switch (key) {
        case 1:
            // fjudul="E - MUSRENBANG";
            // _.sfUrl='emusrenbang/index.php/control/dashboard/';
            // _.svKey=true;
            return _redirectOpen("musrenbang");
        break;
        case 2:
            fjudul="E - RENJA";
            _.sfUrl='control/dashboard/';
            _.svKey=true;
        break;
        case 3:
            return _redirectOpen("dataku");
        break;
        case 4:
            return window.open("https://sumbawabaratkab.sipd.kemendagri.go.id/daerah?GqRjd5Q57KV1GhcAmGDFoMfL9PJSb16/gk6wdceyfIizsLDd9dM0KPn3CcCzOxL7T1e2ltEcdNdjZ8zKuYIvtGIzEjGl6nY6FSDrVeTQe@sA5@7c8DevqVA3xnqBNkXY", '_blank');
        break;
        case 5:
            fjudul="E-MASTER";
            _.sfUrl='master/control/dashboard/';
            _.svKey=false;
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
function _logined(){     
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