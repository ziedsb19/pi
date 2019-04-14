//@ts-ignore
var ctx = <HTMLCanvasElement>document.getElementById('chart_js').getContext('2d');
const monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];
//@ts-ignore
$("#year_pickr").datepicker({
    format: "mm-yyyy",
    viewMode: "months",
    autoclose: true,
    minViewMode: 1
});

$(document).ready(function () {
    //@ts-ignore
    var dataList = new Array(12).fill(0);
    var labelArray = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'May', 'Juin',
        'Juillet', 'Aout',
        'Septembre',
        'October', 'November', 'December'
    ]

    //@ts-ignore
    let href = <string>document.getElementById('chart_js').dataset.href;
    $.get(href, function (data) {
        data = JSON.parse(data);
        data.forEach(function (item: any) {
            dataList[Number(item.month) - 1] = Number(item.count);
        });
        fillChart(labelArray, dataList, "nombre d'evenements par mois année " + new Date().getFullYear());
    });

    $("#year_pickr").change(function () {
        let date = <string>$(this).val();
        let arrayMY: string[];
        arrayMY = date.split("-");
        let month: number = Number(arrayMY[0]);
        let year: number = Number(arrayMY[1]);
        //@ts-ignore
        let labelArray: number[] = new Array(new Date(year, month, 0).getDate()).fill(0);
        //@ts-ignore
        labelArray = labelArray.map((i: number, index) => (i + index + 1) + " " + monthNames[month - 1]);
        $.post(href, { month: month, year: year }, function (data) {
            data = JSON.parse(data);
            //@ts-ignore
            dataList = new Array(new Date(year, month, 0).getDate()).fill(0);
            data.forEach(function (item: any) {
                dataList[Number(item.day) - 1] = Number(item.count);
            });
            fillChart(labelArray, dataList, "nombre d'evenements par jour mois : " + monthNames[month - 1] + " année : " + year);
        });
    });
});

function fillChart(labelArray: string[] | number[], array: number[], label: string) {
    $("#chart_js").val();
    //@ts-ignore
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelArray,
            datasets: [{
                label: label,
                data: array,
                backgroundColor: color,
                borderColor: color,
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                labels: {
                    fontColor: "#004a43",
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

function color(context: any) {
    var index = context.dataIndex;
    var value = context.dataset.data[index];
    if (index % 3 == 0)
        return '#009688';
    if (index % 2 == 0)
        return '#004a43';
    return '#007bff';
}