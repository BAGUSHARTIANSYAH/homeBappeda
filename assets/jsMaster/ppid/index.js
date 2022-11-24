function _onload(data){
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
        },4)
        +_formNoHeader({
            shadow:true,
            cls:"",
            id :"idContainer",
            style:`background-image:url('`+assert+`/fs_sistem/slider/`+_.slider[0].nama+`'); background-size: cover; `,
            kolom:[
                {
                    // size:"6",form:`<div  class='container' style="height: 100%;padding: 0px;margin: 0px;"></div>`
                    size:"12",form:_form2()
                // },{
                //     form:_form2(),
                //     // style:"background: rgba(41, 0, 74, 0.3);"
                }
            ]
        })
        // +_footer({
        //     id:'tester',
        //     attr:'background-color:dark',
        //     cls:'container-fluid bg-warning',
        //     nama:"Bappeda & Litbang Sumbawa Barat"
        // })
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
    hicon="60px";
    fsize="80px";
   return `
    <div class="" style="min-height:600px; width:600px;margin: auto;padding: 30px;">`
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
        // +`<h2 class="heading" style='font-family: Georgia, "Times New Roman", Times, serif; color:white; text-align:center; background: rgba(13, 13, 13, 0.07)'>
        //     <b>TERWUJUDNYA KSB BAIK BERLANDASKAN GOTONG ROYONG</b>
        //     <br>`
        //     +_lines({
        //         attr:'background:white; height:5px;'
        //     })
        // +`</h2>
        +`<div class="menu" style="margin-top:400px; background: rgba(13, 13, 13, 0.07);padding:5px;">`
            +_sejajar({
                data:[{
                    isi:`<button class="btn-block justify-content-center" style='background: rgba(255, 255, 255, 0.3);color:black; border-radius: 50%;width: `+fsize+`;height:`+fsize+`;border-style: dashed;border-width: 3px;margin:auto;' 
                            onclick="_login(1)">`
                            +_textCenter({text:`<img src="`+assert+`/fs_css/musrenbang1.svg" alt="" style="height: `+hicon+`;">`})
                        +`</button>    
                        <button onclick="_login(1)" style="text-align: center;" class="btn btn-primary btn-block">E-MUSRENBANG</button>
                    `
                },{
                    isi:`<button class="btn-block justify-content-center" style='background: rgba(255, 255, 255, 0.3);color:black; border-radius: 50%;width: `+fsize+`;height: `+fsize+`;border-style: dashed;border-width: 3px;margin:auto;' 
                            onclick="_login(2)">`
                            +_textCenter({text:`<img src="`+assert+`/fs_css/budgeting.svg" alt="" style="height: `+hicon+`;">`})
                        +`</button>    
                        <button onclick="_login(2)" style="text-align: center;" class="btn btn-primary btn-block">E-RENJA</button>
                    `
                },{
                    isi:`<button class="btn-block justify-content-center" style='background: rgba(255, 255, 255, 0.3);color:black; border-radius: 50%;width: `+fsize+`;height: `+fsize+`;border-style: dashed;border-width: 3px;margin:auto;' 
                            onclick="_login(3)">`
                            +_textCenter({text:`<img src="`+assert+`/fs_css/setting.png" alt="" style="height: `+hicon+`;">`})
                        +`</button>    
                        <button onclick="_login(3)" style="text-align: center;" class="btn btn-primary btn-block">E-MASTER</button>
                    `
                }]
            })
        +`</div>
    </div>`;
    // +_textCenter({text:` <a class="small" href="forgot-password.html">Forgot Password?</a>`})+
    //     _textCenter({text:` <a class="small" href="register.html">Create an Account!</a>`})
}