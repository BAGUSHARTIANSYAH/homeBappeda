function _onload(data){
    // start 
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
        },6)
        +_formNoHeader({
            shadow:true,
            cls:"",
            id :"idContainer",
            style:`background-image:url('`+assert+`/fs_sistem/slider/3.png'); background-size: cover; `,
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
}
function _form2(){
    hicon="200px";
    fsize="180px";

    infoSupport=[];
    infoSupport.push({name:"Alamat",value:'Jln. Bung Karno No. 5 Kompleks Kamutar Telu Kec. Taliwang - Kode Pos. 84455',icon:'<span class="mdi mdi-google-maps" style="font-size: 40px;color: chocolate;"></span>'});
    infoSupport.push({name:"No Telpon",value:'(0372) 8281424 - 8283219',icon:'<span class="mdi mdi-card-account-phone" style="font-size: 40px;color: cornflowerblue;"></span>'});
    infoSupport.push({name:"Gmail",value:'bappedalitbangksb@gmail.com',icon:'<span class="mdi mdi-gmail" style="font-size: 40px;color: yellowgreen;"></span>'});

    infoSupport1=[];
    infoSupport1.push({name:"Facebook",value:'bappedaKsb',icon:'<span class="mdi mdi-facebook" style="font-size: 40px;color: blue;"></span>'});
    infoSupport1.push({name:"Instagram",value:'bappedaKsb',icon:'<span class="mdi mdi-instagram" style="font-size: 40px;color: cornflowerblue;"></span>'});
    // infoSupport1.push({name:"Telegram",value:'bappedaKsb@gmail.com',icon:'<span class="mdi mdi-cellphone-basic" style="font-size: 40px;color: blue;"></span>'});
    infoSupport1.push({name:"Twitter",value:'bappedaKsb',icon:'<span class="mdi mdi-twitter" style="font-size: 40px;color: blue;"></span>'});
    infoSupport1.push({name:"Website",value:'https://bappedaKsb.com',icon:'<span class="mdi mdi-search-web" style="font-size: 40px;color: yellowgreen;"></span>'});

    return `
    <div class="" style="min-height:600px; margin: auto;padding: 30px;  background: rgba(255, 255, 255, 0.50);">`
        +`<div class="menu" style="margin-top:100px;color:black;padding:5px;">`
            +_sejajar({
                data:[{
                    isi:`<div class="btn-block justify-content-center" 
                            style='background:white;border-radius: 20%;width:400px;margin:auto;'>`
                            +_textCenter({text:`<img src="`+assert+`/fs_css/logo/bupatiWakil22.jpg" alt="" style="height: `+hicon+`;">`})
                        +`</div>`
                        +_textCenter({text:`<h5 >
                                PEMERINTAHAN KABUPATEN <br> SUMBAWA BARAT
                            </h5>`
                        })
                },{
                    isi:_tbl2ColIcon(infoSupport,'',false)
                },{
                    isi:`<div class="btn-block justify-content-center" 
                            style='background:white;color:black; border-radius: 50%;width:200px;margin:auto;'>`
                            +_textCenter({text:`<img src="`+assert+`/fs_css/logo/dev-blank.png" alt="" style="height: `+hicon+`;">`})
                        +`</div>`
                        +_textCenter({text:`<h5>
                                KEPALA BADAN PERENCANAAN<br> DAN PEMBANGUNAN DAERAH
                            </h5>`
                        })
                }]
            })
            +_tbl2ColIcon(infoSupport1,'',true)
        +`</div>
    </div>`;
    // +_textCenter({text:` <a class="small" href="forgot-password.html">Forgot Password?</a>`})+
    //     _textCenter({text:` <a class="small" href="register.html">Create an Account!</a>`})
}