function _onload(data){
    // start 
    judul.nm=data.nm;
    judul.nama=data.nama;
    judul.Logo=data.logo;
    judul.copyright=data.copyright;

    _.sfKdDinas=null;
    _.sfUrl='https://bappedalitbangksb.com/';
    _.svKey=false; /// menandakan e master tanpa kode dinas

    viewWebsite=_container({
        container:true,
        center:true,
        size:"col-12",
        full:"-fluid",
        // attr:" background-image:url('"+assert+"/fs_css/w5.jpg');",
        attr:" background-color:#007480;",
        form:_headerLogin({
            logo:"fs_css/logo/dev-mini.png",
            nama:"3. contoh header page umum ",
            clsNama:"text-success"
        },5)
        +_formNoHeader({
            shadow:true,
            cls:"",
            id :"idContainer",
            style:`background-image:url('`+assert+`fs_css/profil.png'); background-size: cover; height:1000px`,
            kolom:[
                {
                    // size:"6",form:`<div  class='container' style="height: 100%;padding: 0px;margin: 0px;"></div>`
                    size:"12",form:''
                // },{
                //     form:_form2(),
                //     // style:"background: rgba(41, 0, 74, 0.3);"
                }
            ]
        })
    });

    $('#body').html(viewWebsite);
    $('#footer').html(data.footer);

     
}
function _form2(){
    hicon="60px";
    fsize="80px";
   return `
    <div class="" style="min-height:1200px; width:600px;margin: auto;padding: 30px;">
    </div>`;
    // +_textCenter({text:` <a class="small" href="forgot-password.html">Forgot Password?</a>`})+
    //     _textCenter({text:` <a class="small" href="register.html">Create an Account!</a>`})
}