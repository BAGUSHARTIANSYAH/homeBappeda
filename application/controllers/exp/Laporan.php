<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {
    var $assert="";
    private $bgs;
    function __construct(){
        parent::__construct();
        $lbgs=new LibBGS();
        $this->startLokal();
    }
    function startLokal(){
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
    }
    function previewFile($val){
        $baseEND=json_decode((base64_decode($val)));
        $files   =$baseEND->{'files'};
        // return print_r($files);
        $this->lbgs->previewPdf(base_url().$files);
    }
    function notaris($val){
        $baseEND=json_decode((base64_decode($val)));
        $html='';
        if(!$baseEND->{'hasil'}){
            $html=$this->laporanPekerjaan($baseEND);
        }else{
            $html=$this->laporanHasilPekerjaan($baseEND);
        }
        // return print_r($baseEND);
        // return print_r($baseEND);
        // $dlaporan=$this->getDataLaporan($baseEND);
        return $this->lbgs->cetakTC($html);
    }
    function laporanPekerjaan($baseEND){
        $data=$this->qexec->_func(_dpekerjaan($this->sess->kdKantor," and a.final=0 "));
        // return _log($data);
        $dlaporan=array();
        array_push($dlaporan,[
            "ORIENTATION"	=>"L",
            "FORMAT"		=>"a4",
            "name"			=>"rese",
            // "preview"       =>true,
            "preview"       =>false,
            "html"          =>_headerLapiran("DAFTAR PEKERJAAN")
                            ._laporanPekerjaan($data)
        ]);
        return $dlaporan;
    }
    function laporanHasilPekerjaan($baseEND){
        // return print_r($baseEND);
        $data=$this->qexec->_func(_dpekerjaan($this->sess->kdKantor," and a.final=1 "));
        $dlaporan=array();
        array_push($dlaporan,[
            "ORIENTATION"	=>"L",
            "FORMAT"		=>"a4",
            "name"			=>"rese",
            // "preview"       =>true,
            "preview"       =>false,
            "html"          =>_headerLapiran2("DAFTAR HASIL PEKERJAAN",$baseEND->{'tglS'},$baseEND->{'tglE'})
                            ._laporanHasil($data)
        ]);
        return $dlaporan;
    }
}