<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WsKomponen extends CI_Controller {
    function __construct(){
        parent::__construct();	
        // $this->load->helper('url','html_helper','mbgs_helper');

        $this->mbgs->_setBaseUrl(base_url());
        
        $_=array();
        $this->_['code']=$this->mbgs->_backCode($this->enc->encrypt($this->mbgs->_isCode()));
        $this->_=array_merge($this->_,$this->mbgs->_getBasisData());
        $this->_['footer']    =$this->mbgs->_getJs();

        // $this->kdMember=$this->sess->kdMember;
        // $this->kdMember1=$this->sess->kdMember1;
        // $this->nmMember=$this->sess->nmMember;
        // $this->kdJabatan=$this->sess->kdJabatan;
        // $this->kdKantor=$this->sess->kdKantor;
        // $this->nmKantor=$this->sess->nmKantor;

        $this->qtbl=_getNKA("c-prod",true);
        // $username=$this->sess->username;
        // $password=$this->sess->password;
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
    function beranda($page){
        $this->_['head']      =$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['slider']      =$this->qexec->_func("select * from slider order by id asc");
        return print_r(json_encode($this->_));
    }
    function dashboard($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>0,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_dinfo();

        $this->_['footer'].=$this->mbgs->_getJsChart();
        // return $this->mbgs->_log($this->_['ddata']);
        return print_r(json_encode($this->_));
    }

    function kantor($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>1,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();
        $this->_['ddata']=$this->qexec->_func(_dkantor(""));

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function jabatan($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>1,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();
        $this->_['ddata']=$this->qexec->_func(_djabatan(""));

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function member($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>1,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();

        $qtambahan=' and c.kdJabatan!=4';
        $cbJabatan=' where kdJabatan!=4';
        if($this->kdJabatan<2 && $this->kdJabatan<4){
            $qtambahan.=" and a.kdMember1='".$this->kdMember."' ";
        }else if($this->kdJabatan==4){
            $qtambahan='';
            $cbJabatan='';
        }
        $this->_['ddata']=$this->qexec->_func(_dmember($qtambahan));
        $this->_['dkantor']=$this->qexec->_func(_qcbKantor(""));
        $this->_['djabatan']=$this->qexec->_func(_qcbjabatan($cbJabatan));
        // return print_r($this->kdJabatan);

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }

    function produk($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>2,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();
        $this->_['ddata']=$this->qexec->_func(_dproduk($this->kdKantor,""));
        $this->_['dkantor']=$this->qexec->_func(_qcbKantor());

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function subProduk($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>2,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();

        $this->_['ddata']=$this->qexec->_func(_qcbproduk($this->kdKantor,""));
        foreach ($this->_['ddata'] as $i => $v) {
            $this->_['ddata'][$i]['sub']=$this->qexec->_func(_dsubProduk($this->kdKantor," and a.kdProduk=".$this->mbgs->_valforQuery($v['value']).""));
        }
        
        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function tahapan($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>2,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();
        $this->_['ddata']=$this->qexec->_func(_dtahapan($this->kdKantor,""));

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function persyaratan($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>2,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();
        $this->_['ddata']=$this->qexec->_func(_dsarat($this->kdKantor,""));

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function settahapan($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>2,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();

        $this->_['ddata']=$this->qexec->_func(_qcbproduk($this->kdKantor,""));
        foreach ($this->_['ddata'] as $i => $v) {
            $this->_['ddata'][$i]['sub']=$this->qexec->_func(_qcbsubProduk($this->qtbl['p-spro']['nm'],$this->kdKantor," and kdProduk=".$this->mbgs->_valforQuery($v['value']).""));
            if($i==0){
                foreach ($this->_['ddata'][$i]['sub'] as $i2 => $v2) {
                    if($i2==0){
                        $this->_['ddata'][$i]['sub'][$i2]['data']=$this->qexec->_func(_dtahapanGroup($this->kdKantor,$v['value'],$v2['value'],""));
                    }
                }
            }
            
        }
        $this->_['dtahapan']=$this->qexec->_func(_qcbtahapan($this->qtbl['p-taha']['nm'],$this->kdKantor,""));

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function setsarat($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>2,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();

        $this->_['ddata']=$this->qexec->_func(_qcbproduk($this->kdKantor,""));
        foreach ($this->_['ddata'] as $i => $v) {
            $this->_['ddata'][$i]['sub']=$this->qexec->_func(_qcbsubProduk($this->qtbl['p-spro']['nm'],$this->kdKantor," and kdProduk=".$this->mbgs->_valforQuery($v['value']).""));
            if($i==0){
                foreach ($this->_['ddata'][$i]['sub'] as $i2 => $v2) {
                    if($i2==0){
                        // return print_r(_dsaratGroup($this->kdKantor,$v['value'],$v2['value'],""));
                        $this->_['ddata'][$i]['sub'][$i2]['data']=$this->qexec->_func(_dsaratGroup($this->kdKantor,$v['value'],$v2['value'],""));
                    }
                }
            }
        }

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function setting($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>5,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();

        $this->_['slider']      =$this->qexec->_func("select * from slider order by id asc");

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }

    function pendaftaran($page){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>3,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();

        $this->_['ddata']=$this->qexec->_func(_qcbproduk($this->kdKantor,""));
        foreach ($this->_['ddata'] as $i => $v) {
            $this->_['ddata'][$i]['sub']=$this->qexec->_func(_qcbsubProduk($this->qtbl['p-spro']['nm'],$this->kdKantor," and kdProduk=".$this->mbgs->_valforQuery($v['value']).""));
        }
        // return print_r(_dpekerjaan($this->kdKantor,""));
        $qtambahan='';
        if($this->kdJabatan<3){
            $qtambahan=" and a.kdMember='".$this->kdMember."'";
        }
        $this->_['ddaftar']=$this->qexec->_func(_dpekerjaan($this->kdKantor," and a.final=0 ".$qtambahan));

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function hasilPekerjaan($page,$p){
        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>4,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();

        if($p=="null"){
            $date=explode("-",$this->mbgs->_gdate("Y-m"));
            $this->_['tgl']=$this->mbgs->_Kalender($date[0])[(int) $date[1]];
        }else{
            $baseEND=json_decode((base64_decode($p)));
            $this->_['tgl']=[
                "tglS"=>$baseEND->{'tglS'},
                "tglE"=>$baseEND->{'tglE'}
            ];
        }

        $qtambahan='';
        if($this->kdJabatan<3){
            $qtambahan=" and a.kdMember='".$this->kdMember."'";
        }
        $this->_['ddata']=$this->qexec->_func(_qcbsubProduk($this->qtbl['p-spro']['nm'],$this->kdKantor," "));
        foreach ($this->_['ddata'] as $i1 => $v1) {
            $this->_['ddata'][$i1]['data']=$this->qexec->_func(
                _dpekerjaan(
                    $this->kdKantor,
                    " and a.final=1 
                        and a.kdProduk='".$v1['kdProduk']."' 
                        and a.kdSub='".$v1['value']."'
                        and a.tglFinal BETWEEN '".$this->_['tgl']["tglS"]."' and '".$this->_['tgl']["tglE"]."'
                        ".$qtambahan."
                "));
        }
        $this->_['ddata']=array_merge(array(["value"=>"-","valueName"=>"All","selected"=>1]),$this->_['ddata']);
        

        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }
    function subPekerjaan($page,$p){
        $baseEND=json_decode((base64_decode($p)));
        // return print_r($baseEND->{'kdPekerjaan'});
        $kdPekerjaan=$baseEND->{'kdPekerjaan'};
        $kdProduk=$baseEND->{'kdProduk'};
        $kdSub=$baseEND->{'kdSub'};
        $final=$baseEND->{'final'};

        $this->load->helper("tmDashio_helper");
        $this->_=array_merge(
            $this->_,
            [
                "pgStart"=>"Login",
                "pgEnd"=>"Dashboard",
                "user"=>$this->nmMember,
                "kdJab"=>$this->kdJabatan,
                "idBody"=>"bodyTM",
                "ind"=>3,
                "index"=>0
            ]
        );
        $this->_=array_merge(
            $this->_
            ,
            _startTm($this->_)
        );
        $this->_['head'].=$this->mbgs->_getCss().$this->mbgs->_getJsMaster($page);
        $this->_['footer'] .=$this->mbgs->_getJsTabel();
        $this->_['ddata']=$this->qexec->_func(
            _dpekerjaanSub(
                $this->kdKantor,
                $kdPekerjaan,
                $kdProduk,
                $kdSub,
                " "
            )
        );
        $this->_['kdSarat']=_getKdSarat(); // untuk persyaratan 
        $this->_['dpilihan']=_getPilihan();
        $this->_['dpilihan1']=_getPilihan1();
        // return print_r($this->_['ddata']);
        if(strlen($this->_['ddata'][0]['kdPK'])>0){
            $this->_['ddata'][0]['dawal']=$this->qexec->_func(_dpekerjaan($this->kdKantor," and a.final=1 and a.kdPekerjaan='".$this->_['ddata'][0]['kdPK']."'"));
        }
        foreach ($this->_['ddata'] as $i => $v) {
            if($v['kdTahapan']==_getKdSarat()){
                $this->_['ddata'][$i]['sarat']=$this->qexec->_func(_dgetSaratGroup($this->kdKantor,$v['kdProduk'],$v['kdSub'],""));
            }
        }
        
        $this->_dinfo();
        $this->_['footer'].=$this->mbgs->_getJsChart();
        return print_r(json_encode($this->_));
    }

    function _dinfo(){
        $qtambahan='';
        if($this->kdJabatan<3){
            $qtambahan=" and b.kdMember='".$this->kdMember."'";
        }
        $this->_['dinfo']=$this->qexec->_func(_qcbproduk($this->kdKantor,""));
        foreach ($this->_['dinfo'] as $i => $v) {
            if($i==0){
                $this->_['dinfo'][0]['total']=0;
            }
            $this->_['dinfo'][$i]['sub']=$this->qexec->_func(
                _qcbsubPekerjaan(
                    $this->kdKantor,
                    " and a.kdProduk='".$v['value']."'
                        and b.final=0
                        ".$qtambahan."
                ")
            );
            
            $this->_['dinfo'][$i]['persen']=0;
            foreach ($this->_['dinfo'][$i]['sub'] as $i2 => $v2) {
                // 1
                $this->_['dinfo'][$i]['sub'][$i2]['total']=$this->qexec->_func("
                        select count(kdSub) as total
                        from pekerjaan b where kdProduk='".$v['value']."' 
                        and kdKantor='". $this->kdKantor."' 
                        and kdSub='".$v2['kdSub']."' 
                        ".$qtambahan."
                    ")[0]['total'];

                // 2
                $this->_['dinfo'][$i]['persen']+=$v2['persen'];
                if($i2==count($this->_['dinfo'][$i]['sub'])-1){
                    $this->_['dinfo'][$i]['persen']=round($this->_['dinfo'][$i]['persen']/count($this->_['dinfo'][$i]['sub']),2);
                }
            }
            $this->_['dinfo'][0]['total']+=$this->_['dinfo'][$i]['persen'];
        }
    }
}
