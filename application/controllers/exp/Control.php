<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control extends CI_Controller {
    function __construct(){
        parent::__construct();
    
        $this->mbgs->_setBaseUrl(base_url());
        $_=array();
        $this->_['assert']=$this->mbgs->_getAssetUrl();
        $this->_['code']=$this->mbgs->_backCode($this->enc->encrypt($this->mbgs->_isCode()));
        $this->_['param']=null;
        $this->_['qlogin']=true;
    }
	public function index(){
        $this->_['qlogin']=false;
        if($this->sess->kdMember!=null) {
            return redirect("control/dashboard/null");
        }
        $this->_['page']="beranda";
        $this->_['html']=$this->mbgs->_html($this->_);
		$this->load->view('index',$this->_);
    }
    public function dashboard($val){
        // return print_r($this->_['code']);
        if($val!=null && $val!="null"){
            $baseEND=json_decode((base64_decode($val)));
            
            $username   =$baseEND->{'username'};
            $password   =$baseEND->{'password'};

            $q="
                select 
                    a.*,b.nmKantor
                from member a 
                join kantor b on
                    a.kdKantor=b.kdKantor1
                where 
                    UPPER(a.username)=UPPER('".$username."') and 
                    UPPER(a.password)=UPPER('".$password."')";
            $member=$this->qexec->_func($q);
            $sess=array(
                'kdMember'=>$member[0]['kdMember1'],
                'nmMember'=>$member[0]['nmMember'],
                'kdJabatan'=>substr($member[0]['kdJabatan'],5),
                'kdKantor'=>$member[0]['kdKantor'],
                'nmKantor'=>$member[0]['nmKantor'],
                'username'=>$member[0]['username'],
                'password'=>$member[0]['password'],
            );
            
            $res=$this->mbgs->_getAllFile("/fs_sistem/session");
            $this->mbgs->_removeFile($res,$this->mbgs->_getIpClient()."=");
            
            // return print_r($_SERVER);
            $this->mbgs->_expTxt($this->mbgs->_getIpClient()."=",json_encode($sess));
            // sess
            $this->sess->set_userdata($sess);
            $nama=$member[0]['nmMember'];
        }else{
            $this->_keamanan("Bagus H");
            if($this->sess->kdMember==null) {
                return $this->logout();
            }
            $nama=$this->sess->nmMember;
        }
        $this->_['page']="dashboard";
        $this->_['html']=$this->mbgs->_html($this->_);
		$this->load->view('index',$this->_);
    }
    public function kantor(){
        $portal=$this->_keamanan(_getNKA("p-kant",false));
        if($portal['exec']){
            $this->_['page']="kantor";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }
    public function jabatan(){
        $portal=$this->_keamanan(_getNKA("p-jaba",false));
        if($portal['exec']){
            $this->_['page']="jabatan";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }
    public function akun(){
        $portal=$this->_keamanan(_getNKA("p-memb",false));
        if($portal['exec']){
            $this->_['page']="member";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }

    public function produk(){
        $portal=$this->_keamanan(_getNKA("p-prod",false));
        if($portal['exec']){
            $this->_['page']="produk";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }
    public function subProduk(){
        $portal=$this->_keamanan(_getNKA("p-spro",false));
        // return print_r($portal);
        if($portal['exec']){
            $this->_['page']="subProduk";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }
    public function tahapan(){
        $portal=$this->_keamanan(_getNKA("p-taha",false));
        // return print_r($portal);
        if($portal['exec']){
            $this->_['page']="tahapan";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
        
    }
    public function persyaratan(){
        $portal=$this->_keamanan(_getNKA("p-pers",false));
        if($portal['exec']){
            $this->_['page']="persyaratan";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }
    function settahapan(){
        $portal=$this->_keamanan(_getNKA("p-taha",false));
        if($portal['exec']){
            $this->_['page']="settahapan";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
        
    }
    function setsarat(){
        $portal=$this->_keamanan(_getNKA("p-pers",false));
        if($portal['exec']){
            $this->_['page']="setsarat";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }
    public function pendaftaran(){
        $portal=$this->_keamanan(_getNKA("p-pend",false));
        if($portal['exec']){
            $this->_['page']="pendaftaran";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }
    public function subPekerjaan($p){
        $portal=$this->_keamanan(_getNKA("p-pend",false));
        // return print_r($portal);
        if($portal['exec']){
            $this->_['page']="subPekerjaan";
            $this->_['param']=$p;
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
        
    }
    public function hasilPekerjaan($p){
        $portal=$this->_keamanan(_getNKA("p-pend",false));
        if($portal['exec']){
            $this->_['page']="hasilPekerjaan";
            $this->_['param']=$p;
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
    }
    public function laporan(){
        $this->_['page']="laporan";
        $this->_['html']=$this->mbgs->_html($this->_);
		$this->load->view('index',$this->_);
    }
    public function logout(){
        $res=$this->mbgs->_getAllFile("/fs_sistem/session");
        // return print_r($res);
        $this->mbgs->_removeFile($res,$this->mbgs->_getIpClient()."=");
        $this->sess->sess_destroy();
        return redirect("control");
    }
    function setting(){
        $portal=$this->_keamanan(_getNKA("p-sett",false));
        if($portal['exec']){
            $this->_['page']="setting";
            $this->_['html']=$this->mbgs->_html($this->_);
            $this->load->view('index',$this->_);
        }else{
            if($portal['msg']=="session"){
                return $this->logout();
            }else{
                return $this->dashboard("null");
            }
        }
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
    function _keamanan($codeForm){
        // del jika dia dionline kan
        // $this->sess->set_userdata($sess);
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

        $kdMember=$this->sess->kdMember;
        if($kdMember==null) {
            return $this->mbgs->resF("sess");
        }
        if($this->_checkKeyApp($codeForm,$kdMember)==0){
            return $this->mbgs->resF("keyForm");
        }
        return $this->mbgs->resT("");
    }
    function addKeySistem($val){
        // $a=array();
        $kdMember="2G18-memb-1";
        $kdJabatan="4";
        // return print_r(base64_encode(json_encode($a)));
        // eyJrZE1lbWJlciI6IjJHMTgtbWVtYi0xIiwia2RKYWJhdGFuIjoiNiJ9

        // $baseEND=json_decode((base64_decode($val)));
        // $kdMember=$baseEND->{'kdMember'};
        // $kdJabatan=$baseEND->{'kdJabatan'};
        // return print_r($this->mbgs->app)

        $nmApp=$this->qexec->_func("select * from app where kdApp='".$this->mbgs->app['kd']."'");
        $q="";
        if(count($nmApp)==0){
            $q.=" INSERT INTO app(kdApp,nmApp) VALUES ('".$this->mbgs->app['kd']."','".$this->mbgs->app['nm']."');";
        }


        $fitur=$this->qexec->_func("select * from appfitur where kdApp='".$this->mbgs->app['kd']."'");
        $fiturSystem=_getNKA("",true);
        // print_r($fitur);
        if(count($fitur)!=count($fiturSystem)){
            // print_r($fiturSystem);
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
        // print_r($q);
        $kunci=$this->qexec->_func("select * from appkey where kdMember=".$this->mbgs->_valforQuery($kdMember)."");
        if(count($kunci)!=count($fiturSystem)){
            $q.=" delete from appkey where kdMember=".$this->mbgs->_valforQuery($kdMember).";";
            $q.=" INSERT INTO appkey(kdApp,kdMember, kdFitur, Kunci) VALUES ";
            foreach ($fiturSystem as $key => $v) {
                foreach($v['kdJabatan'] as $key1 => $v1){
                    if($v1==$kdJabatan){
                        $q.="('".$this->mbgs->kdApp."',".$this->mbgs->_valforQuery($kdMember).",".$this->mbgs->_valforQuery($v['kd']).",'0'),";
                    }
                }
            }
            $q=substr($q,0,strlen($q)-1);
        }
        if(strlen($q)==0){
            return print_r("Data Key Sudah Sesuai");
        }
        // return print_r($q);
        $this->qexec->_multiProc($q);
        print_r("sukses");
    }
}
