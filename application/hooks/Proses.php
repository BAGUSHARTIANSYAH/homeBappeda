<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// sess enc mbgs lbgs qexec
class Proses extends CI_Controller {
    private $bgs;
    function __construct(){
        parent::__construct();
        $this->_=array();
        
        
        $this->kd=$this->sess->kdMember;
        $this->nm=$this->sess->nama;
        $this->kdJabatan=$this->sess->kdMemberJabatan;
        $this->kdDinas=$this->sess->kdMemberDinas;
        $this->tahun=$this->sess->tahun;

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

            $kdDinas   =$baseEND->{'kdDinas'};
            $password   =$baseEND->{'password'};
            $username   =$baseEND->{'username'};
            $kondisi=true;
        }else{
            $password   =$this->sess->password;
            $username   =$this->sess->nama;
            $kdDinas   =$this->sess->kdMemberDinas;
        }
        $q="select * from member where kodeDinas='".$kdDinas."' and UPPER(nama)=UPPER('".$username."') and UPPER(password)=UPPER('".$password."')";
        // return print_r($q);
        $member=$this->qexec->_func($q);
        if(count($member)==1){
            // if($kondisi){//for login awal no sess
            //     if(substr($member[0]['kdJabatan'],5)<3){
            //         $pembahasan=$this->qexec->_func("SELECT tahun,noPembahasan,progres,finals,files FROM pembahasan ORDER by ins desc limit 1");
            //         if(count($pembahasan)==0){
            //             return $this->mbgs->resFalse("Sistem Tidak Dapat Digunakan ");
            //         }
            //     }
            //     return $this->mbgs->resTrue("Sukses");
            // }
            return $this->mbgs->resTrue("Sukses");
        }else{
            if($kondisi){//for login awal no sess
                return $this->mbgs->resFalse("user tidak dapat ditemukan !!!");
            }
        }
        print_r(json_encode($this->_));
    }
    public function getRenstraOpd(){
        $kondisi=false;
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }else{
            $baseEND=json_decode((base64_decode($_POST['data'])));
            $kdDinas   =$baseEND->{'kdDinas'};
            
            $this->_['data']=$this->qexec->_func(_renstraOpdGet($kdDinas,$this->tahun,""));
            $this->_['tsub']=$this->qexec->_func(_tsub($kdDinas,$this->tahun,""))[0]['total'];
            $this->_['tsubProses']=$this->qexec->_func(_tsubProses($kdDinas,$this->tahun,""));
            if(count($this->_['tsubProses'])==0){
                $this->_['tsubProses']=0;
            }
            $this->_['tpaguPra']=$this->qexec->_func(_tpagu($kdDinas,"1",$this->tahun,""))[0]['total'];
            $this->_['tpaguRka']=$this->qexec->_func(_tpagu($kdDinas,"2",$this->tahun,""))[0]['total'];
            $this->_['tpaguFinal']=$this->qexec->_func(_tpagu($kdDinas,"3",$this->tahun,""))[0]['total'];
            return $this->mbgs->resTrue($this->_);
        }
    }
    public function saveRenstraOpd(){
        $kondisi=false;
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }else{
            $baseEND=json_decode((base64_decode($_POST['data'])));
            $kdDinas   =$baseEND->{'kdDinas'};
            $data       =$baseEND->{'data'};

            // return print_r($data[0]->act);
            $qadd='INSERT INTO `psub`(`kdSub`, `kdKeg`, `kdDinas`, `nmSub`, `taSub`) VALUES ';
            $qdel='';
            $kondisi=true;
            foreach ($data as $key => $v) {
                if($v->act){
                    $kondisi=false;
                    $qadd.='(
                        '.$this->mbgs->_valforQuery($v->kdSub).','.$this->mbgs->_valforQuery($v->kdKeg).',
                        '.$this->mbgs->_valforQuery($kdDinas).','.$this->mbgs->_valforQuery($v->nmSub).',
                        '.$this->mbgs->_valforQuery($this->tahun).'
                    ),';
                }else{
                    $qdel.='DELETE FROM `psub` WHERE 
                            kdSub='.$this->mbgs->_valforQuery($v->kdSub).' and
                            kdKeg='.$this->mbgs->_valforQuery($v->kdKeg).' and
                            kdDinas='.$this->mbgs->_valforQuery($kdDinas).' and
                            taSub='.$this->mbgs->_valforQuery($this->tahun).';
                    ';
                }
            }
            $qadd=substr($qadd,0,strlen($qadd)-1).";";
            if($kondisi){
                $qadd='';
            }
            $check=$this->qexec->_multiProc($qadd.$qdel);
            if($check){
                $this->_['data']=$this->qexec->_func(_renstraOpdGet($kdDinas,$this->tahun,""));
                $this->_['tsub']=$this->qexec->_func(_tsub($kdDinas,$this->tahun,""))[0]['total'];
                $this->_['tsubProses']=$this->qexec->_func(_tsubProses($kdDinas,$this->tahun,""));
                if(count($this->_['tsubProses'])==0){
                    $this->_['tsubProses']=0;
                }
                $this->_['tpaguPra']=$this->qexec->_func(_tpagu($kdDinas,"1",$this->tahun,""))[0]['total'];
                $this->_['tpaguRka']=$this->qexec->_func(_tpagu($kdDinas,"2",$this->tahun,""))[0]['total'];
                $this->_['tpaguFinal']=$this->qexec->_func(_tpagu($kdDinas,"3",$this->tahun,""))[0]['total'];
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
            }
        }
    }

    public function getRenstraRealOpd(){
        $kondisi=false;
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }else{
            $baseEND=json_decode((base64_decode($_POST['data'])));
            $kdDinas   =$baseEND->{'kdDinas'};
            
            $this->_['data']=$this->qexec->_func(_renstraOpd($kdDinas,$this->tahun,""));
            $this->_['tsub']=$this->qexec->_func(_tsub($kdDinas,$this->tahun,""))[0]['total'];
            $this->_['tsubProses']=$this->qexec->_func(_tsubProses($kdDinas,$this->tahun,""));
            if(count($this->_['tsubProses'])==0){
                $this->_['tsubProses']=0;
            }
            $this->_['tpaguPra']=$this->qexec->_func(_tpagu($kdDinas,"1",$this->tahun,""))[0]['total'];
            $this->_['tpaguRka']=$this->qexec->_func(_tpagu($kdDinas,"2",$this->tahun,""))[0]['total'];
            $this->_['tpaguFinal']=$this->qexec->_func(_tpagu($kdDinas,"3",$this->tahun,""))[0]['total'];
            return $this->mbgs->resTrue($this->_);
        }
    }
    public function getSubOpd(){
        $kondisi=false;
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }else{
            $baseEND=json_decode((base64_decode($_POST['data'])));
            $kdDinas   =$baseEND->{'kdDinas'};
            
            $this->_['data']=$this->qexec->_func(_keySub($kdDinas,$this->tahun,""));
            return $this->mbgs->resTrue($this->_);
        }
    }
    
    //budgeting
    public function ebOnOFfKey(){
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }else{
            $baseEND=json_decode((base64_decode($_POST['data'])));
            $query   =$baseEND->{'query'};
            $kdDinas   =$baseEND->{'kdDinas'};
            
            $check=$this->qexec->_proc($query);
            if($check){
                $this->_['data']=$this->qexec->_func(_keySub($kdDinas,$this->tahun,""));
                return $this->mbgs->resTrue($this->_);
            }else{
                return $this->mbgs->resFalse("Terjadi Kesalahan dalam proses perubahan!!!");
            }
        }
    }

    

    function insMember(){
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }
        // $portal=$this->_keamanan($_POST['code'],_getNKA("c-prod",false));
        // if($portal['exec']){
        $baseEND=json_decode((base64_decode($_POST['data'])));
    
        $kdJabatan   =$baseEND->{'kdJabatan'};
        $username    =$baseEND->{'username'};
        $password    =$baseEND->{'password'};
        $nik         =$baseEND->{'nik'};
        $email       =$baseEND->{'email'};
        $kdDinas     =$baseEND->{'kdDinas'};
        
        $keyTabel="kdMember";
        $kdTabel=$this->qexec->_func("select ".$keyTabel." from member ORDER BY cast(".$keyTabel." as int) DESC limit 1");
        if(count($kdTabel)>0){
            $kdTabel=$kdTabel[0][$keyTabel]+1;
        }else{
            $kdTabel=1;
        }

        $kdMemberx=$this->mbgs->app['unik'].$this->qtbl['p-memb']['nm'].$kdTabel;
        
        $q="INSERT INTO member(kdMember,kdMember1, kdDinas, nmMember, nik, password, email,kdJabatan) VALUES(
            ".$this->mbgs->_valforQuery($kdTabel).",".$this->mbgs->_valforQuery($kdMemberx).",
            ".$this->mbgs->_valforQuery($kdDinas).",".$this->mbgs->_valforQuery($username).",
            ".$this->mbgs->_valforQuery($nik).",".$this->mbgs->_valforQuery($password).",
            ".$this->mbgs->_valforQuery($email).",".$this->mbgs->_valforQuery($kdJabatan)."
        )";

        // return print_r($q);
        $check=$this->qexec->_proc($q);
        if($check){
            $this->_['member']=$this->qexec->_func(_member(""));
            return $this->mbgs->resTrue($this->_);
        }else{
            return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
        }
        // }return $this->mbgs->resFalse($portal['msg']);
    }
    function updMember(){
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }
        $baseEND=json_decode((base64_decode($_POST['data'])));
    
        $kdJabatan   =$baseEND->{'kdJabatan'};
        $username    =$baseEND->{'username'};
        $password    =$baseEND->{'password'};
        $nik         =$baseEND->{'nik'};
        $email       =$baseEND->{'email'};
        $kdDinas     =$baseEND->{'kdDinas'};
        $kdMember     =$baseEND->{'kdMember'};
        
        $q="
            update member set
                kdDinas=".$this->mbgs->_valforQuery($kdDinas).",
                nmMember=".$this->mbgs->_valforQuery($username).",
                nik=".$this->mbgs->_valforQuery($nik).",
                password=".$this->mbgs->_valforQuery($password).",
                email=".$this->mbgs->_valforQuery($email).",
                kdJabatan=".$this->mbgs->_valforQuery($kdJabatan)."
            where kdMember='".$kdMember."'
        ";
        // return print_r($q);
        $check=$this->qexec->_proc($q);
        // $check=true;
        if($check){

            $this->_['member']=$this->qexec->_func(_member(""));
            return $this->mbgs->resTrue($this->_);
        }else{
            return $this->mbgs->resFalse("Terjadi Kesalahan saat Penyimpanan data");
        }
        // $q=substr($judul,0,strlen(trim($judul))-1).";";
        // $q.=substr($rincian,0,strlen(trim($rincian))-1);

        
    }
    function delMember(){
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }
        $baseEND=json_decode((base64_decode($_POST['data'])));
        
        $kdMember     =$baseEND->{'kdMember'};

        $q="
            delete from member 
            where kdMember=".$this->mbgs->_valforQuery($kdMember)."
        ";
        $check=$this->qexec->_multiProc($q);
        if($check){
            
            $this->_['member']=$this->qexec->_func(_member(""));
            return $this->mbgs->resTrue($this->_);
        }else{
            return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
        }
    }

    function saveAdminGroup(){
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }
        $baseEND=json_decode((base64_decode($_POST['data'])));
        $query   =$baseEND->{'query'};
        $member =$baseEND->{'member'};
        $check=$this->qexec->_multiProc($query);
        if($check){
            $this->_['dinas']=$this->qexec->_func(_cbDinasForAG($member," where taDinas='".$this->tahun."'"));
            return $this->mbgs->resTrue($this->_);
        }else{
            return $this->mbgs->resFalse("Terjadi Kesalahan di penyimpanan sistem");
        }
        // }return $this->mbgs->resFalse($portal['msg']);
    }
    function getDinasAdminGroup(){
        if($this->sess->kdMember==null){
            return $this->mbgs->resFalse("maaf, Pengguna tidak terdeteksi !!!");
        }
        $baseEND=json_decode((base64_decode($_POST['data'])));
        $member =$baseEND->{'member'};
        $this->_['dinas']=$this->qexec->_func(_cbDinasForAG($member," where taDinas='".$this->tahun."'"));
        return $this->mbgs->resTrue($this->_);
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

        $this->kdMember=$this->sess->kdMemberMember;
        $this->kdMember1=$this->sess->kdMemberMember1;
        $this->nmMember=$this->sess->nmMember;
        $this->kdJabatan=$this->sess->kdMemberJabatan;
        $this->kdKantor=$this->sess->kdMemberKantor;
        $this->nmKantor=$this->sess->nmKantor;
        $kdMember=$this->sess->kdMemberMember;
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


