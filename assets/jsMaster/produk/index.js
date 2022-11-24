function _onload(data){
    // start 
    judul.nm=data.nm;
    judul.nama=data.nama;
    judul.Logo=data.logo;
    judul.copyright=data.copyright;


    
    _.produk=data.produk;
    // _.tahun=data.tahun;
    // _.dinas=data.dinas;

    // _.sfKdDinas=null;
    // _.sfUrl='https://bappedalitbangksb.com/';
    // _.svKey=false; /// menandakan e master tanpa kode dinas

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
        },3)
        +_lines({})
        +_formNoHeader({
            shadow:false,
            cls:"",
            id :"idContainer",
            style:`overflow-y: auto; padding:30px !important; padding-top:0px !important;  background-color:#b9b4b45e; margin-bottom:5px;`,
            kolom:[
                {
                    size:"12",
                    style:'padding: 10px;padding-top: 20px;',
                    form:_sejajar({
                            attrCol:'style="margin: 0px !important;"',
                            data:[{
                                isi:_inpGroupPrepend({
                                    icon:'</i><i class="mdi mdi-folder-upload"></i>',
                                    bg:'bg-dark text-light',
                                    placeholder:"",
                                    attrSpan:`style="font-size: medium;"`,
                                    isi:_inp({
                                            hint:"Search... Enter...",
                                            checked:undefined,
                                            attr:'onchange="_searchDoc(this)"',
                                            id:'search',
                                            type:'text',
                                            cls:undefined,
                                        })
                                })
                            },{
                                isi:_btn({
                                    color:"primary",
                                    judul:`<i class="mdi mdi-book-search"></i>Search`,
                                    attr:"style='float:left;' onclick='_searchDoc(0)'",
                                    class:"btn btn-primary "
                                })
                            }]
                        })
                        +`<div id='preview' style="margin: auto;">`
                            +_galeryx2({
                                row:3,
                                url:_urlMaster,
                                data:_.produk
                                // [
                                //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld',img:'/fs_css/w5.jpg'},
                                //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld',img:'/fs_sistem/slider/lap.PNG'},
                                //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld',img:'/fs_sistem/slider/lap.PNG'},
                                //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld',img:'/fs_sistem/slider/lap.PNG'},
                                //     {judul:'Badan Perencanaan Pembangunan Daerah',keterangan:'Penelitian dan Pengabdian (Bappeda dan Litbang) Kabupaten Sumbawa Barat (KSB), melaksanakan lomba penelitan tahun 2021',url:'lokeld',img:'/fs_sistem/slider/lap.PNG'}
                                // ],
                            })
                        +`</div>`
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
function _searchDoc(v) {
    fjudul='';
    if(v!=0){
        fjudul=v.value;
    }else{
        fjudul=$('#search').val();
    }
    fdt=[];
    if(fjudul.length>0){
        _.produk.forEach((v)=> {
            if(_search(v.judul,fjudul)){
                fdt.push(v);
            }
        });
    }else{
        fdt=_.produk;
    }
    $('#preview').html(
        _galeryx2({
            row:3,
            url:_urlMaster,
            data:fdt
        })
    )
}
