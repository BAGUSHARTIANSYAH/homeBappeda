<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function _tm1Head(){
        $CI =& get_instance();
        $furl=$CI->mbgs->_getAssetUrl()."thema/nuikct/";
        return '
            <link href="'.$furl.'assets/css/now-ui-kit2.css?v=1.3.0" rel="stylesheet" />
            <link href="'.$furl.'assets/demo/demo.css" rel="stylesheet" />
        ';
    }
    function _tm1Footer(){
        $CI =& get_instance();
        $furl=$CI->mbgs->_getAssetUrl()."thema/nuikct/";
        return '
                <script src="'.$furl.'assets/js/core/jquery.min.js"></script>
                <script src="'.$furl.'assets/js/core/bootstrap.min.js" type="text/javascript"></script>
                <script src="'.$furl.'assets/js/plugins/bootstrap-switch.js"></script>
                

                <script src="'.$furl.'assets/js/plugins/nouislider.min.js" type="text/javascript"></script>

                <script src="'.$furl.'assets/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
                <script src="'.$furl.'assets/js/now-ui-kit.js?v=1.3.0" type="text/javascript"></script>
        ';
    }
    function _tm1Loader(){
        return '';
    }
    function _tm1Setting($p){
        $CI =& get_instance();
        // hg height
        // bg background,
        // wd width
        // nm nama 
        // pg page 
        // ic icon 
        // ac active
        // ha hak akses 
        // kdJab kd jabatan 
        $_=array();
        $router=base_url();
        $fs_css=$router."assets/fs_css/";
        $fs_sistem=$router."assets/fs_sistem/";

        $user=[1,2,3,4,5];
        $admin=[2,3,4,5];
        $monitor=[3,4,5];
        $super=[3,4,5];
        $dev=[5];

        $_['kdJab']=$p['kdJab'];
        $_['nav']=[
            "bg"=>"style='background-color:none;'",
            "logo"=>$fs_css."logo/3W.png",
            "logo1"=>$fs_css."/bupatiWakil.png",
            "bgLogo"=>$fs_css."/kantorBupati.jpg",
            // "dataImage"=>"",
            "dataImage"=>$fs_sistem."/slider/tano.jpg",
            "dataImage1"=>$fs_css."/batik.png",
            "hgLogo2"=>"150px",
            "wdLogo"=>"350px;",
            "nm"=>$p['nm'],
            "nama"=>$p['nama'],
            "user"=>$p['user'],
            "urlUser"=>$router.'control/tes',
            "bgNama"=>"text-light",
            "pgStart"=>$p['pgStart'],
            "pgEnd"=>$p['pgEnd'],
            "form"=>"",
            "moto"=>"Tata Administrasi Bangun Jalan Pikiran"
        ];

        $_['menu']=array();
        
        // menu 1
        $menuSupport=array();
        array_push($menuSupport,[
            "nm"=>"BERANDA",
            "ic"=>'<i class="mdi mdi-home menu-icon"></i>',
            "url"=>"control",
            "ac"=>"",
            "ha"=>$user,
            "subMenu"=>[]

        ]);
        array_push($_['menu'],[
            "nm"=>null,
            "menu"=>$menuSupport
        ]);

        $menuSupport=array();
        array_push($menuSupport,[
            "nm"=>"AGENDA",
            "ic"=>'<i class="mdi mdi-home menu-icon"></i>',
            "url"=>"control/agenda",
            "ac"=>"",
            "ha"=>$user,
            "subMenu"=>[]

        ]);
        array_push($_['menu'],[
            "nm"=>null,
            "menu"=>$menuSupport
        ]);


        $menuSupport=array();
        array_push($menuSupport,[
            "nm"=>"PRODUK",
            "ic"=>'<i class="mdi mdi-home menu-icon"></i>',
            "url"=>"control/produk",
            "ac"=>"",
            "ha"=>$user,
            "subMenu"=>[]

        ]);
        array_push($_['menu'],[
            "nm"=>null,
            "menu"=>$menuSupport
        ]);

        $menuSupport=array();
        array_push($menuSupport,[
            "nm"=>"PPID",
            "ic"=>'<i class="mdi mdi-home menu-icon"></i>',
            "url"=>"control/ppid",
            "ac"=>"",
            "ha"=>$user,
            "subMenu"=>[]

        ]);
        array_push($_['menu'],[
            "nm"=>null,
            "menu"=>$menuSupport
        ]);

        $menuSupport=array();
        array_push($menuSupport,[
            "nm"=>"PROFIL",
            "ic"=>'<i class="mdi mdi-home menu-icon"></i>',
            "url"=>"control/profil",
            "ac"=>"",
            "ha"=>$user,
            "subMenu"=>[]

        ]);
        array_push($_['menu'],[
            "nm"=>null,
            "menu"=>$menuSupport
        ]);
        
        $menuSupport=array();
        array_push($menuSupport,[
            "nm"=>"KONTAK",
            "ic"=>'<i class="mdi mdi-home menu-icon"></i>',
            "url"=>"control/kontak",
            "ac"=>"",
            "ha"=>$user,
            "subMenu"=>[]

        ]);
        array_push($_['menu'],[
            "nm"=>null,
            "menu"=>$menuSupport
        ]);
        
        
        // menu 2
        // $menuSupport=array();
        // array_push($menuSupport,[
        //     "nm"=>"Basis Data",
        //     "ic"=>'<i class="mdi mdi-note-plus menu-icon"></i>',
        //     "url"=>"#",
        //     "ac"=>"",
        //     "ha"=>$user,
        //     "subMenu"=>[
        //         [
        //             "url"=>$router."control/dinas",
        //             "menu"=>"Dinas",
        //             "status"=>"active",
        //             "ha"=>$user,
        //             "status"=>""
        //         ],
        //         [
        //             "url"=>$router."control/akun",
        //             "menu"=>"Akun",
        //             "ha"=>$user,
        //             "status"=>"active",
        //             "status"=>""
        //         ],
        //     ]

        // ]);
        // array_push($_['menu'],[
        //     "nm"=>null,
        //     "menu"=>$menuSupport
        // ]);
            

        // header
        $_['header']=[
            "nama"=>"",
            "ic"=>$fs_css."/boy.png",
            "style"=>"background-color: #3a4f5573;",
            "style1"=>'style="background-image:url('.$fs_css."bgForm.png".'); height:'.$_['nav']['hgLogo2'].';"',
            "contentDropList"=>[
                [
                    "url"=>"",
                    "onclick"=>"",
                    "ic"=>'<i class="mdi mdi-face"></i>',
                    "nm"=>"Profile"
                ],
                [
                    "url"=>"",
                    "onclick"=>"",
                    "ic"=>'<i class="mdi mdi-settings"></i>',
                    "nm"=>"Settings"
                ],
                [
                    "url"=>"",
                    "onclick"=>"",
                    "ic"=>'<i class="mdi mdi-exit-to-app"></i>',
                    "nm"=>"Logout"
                ]
            ]
        ];

        // notif
        $_['notif']=[
            [
                "url"=>"",
                "bg"=>"",
                "icon"=>'<i class="fa fa-bolt"></i>',
                "src"=>$fs_css."informasi.png",
                "textSmall"=>"Nama Sistem",
                "text"=>"SI NOTARIS"
            ],
            [
                "url"=>"",
                "bg"=>"",
                "icon"=>'<i class="fa fa-bolt"></i>',
                "src"=>$fs_css."informasi.png",
                "textSmall"=>"Mei 25, 2021",
                "text"=>"Dikembangkan oleh : Tim IT MFC"
            ],
            [
                "url"=>"",
                "bg"=>"",
                "icon"=>'<i class="fa fa-bolt"></i>',
                "src"=>$fs_css."informasi.png",
                "textSmall"=>"Mei 25, 2021",
                "text"=>"Sistem di Luncurkan"
            ],
        ];
        $_['notifT']=true;
        return $_;
    }
    function _tm1Header($st){
        // $st=_tmSetting($p);
        
        $fdata="";
        foreach ($st['menu'] as $key => $v) {
            $faktifMenu=$v['menu'][0]['ac'];
            
            $ftamSubMenu="";
            $menu=$v['menu'][0];
            foreach ($menu['ha'] as $key1 => $vx) {
                if($st['kdJab']==$vx){
                    if(count($menu['subMenu'])>0){
                        $fdata.='
                            <li class="nav-item dropdown '.$faktifMenu.'">
                                <a href="#" class="nav-link dropdown-toggle" id="bgs'.$key1.'" data-toggle="dropdown">
                                    <i class="now-ui-icons design_app"></i>
                                <p>Components</p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bgs'.$key1.'">';
                                    foreach ($menu['subMenu'] as $key2 => $vm) {
                                        $fdata.='
                                            <a class="dropdown-item '.$vm['status'].'" href="'.$vm['url'].'">
                                                '.$vm['menu'].'
                                            </a>';
                                    }
                            $fdata.='</div>
                            </li>';
                    }else{
                        $fdata.='
                            <li class="nav-item '.$faktifMenu.'" href="javascript:void(0)" onclick="scrollToDownload()">
                                <a class="nav-link " href="'.$menu['url'].'">
                                    <span class="menu-title">'.$menu['nm'].'</span>
                                </a>
                            </li>
                        ';
                    }
                }
            }
        }
        // return print_r($fdata);
        // return print_r($fdata);
        // <p style="font-size:25px;"><b>'.$st['nav']['nm'].'</b></p>

        // <a class="navbar-brand" href="#" rel="tooltip" data-placement="bottom" >
        //                 <img src="'.$st['nav']['logo'].'" style="width:'.$st['nav']['wdLogo'].'">
        //             </a>
        return '
            <nav class="navbar navbar-expand-lg bg-dark fixed-top navbar-transparent " color-on-scroll="400">
                <div class="container">
                <div class="navbar-translate">
                    <a class="navbar-brand" href="#" onclick="_login(5)" rel="tooltip" data-placement="bottom" >
                        <img src="'.$st['nav']['logo'].'" style="width:'.$st['nav']['wdLogo'].'">
                    </a>
                    <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar top-bar"></span>
                        <span class="navbar-toggler-bar middle-bar"></span>
                        <span class="navbar-toggler-bar bottom-bar"></span>
                        Menu
                    </button>
                </div>
                <div class="collapse navbar-collapse justify-content-end" id="navigation" data-nav-image="./assets/img/blurred-image-1.jpg">
                    <ul class="navbar-nav">
                        '.$fdata.'                    
                    </ul>
                    
                </div>
                </div>
            </nav>
        ';
        
    }
    function _tm1Navbar($st){
        // class="container" <img  id="sliderx" style="padding: 0px;"  class="col-2" src="'.$st['nav']['dataImage1'].'">
        // <img  id="slider" style="padding: 0px;" class="col-12" src="'.$st['nav']['dataImage'].'">
        //         <img  id="sliderx" style="padding: 0px;"  class="col-2" src="'.$st['nav']['dataImage1'].'">
        // <div class="col-10"></div>
        //             <img  id="sliderx" style="padding: 0px;opacity: 0.9;"  class="col-2" src="'.$st['nav']['dataImage1'].'">
       return '
            <div class="page-header clear-filter" filter-color="orange">
                <div class="row page-header-image" id="slider"  data-parallax="true" style="background-image:url('.$st['nav']['dataImage'].');margin: 0px;">
                    
                </div>
                <div  id="menuAPP">
                </div>
            </div>
       ';
    }
    function _pg1Footer(){
        return '
        <footer class="footer">
            <div class="container-fluid">
                <nav>
                    <ul class="footer-menu">
                        <li>
                            <a href="#">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Company
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Portfolio
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Blog
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-center">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        <a href="http://www.creative-tim.com">Creative Tim</a>, made with love for a better web
                    </p>
                </nav>
            </div>
        </footer>
        ';
    }
    function _contentDropList($list){
        $fdata='';
        foreach ($list as $key => $v) {
            // return print_r($v);
            if(empty($v['url'])){
                $fdata.='
                <a class="dropdown-item" href="#" onclick="'.$v['onclick'].'">
                    '.$v['ic'].' '.$v['nm'].'
                </a>';
            }else{
                $fdata.='
                    <a class="dropdown-item" href="'.$v['url'].'">
                        '.$v['ic'].' '.$v['nm'].'
                    </a>';
            }
        }
        return $fdata;
    }
    function _start1Tm($p){
        // $p=[
        //     "pgStart"=>"",
        //     "pgEnd"=>"",
        //     "nm"=>"",
        //     "kdJab"=>3,
        //     "idBody"=>"bodyTM1" // body content
        // ];
        $st=_tm1Setting($p);
        
        $st['menu'][$p['ind']-1]['menu'][0]['ac']='active';
        // return print_r($st['menu']);
        if($p['index']!=null){
            $st['menu'][$p['ind']]['menu'][0]['subMenu'][$p['index']]['ac']='active';
        }
        return [
            "head"=>_tm1Head(),
            "tmFooter"=>_tm1Footer(),
            "tmBody"=>_tm1Header($st).
            '
                <div class="wrapper">
                    '._tm1Navbar($st).'
                    <div class="main" id="'.$p['idBody'].'">
                    </div>
                </div>
            '
        ];
    }
?>
