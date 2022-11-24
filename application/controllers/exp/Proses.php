<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// sess enc mbgs lbgs qexec
class Proses extends CI_Controller {
    private $bgs;
    function __construct(){
        parent::__construct();
        $this->_=array();
        
        // $this->kdMember=$this->sess->kdMember;
        // $this->kdMember1=$this->sess->kdMember1;
        // $this->nmMember=$this->sess->nmMember;
        // $this->kdJabatan=$this->sess->kdJabatan;
        // $this->kdKantor=$this->sess->kdKantor;
        // $this->nmKantor=$this->sess->nmKantor;

        $this->qtbl=_getNKA("c-prod",true);
        // $this->qtbl['p-kant']['nm']
    }
	public function checkUser(){
        $kondisi=false;
        if($this->sess->kode==null){
            $data=$_POST['data'];
            if(empty($_POST['data'])){
                return redirect("control");
            }
            
            $baseEND=json_decode((base64_decode($data)));
            // if(!$this->mbgs->_backCodes(base64_decode($_POST['code']))){
            //     return $this->mbgs->resFalse("Tidak Sesuai Keamanan Sistem !!!");
            // }

            $password   =$baseEND->{'password'};
            $username   =$baseEND->{'username'};
            $kondisi=true;
        }else{
            $password   =$this->sess->password;
            $username   =$this->sess->nama;
        }
        $q="select * from member where UPPER(username)=UPPER('".$username."') and UPPER(password)=UPPER('".$password."') and kdApp='".$this->mbs->app->kd."'";
        // return print_r($q);
        $member=$this->qexec->_func($q);
        if(count($member)==1){
            if($kondisi){//for login awal no sess
                if(substr($member[0]['kdJabatan'],5)<3){
                    $pembahasan=$this->qexec->_func("SELECT tahun,noPembahasan,progres,finals,files FROM pembahasan ORDER by ins desc limit 1");
                    if(count($pembahasan)==0){
                        return $this->mbgs->resFalse("Sistem Tidak Dapat Digunakan ");
                    }
                }
                return $this->mbgs->resTrue("Sukses");
            }
            return $this->mbgs->resFalse("Sukses");
        }else{
            if($kondisi){//for login awal no sess
                return $this->mbgs->resFalse("user tidak dapat ditemukan !!!");
            }
        }
        print_r(json_encode($this->_));
    }


