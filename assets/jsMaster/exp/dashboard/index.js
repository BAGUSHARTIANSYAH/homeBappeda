function _onload(data){
    $('#body').html(data.tmBody);
    myCode=data.code;
    _.dinfo=data.dinfo;
    // console.log(data.idBody);
    
    $('#bodyTM').html(_form());
    $('#footer').html(data.tmFooter+data.footer);
    // console.log();
    // ready();
    _startChart();
}
function _form() {
    // <img class="img-fluid d-block mx-auto" src="`+assert+`fs_css/bgForm.png" alt="sasasa"></img>
    return `
    
    <div class="page-header" style="padding: 20px; margin-top: 4%;">
        <div class="page-block">`
            +informasix()
            +_formData()
            +
        `</div>
    </div>`;
}
function informasix() {
    fhtml='';
    _.dinfo.forEach((v,i) => {
        fhtml+=`
            <div class="col-md-3 mb">
                <div class="white-panel bg-light  shadow">
                    `+_sejajar({
                        attrCol:`style=""`,
                        data:[{
                            isi:`
                            <p><img src="`+assert+v.logo+`" class="img-circle" height="85"></p>
                            <p><b>`+v.valueName+`</b></p>
                            `
                        },{
                            isi:_progressBar({
                                    cls:_bgMinMax(parseFloat(v.persen),true),
                                    value:v.persen+"%"
                                })
                                +_fi2Kolom({
                                    attr:'margin: auto;width: max-content;',
                                    cls:'',
                                    attrName:"",
                                    attrVal:"",
                                    data:[
                                        {
                                            valueName:'BERPROSES',
                                            value:_span({text:_$(v.sub.length),id:"span",cls:'badge bg-warning p-2 text-dark'})
                                        }
                                        ,{
                                            valueName:'Persentase',
                                            value:_span({text:v.persen+" %",id:"span",cls:'badge bg-success p-2 '})
                                        }
                                    ]
                                })
                        }]
                    })
                +`</div>
            </div>
        `;
    });
    return `
    <div class="row">
        `+fhtml+`
        <div class="col-md-3 mb">
            <div class="white-panel bg-light  shadow">
                <canvas id="pie" height="33vh" width="80vw"></canvas>
            </div>
        </div>

    </div>
    `;
}
function _startChart() {
    dt={
        nm:[],
        val:[],
        bg:[
            "#009FFF",
            "#ff6d00",
            "#2CFF00"
        ]
    };

    dt1={
        nm:['',],
        val:[0],
        bg:[
            "#009FFF",
            "#ff6d00",
            "#2CFF00"
        ]
    };
    dt2={
        nm:['',],
        val:[0],
        bg:[
            "#009FFF",
            "#ff6d00",
            "#2CFF00"
        ]
    };
    dt3={
        nm:['',],
        val:[0],
        bg:[
            "#009FFF",
            "#ff6d00",
            "#2CFF00"
        ]
    };
    dt4={
        val:[],
        bg:[
            "#009FFF",
            "#ff6d00",
            "#2CFF00"
        ]
    };
    _.dinfo.forEach((v,i) => {
        dt.nm.push(v.valueName);
        dt.val.push(parseFloat((v.persen*100)/_.dinfo[0].total).toFixed(2));

        dt4.val.push({value: (v.persen*100)/_.dinfo[0].total, label:v.valueName})

        if(v.sub!=undefined && v.sub.length>0){
            v.sub.forEach(v1=> {
                switch (i) {
                    case 0:
                        dt1.nm.push(v1.nmSub);
                        dt1.val.push(v1.total);
                    break;
                    case 1:
                        dt2.nm.push(v1.nmSub);
                        dt2.val.push(v1.total);
                    break;
                    case 2:
                        dt3.nm.push(v1.nmSub);
                        dt3.val.push(v1.total);
                    break;
                }
            });
        }
    });
    
    var ctx = document.getElementById("pie").getContext('2d');
    new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels:dt.nm,
                datasets: [{
                    backgroundColor:dt.bg,
                    data:dt.val,
                    
                }]
            },
            options: {
                legend: {
                    display: true,
                    position: 'bottom',
                        labels: {
                            fontColor: '#71748d',
                            fontFamily: 'Circular Std Book',
                            fontSize: 10,
                            text:"saass"
                        }
                }
        }
    });

    var ctx = document.getElementById("chartjs_bar").getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels:dt1.nm,
            datasets: [{
                label:'NOTARIS',
                data:dt1.val,
                backgroundColor:dt1.bg,
                        borderColor: "rgba(89, 105, 255,0.7)",
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                yAxes: [{

                }]
            },
                    legend: {
            display: true,
            position: 'bottom',

            labels: {
                fontColor: '#71748d',
                fontFamily: 'Circular Std Book',
                fontSize: 14,
            }
        },

        scales: {
            xAxes: [{
                ticks: {
                    fontSize: 14,
                    fontFamily: 'Circular Std Book',
                    fontColor: '#71748d',
                }
            }],
            yAxes: [{
                ticks: {
                    fontSize: 14,
                    fontFamily: 'Circular Std Book',
                    fontColor: '#71748d',
                }
            }]
        }
    }

                    
    });
    var ctx = document.getElementById("chartjs_barPPAT").getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels:dt2.nm,
            datasets: [{
                label:'PPAT',
                data:dt2.val,
                backgroundColor:dt2.bg,
                        borderColor: "rgba(89, 105, 255,0.7)",
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                yAxes: [{

                }]
            },
                    legend: {
            display: true,
            position: 'bottom',

            labels: {
                fontColor: '#71748d',
                fontFamily: 'Circular Std Book',
                fontSize: 14,
            }
        },

        scales: {
            xAxes: [{
                ticks: {
                    fontSize: 14,
                    fontFamily: 'Circular Std Book',
                    fontColor: '#71748d',
                }
            }],
            yAxes: [{
                ticks: {
                    fontSize: 14,
                    fontFamily: 'Circular Std Book',
                    fontColor: '#71748d',
                }
            }]
        }
    }});

    var ctx = document.getElementById("chartjs_barIBM").getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels:dt3.nm,
            datasets: [{
                label:'IMB',
                data:dt3.val,
                backgroundColor:dt3.bg,
                        borderColor: "rgba(89, 105, 255,0.7)",
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                yAxes: [{

                }]
            },
                    legend: {
            display: true,
            position: 'bottom',

            labels: {
                fontColor: '#71748d',
                fontFamily: 'Circular Std Book',
                fontSize: 14,
            }
        },

        scales: {
            xAxes: [{
                ticks: {
                    fontSize: 14,
                    fontFamily: 'Circular Std Book',
                    fontColor: '#71748d',
                }
            }],
            yAxes: [{
                ticks: {
                    fontSize: 14,
                    fontFamily: 'Circular Std Book',
                    fontColor: '#71748d',
                }
            }]
        }
    }});

    Morris.Donut({
        element: 'morris_donut',
        data: dt4.val,
     
        labelColor: '#2e2f39',
           gridTextSize: '14px',
        colors: dt4.bg,

        formatter: function(x) { return x + "%" },
          resize: true
    });
    
}
function _formData() {
    return `<div class="row m-2 shadow">`
                +_formIcon({
                    icon:'<i class="mdi mdi-file-check"></i>'
                    ,text:"<h3>Informasi Pekerjaan</h3>",
                    classJudul:' p-2 display-4',
                    id:"form1",
                    sizeCol:undefined,
                    bgHeader:"bg-info display-4 text-light",
                    attrHeader:`style="height: max-content;"`,
                    bgForm:"#fff; font-size: medium;",
                    isi:`
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card-body">
                                <canvas id="chartjs_bar"></canvas>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card-body">
                                <canvas id="chartjs_barPPAT"></canvas>
                            </div>
                        </div>


                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card-body">
                                <canvas id="chartjs_barIBM"></canvas>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card-body">
                                <div id="morris_donut"></div>
                            </div>
                        </div>
                    `
                })
            +`</div>`;
}