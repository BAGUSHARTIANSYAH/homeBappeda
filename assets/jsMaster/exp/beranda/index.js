function _onload(data){
    // start 
    judul.nm=data.nm;
    judul.nama=data.nama;
    judul.Logo=data.logo;
    judul.copyright=data.copyright;


    _.slider=data.slider;
    try {
        
    } catch (error) {
        
    }
    slider.push({
        attr:"right: 1%;bottom: 0px;left: 1%;position: relative;",
        url:assert+"fs_sistem/slider/"+_.slider[0].img,
        color:" background-size: cover;margin-bottom:0px;height:80%",
        id:"slider",
        // hover:"BAGUS H"
    });

    _.slider.forEach((v,i) => {
        if(i>0){
            slider.push({url:assert+"fs_sistem/slider/"+v.img});
        }
    });

    viewWebsite=_container({
        container:true,
        center:true,
        size:"col-10 pt-2 pb-2",
        full:"-fluid",
        attr:"background: cadetblue;",
        form:_formNoHeader({
            shadow:true,
            kolom:[
                {
                    size:"6",form:_form1()
                },{
                    form:_form2()
                }
            ]
        })
    });

    $('#body').html(viewWebsite);
    $('#footer').html(data.footer);

    $('#username').val('m0-dev');
    $('#password').val('aaa')
}
function _form1(){
    return `<div class='container d-none d-lg-block bg-login-image' style="background-color: darkcyan;height: 100%;padding: 0px;margin: 0px;">
    `+_slider()+`
    </div>`;
    // return _slider();
}
function _form2(){
   return `
    <div class="p-5">
        `+_textCenter({text:`<img src="`+assert+'fs_css/logo/'+judul.Logo+`" style="height:120px;"> `})+
        _textCenter({
            text:_textH({
                heading:"1",
                class:undefined,
                text:judul.nm+"<br> <small style='font-size: 25px;'>"+judul.nama+"</small>"
            })
        })+`
        <div class="user">
            `+__formGroup({
                hint:"Username",
                attr:"",
                class:"form-control-user",
                type:"text",
                id:"username"
            })+__formGroup({
                hint:"Password",
                attr:"",
                class:"form-control-user",
                type:"password",
                id:"password"
            })+__formGroup({
                isi:_checkbok({
                    id:"checklist",
                    text:"Ingatkan"
                })
            })+_btn({
                color:"primary",
                class:"btn btn-primary btn-user btn-block",
                attr:"onclick='_login()'",
                judul:"Login"
            })+`
        </div>
        `+lines({color:"white"})
        +`
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>`+judul.copyright+`</span><br><br>
                <a href="#" class="btn btn-primary btn-circle">
                    <i class="mdi mdi-facebook"></i>
                </a>
                <a href="#" class="btn btn-danger btn-circle">
                    <i class="mdi mdi-instagram"></i>
                </a>
                <a href="#" class="btn btn-warning btn-circle">
                    <i class="mdi mdi-telegram"></i>
                </a>
            </div>
        </div>
    </div>
    
    `;
    // +_textCenter({text:` <a class="small" href="forgot-password.html">Forgot Password?</a>`})+
    //     _textCenter({text:` <a class="small" href="register.html">Create an Account!</a>`})
}

function _login() {
    param={
        username:$('#username').val(),
        password:$('#password').val()
    }
    if(_isNull(param.username))return _toast({bg:'e',msg:'Tambahkan username !!!'});
    if(_isNull(param.password))return _toast({bg:'e',msg:'Tambahkan password !!!'});
    _post('proses/checkUser',param).then(response=>{
        response=JSON.parse(response);
        if(response.exec){
            _redirect("control/dashboard/"+btoa(JSON.stringify(param)));
        }else{
            return _toast({bg:'e', msg:response.msg});
        }
    });
}