    function insProduk(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-prod",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdKantor   =$baseEND->{'kdKantor'};
            $nmProduk   =$baseEND->{'nmProduk'};
            $deskripsi  =$baseEND->{'deskripsi'};

            $keyTabel="kdProduk";
            $kdTabel=$this->qexec->_func("select ".$keyTabel." from produk where kdKantor='".$this->kdKantor."' ORDER BY ".$keyTabel." DESC limit 1");
            if(count($kdTabel)>0){
                $kdTabel=$kdTabel[0][$keyTabel]+1;
            }else{
                $kdTabel=1;
            }

            $check=$this->qexec->_proc("
                INSERT INTO produk(kdProduk, kdProduk1, kdKantor, nmProduk, deskripsi) VALUES 
                (
                    ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($this->qtbl['c-prod']['nm'].$kdTabel).",
                    ".$this->mbgs->_valforQuery($kdKantor).",".$this->mbgs->_valforQuery($nmProduk).",
                    ".$this->mbgs->_valforQuery($deskripsi)."
                )
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dproduk($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updProduk(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-prod",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdKantor   =$baseEND->{'kdKantor'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $nmProduk   =$baseEND->{'nmProduk'};
            $deskripsi  =$baseEND->{'deskripsi'};

            $check=$this->qexec->_proc("
                update produk set nmProduk=".$this->mbgs->_valforQuery($nmProduk).", deskripsi=".$this->mbgs->_valforQuery($deskripsi)."
                where kdProduk=".$this->mbgs->_valforQuery($kdProduk)." 
                and kdKantor=".$this->mbgs->_valforQuery($kdKantor)."
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dproduk($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delProduk(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-prod",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdKantor   =$baseEND->{'kdKantor'};
            $kdProduk   =$baseEND->{'kdProduk'};

            $check=$this->qexec->_proc("
                delete from produk
                where kdProduk=".$this->mbgs->_valforQuery($kdProduk)." 
                and kdKantor=".$this->mbgs->_valforQuery($kdKantor)."
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dproduk($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }

    function insKantor(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-kant",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $direktur   =$baseEND->{'direktur'};
            $nmKantor   =$baseEND->{'nmKantor'};
            $alamat     =$baseEND->{'alamat'};
            $noHp       =$baseEND->{'noHp'};
            $email      =$baseEND->{'email'};

            $keyTabel="kdKantor";
            $kdTabel=$this->qexec->_func("select ".$keyTabel." from kantor ORDER BY ".$keyTabel." DESC limit 1");
            if(count($kdTabel)>0){
                $kdTabel=$kdTabel[0][$keyTabel]+1;
            }else{
                $kdTabel=1;
            }

            $check=$this->qexec->_proc("
                INSERT INTO kantor(kdKantor,kdKantor1,pemilik,nmKantor, alamat,noHp,gmail) VALUES 
                (
                    ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($this->qtbl['p-kant']['nm'].$kdTabel).",
                    ".$this->mbgs->_valforQuery($direktur).",".$this->mbgs->_valforQuery($nmKantor).",
                    ".$this->mbgs->_valforQuery($alamat).",".$this->mbgs->_valforQuery($noHp).",".$this->mbgs->_valforQuery($email)."
                )
            ");
            // return print_r($check);
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dkantor(""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updKantor(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-kant",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $direktur   =$baseEND->{'direktur'};
            $nmKantor   =$baseEND->{'nmKantor'};
            $alamat     =$baseEND->{'alamat'};
            $noHp       =$baseEND->{'noHp'};
            $email      =$baseEND->{'email'};
            $kdKantor      =$baseEND->{'kdKantor'};

            $check=$this->qexec->_proc("
                update kantor set pemilik=".$this->mbgs->_valforQuery($direktur).", 
                    nmKantor=".$this->mbgs->_valforQuery($nmKantor).",
                    alamat=".$this->mbgs->_valforQuery($alamat).", 
                    noHp=".$this->mbgs->_valforQuery($noHp).",
                    gmail=".$this->mbgs->_valforQuery($email)."
                where kdKantor=".$this->mbgs->_valforQuery($kdKantor)."
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dkantor(""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delKantor(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-kant",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdKantor      =$baseEND->{'kdKantor'};

            $check=$this->qexec->_proc("
                delete from kantor
                where kdKantor=".$this->mbgs->_valforQuery($kdKantor)."
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dkantor(""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }

    function insJabatan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-jaba",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmJaba   =$baseEND->{'nmJaba'};

            $keyTabel="kdJabatan";
            $kdTabel=$this->qexec->_func("select ".$keyTabel." from jabatan ORDER BY ".$keyTabel." DESC limit 1");
            if(count($kdTabel)>0){
                $kdTabel=$kdTabel[0][$keyTabel]+1;
            }else{
                $kdTabel=1;
            }

            $check=$this->qexec->_proc("
                INSERT INTO jabatan(kdJabatan,kdJabatan1,nmJabatan) VALUES 
                (
                    ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($this->qtbl['p-jaba']['nm'].$kdTabel).",
                    ".$this->mbgs->_valforQuery($nmJaba)."
                )
            ");
            // return print_r($check);
            if($check){
                $this->_['ddata']=$this->qexec->_func(_djabatan(""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updJabatan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-jaba",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmJaba   =$baseEND->{'nmJaba'};
            $kdJaba   =$baseEND->{'kdJaba'};

            $check=$this->qexec->_proc("
                update jabatan set nmJabatan=".$this->mbgs->_valforQuery($nmJaba)."
                where kdJabatan=".$this->mbgs->_valforQuery($kdJaba)."
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_djabatan(""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delJabatan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-jaba",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdJaba      =$baseEND->{'kdJaba'};

            $check=$this->qexec->_proc("
                delete from jabatan
                where kdJabatan=".$this->mbgs->_valforQuery($kdJaba)."
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_djabatan(""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }

    function insMember(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-memb",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmMember   =$baseEND->{'nmMember'};
            $kdKantor   =$baseEND->{'kdKantor'};
            $kdJabatan   =$baseEND->{'kdJabatan'};

            $kabupaten   =$baseEND->{'kabupaten'};
            $kecamatan   =$baseEND->{'kecamatan'};
            $desa   =$baseEND->{'desa'};

            $noHp   =$baseEND->{'noHp'};
            $tambahan   =$baseEND->{'tambahan'};
            $username   =$baseEND->{'username'};
            $password   =$baseEND->{'password'};

            $keyTabel="kdMember";
            $kdTabel=$this->qexec->_func("select ".$keyTabel." from member where kdKantor='".$kdKantor."' ORDER BY ".$keyTabel." DESC limit 1");
            if(count($kdTabel)>0){
                $kdTabel=$kdTabel[0][$keyTabel]+1;
            }else{
                $kdTabel=1;
            }

            $kdMemberx=$this->mbgs->app['unik'].$this->qtbl['p-memb']['nm'].$kdTabel;

            

            $check=$this->qexec->_proc("
                INSERT INTO member(
                    kdMember, kdMember1, kdKantor, nmMember, 
                    kdJabatan, kabupaten, kecamatan, desa, 
                    noHp, tambahan, username, password
                ) VALUES
                (
                    ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($kdMemberx).",
                        ".$this->mbgs->_valforQuery($kdKantor).",".$this->mbgs->_valforQuery($nmMember).",
                    ".$this->mbgs->_valforQuery($kdJabatan).",".$this->mbgs->_valforQuery($kabupaten).",
                        ".$this->mbgs->_valforQuery($kecamatan).",".$this->mbgs->_valforQuery($desa).",
                    ".$this->mbgs->_valforQuery($noHp).",".$this->mbgs->_valforQuery($tambahan).",
                        ".$this->mbgs->_valforQuery($this->mbgs->app['qmem'].($kdTabel-1)."-".$username).",".$this->mbgs->_valforQuery($password)."
                )
            ");
            // return print_r($check);
            if($check){
                $check=$this->addKeySistem(base64_encode(json_encode([
                    "kdMember"=>$kdMemberx,
                    "kdJabatan"=>substr($kdJabatan,5)
                ])));
                if($check){
                    $this->_['ddata']=$this->qexec->_func(_dmember(""));
                    return $this->mbgs->resTrue($this->_);
                }
                return $this->mbgs->resFalse("Tambah Key Sistem !!!");
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updMember(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-memb",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdMember   =$baseEND->{'kdMember'};
            $kdKantor   =$baseEND->{'kdKantor'};
            
            $nmMember   =$baseEND->{'nmMember'};
            $kdJabatan   =$baseEND->{'kdJabatan'};

            $kabupaten   =$baseEND->{'kabupaten'};
            $kecamatan   =$baseEND->{'kecamatan'};
            $desa   =$baseEND->{'desa'};

            $noHp   =$baseEND->{'noHp'};
            $tambahan   =$baseEND->{'tambahan'};
            $username   =$baseEND->{'username'};
            $password   =$baseEND->{'password'};

            $check=$this->qexec->_proc("
                update member set 
                    nmMember=".$this->mbgs->_valforQuery($nmMember).", 
                    kdJabatan=".$this->mbgs->_valforQuery($kdJabatan).", 
                    kabupaten=".$this->mbgs->_valforQuery($kabupaten).", 
                    kecamatan=".$this->mbgs->_valforQuery($kecamatan).", 
                    desa=".$this->mbgs->_valforQuery($desa).", 
                    noHp=".$this->mbgs->_valforQuery($noHp).", 
                    tambahan=".$this->mbgs->_valforQuery($tambahan).", 
                    username=".$this->mbgs->_valforQuery($username).", 
                    password=".$this->mbgs->_valforQuery($password)."
                where kdMember=".$this->mbgs->_valforQuery($kdMember)."
                    and kdKantor=".$this->mbgs->_valforQuery($kdKantor)."
            ");
            if($check){
                $qtambahan=' and c.kdJabatan!=5';
                if($this->kdJabatan<2 && $this->kdJabatan<5){
                    $qtambahan.=" and a.kdMember1='".$this->kdMember."' ";
                }else if($this->kdJabatan==5){
                    $qtambahan='';
                }
                $this->_['ddata']=$this->qexec->_func(_dmember($qtambahan));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delMember(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-memb",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdMember   =$baseEND->{'kdMember'};
            $kdKantor   =$baseEND->{'kdKantor'};

            $check=$this->qexec->_proc("
                delete from member
                where kdMember=".$this->mbgs->_valforQuery($kdMember)."
                    and kdKantor=".$this->mbgs->_valforQuery($kdKantor)."
            ");
            if($check){
                $qtambahan=' and c.kdJabatan!=5';
                if($this->kdJabatan<2 && $this->kdJabatan<5){
                    $qtambahan.=" and a.kdMember1='".$this->kdMember."' ";
                }else if($this->kdJabatan==5){
                    $qtambahan='';
                }
                $this->_['ddata']=$this->qexec->_func(_dmember($qtambahan));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }

    function insSubProduk(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-spro",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmSub      =$baseEND->{'nmSub'};
            $pagu       =$baseEND->{'pagu'};
            $deskripsi  =$baseEND->{'deskripsi'};
            $kdProduk   =$baseEND->{'kdProduk'};
            

            $keyTabel="kdSub";
            $kdTabel=$this->qexec->_func("select ".$keyTabel." from produkSub where kdKantor='".$this->kdKantor."' and kdProduk='".$kdProduk."' ORDER BY ".$keyTabel." DESC limit 1");
            if(count($kdTabel)>0){
                $kdTabel=$kdTabel[0][$keyTabel]+1;
            }else{
                $kdTabel=1;
            }

            $check=$this->qexec->_proc("
               INSERT INTO produksub (kdSub, kdProduk, kdKantor, nmSub, deskripsi, pagu) VALUES
                (
                    ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($kdProduk).",
                        ".$this->mbgs->_valforQuery($this->kdKantor).",".$this->mbgs->_valforQuery($nmSub).",
                    ".$this->mbgs->_valforQuery($deskripsi).",".$this->mbgs->_valforQuery($pagu)."
                )
            ");
            // return print_r($check);
            if($check){
                $this->_['sub']=$this->qexec->_func(_dsubProduk($this->kdKantor," and a.kdProduk=".$this->mbgs->_valforQuery($kdProduk).""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updSubProduk(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-spro",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmSub      =$baseEND->{'nmSub'};
            $pagu       =$baseEND->{'pagu'};
            $deskripsi  =$baseEND->{'deskripsi'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};
            // $kdKantor   =$baseEND->{'kdKantor'};

            $check=$this->qexec->_proc("
                update produksub set 
                    nmSub=".$this->mbgs->_valforQuery($nmSub).", 
                    deskripsi=".$this->mbgs->_valforQuery($deskripsi).", 
                    pagu=".$this->mbgs->_valforQuery($pagu)."
                where 
                    kdSub =".$this->mbgs->_valforQuery($kdSub)."
                    and kdProduk=".$this->mbgs->_valforQuery($kdProduk)." 
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
            ");
            if($check){
                $this->_['sub']=$this->qexec->_func(_dsubProduk($this->kdKantor," and a.kdProduk=".$this->mbgs->_valforQuery($kdProduk).""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delSubProduk(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-spro",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $check=$this->qexec->_proc("
                delete from produksub
                where 
                    kdSub =".$this->mbgs->_valforQuery($kdSub)."
                    and kdProduk=".$this->mbgs->_valforQuery($kdProduk)." 
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
            ");
            if($check){
                $this->_['sub']=$this->qexec->_func(_dsubProduk($this->kdKantor," and a.kdProduk=".$this->mbgs->_valforQuery($kdProduk).""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }

    function insTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-taha",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmTahapan  =$baseEND->{'nmTahapan'};
            $deskripsi  =$baseEND->{'deskripsi'};
            

            $keyTabel="kdTahapan";
            $kdTabel=$this->qexec->_func("select ".$keyTabel." from tahapan where kdKantor='".$this->kdKantor."' ORDER BY ".$keyTabel." DESC limit 1");
            if(count($kdTabel)>0){
                $kdTabel=$kdTabel[0][$keyTabel]+1;
            }else{
                $kdTabel=1;
            }

            $check=$this->qexec->_proc("
               INSERT INTO tahapan (kdTahapan, kdKantor, nmTahapan,deskripsi) VALUES
                (
                    ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($this->kdKantor).",
                    ".$this->mbgs->_valforQuery($nmTahapan).",".$this->mbgs->_valforQuery($deskripsi)."
                )
            ");
            // return print_r($check);
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dtahapan($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-taha",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmTahapan  =$baseEND->{'nmTahapan'};
            $deskripsi  =$baseEND->{'deskripsi'};
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdKantor   =$baseEND->{'kdKantor'};
            // $kdKantor   =$baseEND->{'kdKantor'};

            $check=$this->qexec->_proc("
                update tahapan set 
                    nmTahapan=".$this->mbgs->_valforQuery($nmTahapan).",
                    deskripsi=".$this->mbgs->_valforQuery($deskripsi)."
                where 
                    kdTahapan =".$this->mbgs->_valforQuery($kdTahapan)." 
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)." 
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dtahapan($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-taha",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdKantor   =$baseEND->{'kdKantor'};

            $check=$this->qexec->_proc("
                delete from tahapan
                where 
                    kdTahapan =".$this->mbgs->_valforQuery($kdTahapan)." 
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)." 
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dtahapan($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }

    function insSarat(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pers",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmSarat    =$baseEND->{'nmSarat'};
            $deskripsi  =$baseEND->{'deskripsi'};
            

            $keyTabel="kdPersyaratan";
            $kdTabel=$this->qexec->_func("select ".$keyTabel." from persyaratan where kdKantor='".$this->kdKantor."' ORDER BY ".$keyTabel." DESC limit 1");
            if(count($kdTabel)>0){
                $kdTabel=$kdTabel[0][$keyTabel]+1;
            }else{
                $kdTabel=1;
            }

            $check=$this->qexec->_proc("
               INSERT INTO persyaratan (kdPersyaratan, kdKantor, nmPersyaratan,deskripsi) VALUES
                (
                    ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($this->kdKantor).",
                    ".$this->mbgs->_valforQuery($nmSarat).",".$this->mbgs->_valforQuery($deskripsi)."
                )
            ");
            // return print_r($check);
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dsarat($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updSarat(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-pers",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nmSarat    =$baseEND->{'nmSarat'};
            $deskripsi  =$baseEND->{'deskripsi'};
            $kdPersyaratan  =$baseEND->{'kdPersyaratan'};
            $kdKantor   =$baseEND->{'kdKantor'};
            // $kdKantor   =$baseEND->{'kdKantor'};

            $check=$this->qexec->_proc("
                update persyaratan set 
                    nmPersyaratan=".$this->mbgs->_valforQuery($nmSarat).",
                    deskripsi=".$this->mbgs->_valforQuery($deskripsi)."
                where 
                    kdPersyaratan =".$this->mbgs->_valforQuery($kdPersyaratan)." 
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)." 
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dsarat($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delSarat(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-pers",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdPersyaratan  =$baseEND->{'kdPersyaratan'};
            $kdKantor   =$baseEND->{'kdKantor'};

            $check=$this->qexec->_proc("
                delete from persyaratan
                where 
                    kdPersyaratan =".$this->mbgs->_valforQuery($kdPersyaratan)." 
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)." 
            ");
            if($check){
                $this->_['ddata']=$this->qexec->_func(_dsarat($this->kdKantor,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }

    function insSetTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-taha",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $lpekerjaan =$baseEND->{'lpekerjaan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};
            $index      =$baseEND->{'index'};
            

            $keyTabel="kdTG";
            $kdTabel=$this->qexec->_func("select ".$keyTabel." from tahapangroup where 
                kdKantor='".$this->kdKantor."' 
                and kdProduk='".$kdProduk."' 
                and kdSub='".$kdSub."' 
            ORDER BY ".$keyTabel." DESC limit 1");
            if(count($kdTabel)>0){
                $kdTabel=$kdTabel[0][$keyTabel]+1;
            }else{
                $kdTabel=1;
            }

            $check=$this->qexec->_proc("
               INSERT INTO tahapangroup (kdTG, kdKantor, kdProduk,kdSub,kdTahapan,lamaPekerjaan,ind) VALUES
                (
                    ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($this->kdKantor).",
                    ".$this->mbgs->_valforQuery($kdProduk).",".$this->mbgs->_valforQuery($kdSub).",
                    ".$this->mbgs->_valforQuery($kdTahapan).",".$this->mbgs->_valforQuery($lpekerjaan).",
                    ".$this->mbgs->_valforQuery($index)."
                )
            ");
            // return print_r($check);
            if($check){
                $this->_['data']=$this->qexec->_func(_dtahapanGroup($this->kdKantor,$kdProduk,$kdSub,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updSetTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-taha",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $lpekerjaan =$baseEND->{'lpekerjaan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};
            $kdTG       =$baseEND->{'kdTG'};
            $index      =$baseEND->{'index'};

            $check=$this->qexec->_proc("
                update tahapangroup set 
                    kdTahapan=".$this->mbgs->_valforQuery($kdTahapan).",
                    lamaPekerjaan=".$this->mbgs->_valforQuery($lpekerjaan)."
                where kdTG =".$this->mbgs->_valforQuery($kdTG)."
                    and kdKantor =".$this->mbgs->_valforQuery($this->kdKantor)."
                    and kdProduk=".$this->mbgs->_valforQuery($kdProduk)."
                    and kdSub=".$this->mbgs->_valforQuery($kdSub)."
            ");
            if($check){
                $this->_['data']=$this->qexec->_func(_dtahapanGroup($this->kdKantor,$kdProduk,$kdSub,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delSetTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-taha",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};
            $kdTG       =$baseEND->{'kdTG'};

            $check=$this->qexec->_proc("
                delete from tahapangroup
                where 
                    kdTG=".$this->mbgs->_valforQuery($kdTG)."
                    and kdProduk =".$this->mbgs->_valforQuery($kdProduk)." 
                    and kdSub=".$this->mbgs->_valforQuery($kdSub)." 
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)." 
            ");
            if($check){
                $this->_['data']=$this->qexec->_func(_dtahapanGroup($this->kdKantor,$kdProduk,$kdSub,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function getDataSetTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-taha",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};
            
            $this->_['data']=$this->qexec->_func(_dtahapanGroup($this->kdKantor,$kdProduk,$kdSub,""));
            return $this->mbgs->resTrue($this->_);
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function updPosisiSetTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("u-taha",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));

            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};
            
            $kdTG       =$baseEND->{'kdTG'};
            $kdTG1       =$baseEND->{'kdTG1'};

            $index      =$baseEND->{'index'};
            $index1      =$baseEND->{'index1'};

            $check=$this->qexec->_multiProc("
                update tahapangroup set 
                    ind=".$this->mbgs->_valforQuery($index)."
                where kdTG =".$this->mbgs->_valforQuery($kdTG)."
                    and kdKantor =".$this->mbgs->_valforQuery($this->kdKantor)."
                    and kdProduk=".$this->mbgs->_valforQuery($kdProduk)."
                    and kdSub=".$this->mbgs->_valforQuery($kdSub).";

                update tahapangroup set 
                    ind=".$this->mbgs->_valforQuery($index1)."
                where kdTG =".$this->mbgs->_valforQuery($kdTG1)."
                    and kdKantor =".$this->mbgs->_valforQuery($this->kdKantor)."
                    and kdProduk=".$this->mbgs->_valforQuery($kdProduk)."
                    and kdSub=".$this->mbgs->_valforQuery($kdSub)."
            ");
            if($check){
                $this->_['data']=$this->qexec->_func(_dtahapanGroup($this->kdKantor,$kdProduk,$kdSub,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    
    function saveSetSarat(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pers",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $data       =$baseEND->{'data'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};


            $q="
                delete from saratgroup where kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)." 
                    and kdProduk=".$this->mbgs->_valforQuery($kdProduk)." 
                    and kdSub=".$this->mbgs->_valforQuery($kdSub).";
               INSERT INTO saratgroup (kdSG, kdKantor, kdProduk,kdSub,kdPersyaratan) VALUES
            ";
            foreach ($data as $key => $v) {
                $q.="(
                    ".$this->mbgs->_valforQuery(($key+1)).",".$this->mbgs->_valforQuery($this->kdKantor)." ,
                    ".$this->mbgs->_valforQuery($kdProduk).",".$this->mbgs->_valforQuery($kdSub).",
                    ".$this->mbgs->_valforQuery($v->kd)." 
                ),";
            }
            $q=substr($q,0,strlen($q)-1);
            // return print_r($q);
            $check=$this->qexec->_multiProc($q);
            if($check){
                $this->_['data']=$this->qexec->_func(_dsaratGroup($this->kdKantor,$kdProduk,$kdSub,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function getDataSetSarat(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pers",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};
            
            $this->_['data']=$this->qexec->_func(_dsaratGroup($this->kdKantor,$kdProduk,$kdSub,""));
            return $this->mbgs->resTrue($this->_);
        }return $this->mbgs->resFalse($portal['msg']);
    }

    function saveDaftar(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $nik        =$baseEND->{'nik'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $nm         =$baseEND->{'nm'};
            $modal      =$baseEND->{'modal'};
            $hp         =$baseEND->{'hp'};

            $total      =$baseEND->{'total'};
            $bayar      =$baseEND->{'bayar'};
            $sisa       =$baseEND->{'sisa'};
            $kdDaftar   =$baseEND->{'kdDaftar'};

            $nmHP       =$baseEND->{'nmHP'};
            $aliasHP    =$baseEND->{'aliasHP'};
            $emailHP    =$baseEND->{'emailHP'};
            $update     =$baseEND->{'update'};
            
            if($update){
                $kdPekerjaan     =$baseEND->{'kdPekerjaan'};
                $q="
                    update pekerjaan set 
                        kdProduk=".$this->mbgs->_valforQuery($kdProduk).", 
                        kdSub=".$this->mbgs->_valforQuery($kdSub).", 
                        nik=".$this->mbgs->_valforQuery($nik).", 
                        nm=".$this->mbgs->_valforQuery($nm).", 
                        modal=".$this->mbgs->_valforQuery($modal).", 
                        hp=".$this->mbgs->_valforQuery($hp).", 
                        total=".$this->mbgs->_valforQuery($total).", 
                        bayar=".$this->mbgs->_valforQuery($bayar).", 
                        sisa=".$this->mbgs->_valforQuery($sisa).", 
                        kdPK=".$this->mbgs->_valforQuery($kdDaftar).",
                        
                        nmHP=".$this->mbgs->_valforQuery($nmHP).",
                        aliasHP=".$this->mbgs->_valforQuery($aliasHP).",
                        emailHP=".$this->mbgs->_valforQuery($emailHP)."
                    where 
                        kdPekerjaan=".$this->mbgs->_valforQuery($kdPekerjaan)."
                        and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                ";
            }else{
                $keyTabel="kdPekerjaan";
                $kdTabel=$this->qexec->_func("select ".$keyTabel." from pekerjaan
                    ORDER BY ".$keyTabel." DESC limit 1
                ");
                if(count($kdTabel)>0){
                    $kdTabel=$kdTabel[0][$keyTabel]+1;
                }else{
                    $kdTabel=1;
                }

                $q="
                    INSERT INTO pekerjaan(
                        kdPekerjaan, kdKantor, kdProduk, 
                        kdSub, nik, nm, 
                        modal, hp, total, 
                        bayar, sisa, kdPK,kdMember,
                        nmHP,aliasHP,emailHP
                    ) VALUES (
                        ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($this->kdKantor).",".$this->mbgs->_valforQuery($kdProduk).",
                        ".$this->mbgs->_valforQuery($kdSub).",".$this->mbgs->_valforQuery($nik).",".$this->mbgs->_valforQuery($nm).",
                        ".$this->mbgs->_valforQuery($modal).",".$this->mbgs->_valforQuery($hp).",".$this->mbgs->_valforQuery($total).",
                        ".$this->mbgs->_valforQuery($bayar).",".$this->mbgs->_valforQuery($sisa).",".$this->mbgs->_valforQuery($kdDaftar).",
                            ".$this->mbgs->_valforQuery($this->kdMember).",
                        ".$this->mbgs->_valforQuery($nmHP).",".$this->mbgs->_valforQuery($aliasHP).",".$this->mbgs->_valforQuery($emailHP)."
                    )
                ";
            }
            $check=$this->qexec->_proc($q);
            if($check){
                $qtambahan='';
                if($this->kdJabatan<3){
                    $qtambahan=" and a.kdMember='".$this->kdMember."'";
                }
                $this->_['data']=$this->qexec->_func(_dpekerjaan($this->kdKantor," and a.final=0 ".$qtambahan));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function delDaftar(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdPekerjaan     =$baseEND->{'kdPekerjaan'};
            $q="
                delete from pekerjaan 
                where 
                    kdPekerjaan=".$this->mbgs->_valforQuery($kdPekerjaan)."
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
            ";
            $check=$this->qexec->_proc($q);
            if($check){
                $qtambahan='';
                if($this->kdJabatan<3){
                    $qtambahan=" and a.kdMember='".$this->kdMember."'";
                }
                $this->_['data']=$this->qexec->_func(_dpekerjaan($this->kdKantor," and a.final=0 ".$qtambahan));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function btlDaftar(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdPekerjaan     =$baseEND->{'kdPekerjaan'};
            $q="
                update pekerjaan set final=1,
                    tglFinal=now(),
                    total=bayar,
                    sisa=0,
                    batal=1
                where 
                    kdPekerjaan=".$this->mbgs->_valforQuery($kdPekerjaan)."
                    and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
            ";
            $check=$this->qexec->_proc($q);
            if($check){
                $qtambahan='';
                if($this->kdJabatan<3){
                    $qtambahan=" and a.kdMember='".$this->kdMember."'";
                }
                $this->_['data']=$this->qexec->_func(_dpekerjaan($this->kdKantor," and a.final=0 ".$qtambahan));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function locDaftar(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdPekerjaan    =$baseEND->{'kdPekerjaan'};
            $kdProduk       =$baseEND->{'kdProduk'};
            $kdSub          =$baseEND->{'kdSub'};
            $dtahapan=$this->qexec->_func(_dtahapanGroup($this->kdKantor,$kdProduk,$kdSub,""));
            // return print_r($dtahapan)
            $q="update pekerjaan set arsip=1,maxTahap='".count($dtahapan)."' where kdPekerjaan='".$kdPekerjaan."';
                delete from pekerjaansub where kdPekerjaan='".$this->qtbl['p-pend']['nm'].$kdPekerjaan."' and kdKantor='".$this->kdKantor."';
                INSERT INTO pekerjaansub(`kdPS`, `kdKantor`, `kdPekerjaan`, `kdTG`) VALUES ";
            foreach ($dtahapan as $i => $v) {
                $q.="(
                    ".$this->mbgs->_valforQuery(($i+1)).",".$this->mbgs->_valforQuery($this->kdKantor).",
                    ".$this->mbgs->_valforQuery($this->qtbl['p-pend']['nm'].$kdPekerjaan).",".$this->mbgs->_valforQuery($v['kdTG'])."
                ),";
            }
            $q=substr($q,0,strlen($q)-1);
            // return print_r($q);
            $check=$this->qexec->_multiProc($q);
            if($check){
                $qtambahan='';
                if($this->kdJabatan<3){
                    $qtambahan=" and a.kdMember='".$this->kdMember."'";
                }
                $this->_['data']=$this->qexec->_func(_dpekerjaan($this->kdKantor," and a.final=0 ".$qtambahan));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    function getDataForNextTahap(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdProduk       =$baseEND->{'kdProduk'};
            $kdSub          =$baseEND->{'kdSub'};
        
            $this->_['data']=$this->qexec->_func(_dforNextStep($this->kdKantor,$kdProduk,$kdSub," and a.final=1"));
            return $this->mbgs->resTrue($this->_);
        }return $this->mbgs->resFalse($portal['msg']);
    }
    

    function tahapanxfile(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            // return print_r($baseEND);
            $kdPekerjaan=$baseEND->{'kdPekerjaan'};
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $kdPS       =$baseEND->{'kdPS'};
            $ket        =$baseEND->{'ket'};
            
            $file=$_POST['file'];
            $namaFile="";
            if(!empty($file)){
                // return print_r($file['data']);
                $namaFile=",file='";
                foreach ($file as $key => $v) {
                    // $namaFile.=$this->_uploadImage($v['src'],$v['nama'])."<2G18>";
                    $namaFile.=$this->_uploadFiles($v['data'],$v['nama']);
                }
                $namaFile.="'";

            }
            // update pekerjaan set tahap=tahap+1 where kdPekerjaan='".$kdPekerjaan."';
            $q="
                update pekerjaansub set keterangan=".$this->mbgs->_valforQuery($ket)."
                    ".$namaFile."
                where kdPS=".$this->mbgs->_valforQuery($kdPS)."
                and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                and kdPekerjaan=".$this->mbgs->_valforQuery( $this->qtbl['p-pend']['nm'].$kdPekerjaan)."
            ";
            // return print_r($q);
            $check=$this->qexec->_proc($q);
            if($check){
                $this->_['ddata']=$this->qexec->_func(
                    _dpekerjaanSub(
                        $this->kdKantor,
                        $kdPekerjaan,
                        $kdProduk,
                        $kdSub,
                        " "
                    )
                );
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
        
    }
    function tahapanximg(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            // return print_r($baseEND);
            $kdPekerjaan=$baseEND->{'kdPekerjaan'};
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $kdPS       =$baseEND->{'kdPS'};
            $ket        =$baseEND->{'ket'};
            
            $file=$_POST['file'];
            $namaFile="";
            if(!empty($file)){
                $namaFile=",img='";
                foreach ($file as $key => $v) {
                    $namaFile.=$this->_uploadImage($v['src'],$v['nama']);
                }
                $namaFile.="'";
            }
            // update pekerjaan set tahap=tahap+1 where kdPekerjaan='".$kdPekerjaan."';
            $q="
                update pekerjaansub set keterangan=".$this->mbgs->_valforQuery($ket)."
                    ".$namaFile."
                where kdPS=".$this->mbgs->_valforQuery($kdPS)."
                and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                and kdPekerjaan=".$this->mbgs->_valforQuery( $this->qtbl['p-pend']['nm'].$kdPekerjaan)."
            ";
            // return print_r($q);
            $check=$this->qexec->_proc($q);
            if($check){
                $this->_['ddata']=$this->qexec->_func(
                    _dpekerjaanSub(
                        $this->kdKantor,
                        $kdPekerjaan,
                        $kdProduk,
                        $kdSub,
                        " "
                    )
                );
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
        
    }
    function tahapanhasil(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            // return print_r($baseEND);
            $kdPekerjaan=$baseEND->{'kdPekerjaan'};
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $kdPS       =$baseEND->{'kdPS'};
            $ket        =$baseEND->{'ket'};
            
            $file=$_POST['file'];
            $img=$file['img'];
            $file=$file['file'];
            // return print_r($img);
            $namaFile="";
            if(!empty($img)){
                $namaFile=",img='";
                foreach ($img as $key => $v) {
                    $namaFile.=$this->_uploadImage($v['src'],$v['nama']);
                }
                $namaFile.="'";
            }
            if(!empty($file)){
                // return print_r($file['data']);
                $namaFile.=",file='";
                foreach ($file as $key => $v) {
                    // $namaFile.=$this->_uploadImage($v['src'],$v['nama'])."<2G18>";
                    $namaFile.=$this->_uploadFiles($v['data'],$v['nama']);
                }
                $namaFile.="'";

            }
            // update pekerjaan set tahap=tahap+1 where kdPekerjaan='".$kdPekerjaan."';
            $q="
                update pekerjaansub set keterangan=".$this->mbgs->_valforQuery($ket)."
                    ".$namaFile."
                where kdPS=".$this->mbgs->_valforQuery($kdPS)."
                and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                and kdPekerjaan=".$this->mbgs->_valforQuery( $this->qtbl['p-pend']['nm'].$kdPekerjaan)."
            ";
            // return print_r($q);
            $check=$this->qexec->_proc($q);
            if($check){
                $this->_['ddata']=$this->qexec->_func(
                    _dpekerjaanSub(
                        $this->kdKantor,
                        $kdPekerjaan,
                        $kdProduk,
                        $kdSub,
                        " "
                    )
                );
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
        
    }
    function tahapanxpilih(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            // return print_r($baseEND);
            $kdPekerjaan=$baseEND->{'kdPekerjaan'};
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $kdPS       =$baseEND->{'kdPS'};
            $ket        =$baseEND->{'ket'};
            $pilihan    =$baseEND->{'pilihan'};
            
            // update pekerjaan set tahap=tahap+1 where kdPekerjaan='".$kdPekerjaan."';
            $q="
                update pekerjaansub set keterangan=".$this->mbgs->_valforQuery($ket).",
                    pilihan=".$this->mbgs->_valforQuery($pilihan)."
                where kdPS=".$this->mbgs->_valforQuery($kdPS)."
                and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                and kdPekerjaan=".$this->mbgs->_valforQuery( $this->qtbl['p-pend']['nm'].$kdPekerjaan)."
            ";
            // return print_r($q);
            $check=$this->qexec->_proc($q);
            if($check){
                $this->_['ddata']=$this->qexec->_func(
                    _dpekerjaanSub(
                        $this->kdKantor,
                        $kdPekerjaan,
                        $kdProduk,
                        $kdSub,
                        " "
                    )
                );
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
        
    }
    function tahapanxfilePil1(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            // return print_r($baseEND);
            $kdPekerjaan=$baseEND->{'kdPekerjaan'};
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $kdPS       =$baseEND->{'kdPS'};
            
            $ket        =$baseEND->{'ket'};
            $pilihan    =$baseEND->{'pilihan'};
            
            $file=$_POST['file'];
            $namaFile="";
            if(!empty($file)){
                $namaFile.=",file='";
                foreach ($file as $key => $v) {
                    // $namaFile.=$this->_uploadImage($v['src'],$v['nama'])."<2G18>";
                    $namaFile.=$this->_uploadFiles($v['data'],$v['nama']);
                }
                $namaFile.="'";

            }
            // update pekerjaan set tahap=tahap+1 where kdPekerjaan='".$kdPekerjaan."';
            $q="
                update pekerjaansub set keterangan=".$this->mbgs->_valforQuery($ket).",
                    pilihan=".$this->mbgs->_valforQuery($pilihan)."
                    ".$namaFile."
                where kdPS=".$this->mbgs->_valforQuery($kdPS)."
                and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                and kdPekerjaan=".$this->mbgs->_valforQuery( $this->qtbl['p-pend']['nm'].$kdPekerjaan)."
            ";
            // return print_r($q);
            $check=$this->qexec->_proc($q);
            if($check){
                $this->_['ddata']=$this->qexec->_func(
                    _dpekerjaanSub(
                        $this->kdKantor,
                        $kdPekerjaan,
                        $kdProduk,
                        $kdSub,
                        " "
                    )
                );
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
        
    }
    

    // hampir sama
    function arsipkanTahapan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            // return print_r($baseEND);
            $kdPekerjaan=$baseEND->{'kdPekerjaan'};
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $kdPS       =$baseEND->{'kdPS'};
            
            $q="
                update pekerjaan set tahap=tahap+1 where kdPekerjaan='".$kdPekerjaan."';

                update pekerjaansub set arsip=1,ins=now()
                where kdPS=".$this->mbgs->_valforQuery($kdPS)."
                and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                and kdPekerjaan=".$this->mbgs->_valforQuery( $this->qtbl['p-pend']['nm'].$kdPekerjaan).";
                
                update pekerjaansub set ins=now()
                where kdPS>".$this->mbgs->_valforQuery($kdPS)."
                and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                and kdPekerjaan=".$this->mbgs->_valforQuery( $this->qtbl['p-pend']['nm'].$kdPekerjaan)."
            ";
            // return print_r($q);
            $check=$this->qexec->_multiProc($q);
            if($check){
                $this->_['ddata']=$this->qexec->_func(
                    _dpekerjaanSub(
                        $this->kdKantor,
                        $kdPekerjaan,
                        $kdProduk,
                        $kdSub,
                        " "
                    )
                );
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
        
    }
    function tutupPekerjaan(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-pend",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            // return print_r($baseEND);
            $kdPekerjaan=$baseEND->{'kdPekerjaan'};
            $kdTahapan  =$baseEND->{'kdTahapan'};
            $kdProduk   =$baseEND->{'kdProduk'};
            $kdSub      =$baseEND->{'kdSub'};

            $total      =$baseEND->{'total'};
            $bayar      =$baseEND->{'bayar'};
            $sisa      =$baseEND->{'sisa'};

            $kdPS       =$baseEND->{'kdPS'};
            
            $q="
                update pekerjaan set final=1,
                    tglFinal=now(),
                    total=".$this->mbgs->_valforQuery($total).",
                    bayar=".$this->mbgs->_valforQuery($bayar).",
                    sisa=".$this->mbgs->_valforQuery($sisa)." 
                where kdPekerjaan='".$kdPekerjaan."';
                update pekerjaansub set arsip=1,ins=now()
                where kdPS=".$this->mbgs->_valforQuery($kdPS)."
                and kdKantor=".$this->mbgs->_valforQuery($this->kdKantor)."
                and kdPekerjaan=".$this->mbgs->_valforQuery( $this->qtbl['p-pend']['nm'].$kdPekerjaan).";
            ";
            // return print_r($q);
            $check=$this->qexec->_multiProc($q);
            if($check){
                $this->_['ddata']=$this->qexec->_func(
                    _dpekerjaanSub(
                        $this->kdKantor,
                        $kdPekerjaan,
                        $kdProduk,
                        $kdSub,
                        " "
                    )
                );
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
        
    }
    function inpSlider(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("c-sett",false));
        if($portal['exec']){
    
            $file=$_POST['file'];
            $namaFile="";
            if(!empty($file)){
                // return print_r($file['data']);
                foreach ($file as $key => $v) {
                    if($key>0){
                        $namaFile.="/";
                    }
                    // $namaFile.=$this->_uploadImage($v['src'],$v['nama'])."<2G18>";
                    $namaFile.=$this->_uploadImage($v['src'],"fs_sistem/slider/".$v['nama']);
                }
            }
            // return print_r($namaFile);
            $split=explode("/",$namaFile);
            if(count($split)>1){
                $q=" INSERT INTO slider (img) VALUES ";
                foreach($split as $key =>$v){
                    $q.="('".$v."'),";  
                };
                $q=substr($q,0,strlen($q)-1);
                // return print_r($q);
                $check=$this->qexec->_multiProc($q);
            }else{
                $check=$this->qexec->_proc("
                    INSERT INTO slider (img) VALUES 
                        (
                            '".$namaFile."'
                        )
                    ");
            }
            // return print_r($check);
            if($check){
                $this->_['slider']     =$this->qexec->_func("SELECT * FROM slider");
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }
        return $this->mgbs->resFalse($portal['msg']);
    }
    function delSlider(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-sett",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
            
            $id       =$baseEND->{'id'};
            $check=$this->qexec->_proc("delete from slider where id=".$id);
            if($check){
                $this->_['slider']     =$this->qexec->_func("SELECT * FROM slider");
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mgbs->resFalse($portal['msg']);
    }

    function mengertiInfo(){
        $portal=$this->_keamanan($_POST['code'],$this->mbgs->_getNKA("p-usul"));
        if($portal['exec']){
            $check=$this->qexec->_proc($this->mbgs->_updDateInformasiDimengerti($this->kdMember,""));
            if($check){
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);

    }

    function _settingKeyMember($member,$kodePage,$kunci){
        // $kodePage=;
        $q="";
        foreach ($member as $key => $v) {
            $q.=" update appkey set 
                    kunci=".$kunci."
                where kdMember=".$this->mbgs->_valforQuery($v['kdMember'])." and 
                    kdFitur!=".$this->mbgs->_valforQuery($kodePage)." and
                    kdFitur like '%".explode("/",$kodePage)[0]."%';";
        }
        return $q;
    }
    public function _uploadFiles($file,$nama){
        $pdf_decoded = base64_decode($file,true);
        $nama=explode(".",$nama);
        date_default_timezone_set("America/New_York");
        $namaFile=$nama[count($nama)-2]."-".date("Y-m-d-h-i-sa").".".$nama[count($nama)-1];
        $lokasiFile='./assets/fs_sistem/upload/files/'.$namaFile;
        file_put_contents($lokasiFile, $pdf_decoded);
        return substr($lokasiFile,2);
    }

    public function _setNotification($fitur,$info,$nmBtn,$tingkatJabatan){
        $keyTabel="kdNotif";
        $kdTabel=$this->qexec->_func("
            select ".$keyTabel." 
            from notif
            ORDER BY ".$keyTabel." DESC limit 1"
        );
        if(count($kdTabel)>0){
            $kdTabel=$kdTabel[0][$keyTabel]+1;
        }else{
            $kdTabel=1;
        }

        $qNotif=" INSERT INTO notif
                    (kdNotif,fitur, isiNotif, nmTombol, url)
                VALUES 
                    (
                        ".$this->mbgs->_valforQuery($kdTabel).",
                        ".$this->mbgs->_valforQuery($fitur).",
                        ".$this->mbgs->_valforQuery($info).",
                        ".$this->mbgs->_valforQuery($nmBtn).",
                        ".$this->mbgs->_valforQuery($this->mbgs->_getUrl($fitur))."
                    );";
        $qNotif.=" INSERT INTO notifuser(kdMember, kdNotif) (".$this->mbgs->_dmemberSetingkat($tingkatJabatan,$kdTabel).")"; // tingkat 3 bisa dicek di tabel jabatan kolom setingkat

        return $this->qexec->_multiProc($qNotif);
    }
    function refreshHakAkses(){
        $portal=$this->_keamanan($_POST['code'],_getNKA("d-memb",false));
        if($portal['exec']){
            $baseEND=json_decode((base64_decode($_POST['data'])));
        
            $kdJabatan  =$baseEND->{'kdJabatan'};
            $kdMember   =$baseEND->{'kdMember'};
            $a=array();
            $a['kdMember']=$kdMember;
            $a['kdJabatan']=substr($kdJabatan,strlen($kdJabatan)-1);
            // return print_r($a);
            $check=$this->addKeySistemPaksa(base64_encode(json_encode($a)));
            if($check){
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }return $this->mbgs->resFalse($portal['msg']);
    }
    public function _uploadImage($file,$nama){
        $split=explode("/",$nama); 
        $flokasi="fs_sistem/upload/image/";// default foldar jika ber ubah maka tambahakan dinamanya
        if(count($split)>1){
            $flokasi='';
            foreach ($split as $key => $v) {
                if($key==count($split)-1){
                    $nama=$v;
                }else{
                    $flokasi.=$v."/";
                }
            }
            // $flokasi.=$split[0]."/";
            // $nama=$split[count($split)-1];
        }
        $nama=explode(".",$nama);
        switch($nama[count($nama)-1]){
            case "png":$image=substr($file,22);break;
            case "PNG":$image=substr($file,22);break;
            case "pdf":$image=substr($file,22);break;
            default:$image=substr($file,23);break;
        }
        // $image=substr($file,23);
        // return print_r($nama[1]);
        date_default_timezone_set("America/New_York");
        $namaFile=$nama[count($nama)-2]."-".date("Y-m-d-h-i-sa").".".$nama[count($nama)-1];

        
        $delspace=explode(" ",$namaFile);
        $namaFile="";
        foreach ($delspace as $key => $value) {
            $namaFile.=$value;
        }
        $lokasiFile='./assets/'.$flokasi.$namaFile;
        write_file($lokasiFile,base64_decode($image));
        return $namaFile;
    }
    function _checkKeyApp($keyForm,$kdMember){
        $kodeForm=false;
        $kodeForm=$keyForm;
        // return print_r($this->mbgs->_qCekKey($kodeForm,$kdMember));
        $q=$this->mbgs->_qCekKey($kodeForm,$kdMember);
        $member=$this->qexec->_func($q);
        // return count($member);
        if(count($member)==1){
            return true;
        }
        return false;
    }
    function _keamanan($code,$codeForm){
        $res=$this->mbgs->_getAllFile("/fs_sistem/session");
        $data="";
        foreach ($res as $key => $value) {
            $exp=explode($this->mbgs->_getIpClient()."=",$value['nama']);
            if(count($exp)>1){
                $data=$this->mbgs->_readFileTxt($value['file']);
            }
        }
        if(strlen($data)==0){
            return $this->mbgs->resF("session");
        }
        $data=json_decode($data);
        $session=array(
            'kdMember'=>$data->{'kdMember'},
            'nmMember'=>$data->{'nmMember'},
            'kdJabatan'=>$data->{'kdJabatan'},
            'kdKantor'=>$data->{'kdKantor'},
            'nmKantor'=>$data->{'nmKantor'},
            'username'=>$data->{'username'},
            'password'=>$data->{'password'},
        );
        $this->sess->set_userdata($session);

        $this->kdMember=$this->sess->kdMember;
        $this->kdMember1=$this->sess->kdMember1;
        $this->nmMember=$this->sess->nmMember;
        $this->kdJabatan=$this->sess->kdJabatan;
        $this->kdKantor=$this->sess->kdKantor;
        $this->nmKantor=$this->sess->nmKantor;
        $kdMember=$this->sess->kdMember;
        if($kdMember==null) {
            return $this->mbgs->resF("can't access !!!");
        }
        
        if(!$this->mbgs->_backCodes(base64_decode($code))){
            return $this->mbgs->resF("Tidak Sesuai Keamanan Sistem !!!");
        }
        if($this->_checkKeyApp($codeForm,$kdMember)==0){
            return $this->mbgs->resF("Anda tidak memiliki ijin !!!");
        }
        return $this->mbgs->resT("");
    }
    function addKeySistem($val){
        // $a=array();
        // $a['kdMember']="2G18-memb-1";
        // $a['kdJabatan']="6";
        // return print_r(base64_encode(json_encode($a)));
        // eyJrZE1lbWJlciI6IjJHMTgtbWVtYi0xIiwia2RKYWJhdGFuIjoiNiJ9

        $baseEND=json_decode((base64_decode($val)));
        // return print_r($baseEND);
        $kdMember=$baseEND->{'kdMember'};
        $kdJabatan=$baseEND->{'kdJabatan'};

        $nmApp=$this->qexec->_func("select * from app where kdApp='".$this->mbgs->app['kd']."'");
        $q="";
        if(count($nmApp)==0){
            $q.=" INSERT INTO app(kdApp,nmApp) VALUES ('".$this->mbgs->app['kd']."','".$this->mbgs->app['nm']."');";
        }


        $fitur=$this->qexec->_func("select * from appfitur where kdApp='".$this->mbgs->app['kd']."'");
        $fiturSystem=_getNKA("",true);
        // return $this->mbgs->_log($q);
        if(count($fitur)!=count($fiturSystem)){
            $q.=" delete from appfitur where kdApp='".$this->mbgs->app['kd']."';";
            $q.=" INSERT INTO appfitur(kdApp, kdFitur, nmFitur) VALUES ";
            foreach ($fiturSystem as $key => $v) {
                $q.="(
                        ".$this->mbgs->_valforQuery($this->mbgs->app['kd']).",
                        ".$this->mbgs->_valforQuery($v['kd']).",
                        ".$this->mbgs->_valforQuery($v['page'])."
                    ),";
            }
        }
        if(strlen($q)>0){
            $q=substr($q,0,strlen($q)-1).";";
        }
        
        $kunci=$this->qexec->_func("select * from appkey where kdMember=".$this->mbgs->_valforQuery($kdMember)."");
        if(count($kunci)!=count($fiturSystem)){
            $q.=" delete from appkey where kdMember=".$this->mbgs->_valforQuery($kdMember).";";
            $q.=" INSERT INTO appkey(kdApp,kdMember, kdFitur, Kunci) VALUES ";
            foreach ($fiturSystem as $key => $v) {
                foreach($v['kdJabatan'] as $key1 => $v1){
                    // print_r($v1."|".$kdJabatan."<br>");
                    if($v1==$kdJabatan){
                        $q.="('".$this->mbgs->app['kd']."',".$this->mbgs->_valforQuery($kdMember).",".$this->mbgs->_valforQuery($v['kd']).",'0'),";
                    }
                }
            }
            $q=substr($q,0,strlen($q)-1);
        }
        if(strlen($q)==0){
            return true;
        }
        // return $this->mbgs->_log($q);
        $check=$this->qexec->_multiProc($q);
        if($check){
            return true;
        }
        return false;
        // print_r("sukses");
    }
    function addKeySistemPaksa($val){
        // $a=array();
        // $a['kdMember']="2G18-memb-1";
        // $a['kdJabatan']="5";
        // return print_r(base64_encode(json_encode($a)));
        // eyJrZE1lbWJlciI6IjJHMTgtbWVtYi0xIiwia2RKYWJhdGFuIjoiNSJ9
        // eyJrZE1lbWJlciI6IjJHMTgtbWVtYi05Iiwia2RKYWJhdGFuIjoiMSJ9

        $baseEND=json_decode((base64_decode($val)));
        // return print_r($baseEND);
        $kdMember=$baseEND->{'kdMember'};
        $kdJabatan=$baseEND->{'kdJabatan'};

        $nmApp=$this->qexec->_func("select * from app where kdApp='".$this->mbgs->app['kd']."'");
        $q="";
        if(count($nmApp)==0){
            $q.=" INSERT INTO app(kdApp,nmApp) VALUES ('".$this->mbgs->app['kd']."','".$this->mbgs->app['nm']."');";
        }


        $fitur=$this->qexec->_func("select * from appfitur where kdApp='".$this->mbgs->app['kd']."'");
        $fiturSystem=_getNKA("",true);
        if(count($fitur)!=count($fiturSystem)){
            $q.=" delete from appfitur where kdApp='".$this->mbgs->app['kd']."';";
            $q.=" INSERT INTO appfitur(kdApp, kdFitur, nmFitur) VALUES ";
            foreach ($fiturSystem as $key => $v) {
                $q.="(
                        ".$this->mbgs->_valforQuery($this->mbgs->app['kd']).",
                        ".$this->mbgs->_valforQuery($v['kd']).",
                        ".$this->mbgs->_valforQuery($v['page'])."
                    ),";
            }
        }
        if(strlen($q)>0){
            $q=substr($q,0,strlen($q)-1).";";
        }
        $kunci=$this->qexec->_func("select * from appkey where kdMember=".$this->mbgs->_valforQuery($kdMember)."");
        $q.=" delete from appkey where kdMember=".$this->mbgs->_valforQuery($kdMember).";";
        $q.=" INSERT INTO appkey(kdApp,kdMember, kdFitur, Kunci) VALUES ";
        foreach ($fiturSystem as $key => $v) {
            foreach($v['kdJabatan'] as $key1 => $v1){
                if($v1==$kdJabatan){
                    $q.="('".$this->mbgs->app['kd']."',".$this->mbgs->_valforQuery($kdMember).",".$this->mbgs->_valforQuery($v['kd']).",'0'),";
                }
            }
        }
        $q=substr($q,0,strlen($q)-1);
        return $this->qexec->_multiProc($q);
    }
}


