function _onload(data){
    _.data=data.agenda;
    // _.agenda=data.agenda;

    infoSupport=[];
    infoSupport.push({
        name:"Hari / Tanggal",
        value:_.data.hari+" / "+_.data.tgl,
        icon:'<span class="mdi mdi-calendar" style="font-size: 50px;color: chocolate;"></span>'
    });
    infoSupport.push({
        name:"Waktu",
        value:_.data.waktu,
        icon:'<span class="mdi mdi-calendar-clock" style="font-size: 50px;color: cornflowerblue;"></span>'
    });
    infoSupport.push({
        name:"Tempat",
        value:_.data.tempat,
        icon:'<span class="mdi mdi-home-clock-outline" style="font-size: 50px;color: yellowgreen;"></span>'
    });
    // infoSupport.push({name:"",value:'Lomba Penelitian'});

    slider=[];
    // slider.push({url:assert+"fs_css/1.jpg",color:"background-image:url("+assert+"bgSupportCss/1.jpg);background-size: cover;margin-bottom:0px;margin-top: 5%;",id:"Iklan"});
    // slider.push({url:assert+"fs_css/2.jpg"});
    // _slider()
    
    if(_.data.posterF.length>0){
        if(_.data.img.length>0){
            slider.push({url:_urlMaster+`assets/fs_sistem/upload/image/agenda/`+_.data.img,color:"background-image:url("+assert+"bgSupportCss/1.jpg);background-size: cover;margin-bottom:0px;margin-top: 5%;",id:"Iklan"});
        }
        split=_.data.posterF.split("/");
        split.forEach((v1) => {
            if(slider.length==0){
                slider.push({url:_urlMaster+`assets/fs_sistem/upload/image/agenda/`+v1,color:"background-image:url("+assert+"bgSupportCss/1.jpg);background-size: cover;margin-bottom:0px;margin-top: 5%;",id:"Iklan"});
            }else{
                slider.push({url:_urlMaster+`assets/fs_sistem/upload/image/agenda/`+v1});
            }
        });
    }else{
        if(_.data.img.length>0){
            slider.push({url:_urlMaster+`assets/fs_sistem/upload/image/agenda/`+_.data.img,color:"background-image:url("+assert+"bgSupportCss/1.jpg);background-size: cover;margin-bottom:0px;margin-top: 5%;",id:"Iklan"});
        }else{
            slider.push({url:assert+'fs_css/bgx.png',color:"background-image:url("+assert+"bgSupportCss/1.jpg);background-size: cover;margin-bottom:0px;margin-top: 5%;",id:"Iklan"});
        }
        
    }
    
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
        },2)
        +_formNoHeader({
            shadow:true,
            cls:"",
            id :"idContainer",
            style:`overflow-y: auto; padding:30px !important;`,
            kolom:[
                {
                    size:"6",form:_tbl2ColIcon(infoSupport,'',true)
                                    +`<div  class='container' style="height: 500px;padding: 0px;margin:0px;background: rgba(0, 0, 0, 0.3);" >`
                                    +_slider()
                                    // +`<img src="`+(_.data.img.length>0?_urlMaster+`assets/fs_sistem/upload/image/`+_.data.img:assert+'fs_css/bgx.png')+`" alt="" width="100%">`
                                    +`</div>`
                    // size:"12",form:_form2()
                },{
                    size:"6",form:_form2()
                    // style:"background: rgba(41, 0, 74, 0.3);"
                },
                // ,{
                //     size:"2",form:_tbl2ColIcon(infoSupport)
                //     // style:"background: rgba(41, 0, 74, 0.3);"
                // }
            ]
        })
        // +_formNoHeader({
        //     shadow:false,
        //     cls:"",
        //     id :"idContainer",
        //     style:`overflow-y: auto; padding:30px !important;`,
        //     kolom:[
        //         {
        //             // size:"",
        //             form:_galeryx1({
        //                 row:3,
        //                 url:router+'control/',
        //                 assets:_urlMaster,
        //                 data:_.agenda
        //                 // [
        //                 //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld'},
        //                 //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld'},
        //                 //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld'},
        //                 //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld'},
        //                 //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld'}
        //                 // ],
        //             })
        //         }
        //     ]
        // })
        // +_lines({attr:'background:white;'})
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
    hicon="60px";
    fsize="80px";
   return `
            <div  style="max-height: 500px;">
                <div style="text-align:center;">
                    <h3>`+_.data.judul+`</h3>
                    `+_lines({ attr:'background:white;'})+`
                </div>

                <div class="entry-content notopmargin post-gambar text-justify">
                    `+(_.data.keteranganE.length>0?_.data.keteranganE:_.data.keteranganS)+`
                </div><!-- Entry Content
                ============================================= -->
                


                </div>
   `;
    // +_textCenter({text:` <a class="small" href="forgot-password.html">Forgot Password?</a>`})+
    //     _textCenter({text:` <a class="small" href="register.html">Create an Account!</a>`})
}