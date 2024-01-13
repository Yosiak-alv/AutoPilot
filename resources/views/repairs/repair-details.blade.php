<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Reparacion # {{$repair->id}}</title>

		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
            *,
            *::after,
            *::before{
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }

            :root{
                --blue-color: #0c2f54;
                --dark-color: #535b61;
                --white-color: #fff;
            }

            ul{
                list-style-type: none;
            }
            ul li{
                margin: 2px 0;
            }

            /* text colors */
            .text-dark{
                color: var(--dark-color);
            }
            .text-blue{
                color: var(--blue-color);
            }
            .text-end{
                text-align: right;
            }
            .text-center{
                text-align: center;
            }
            .text-start{
                text-align: left;
            }
            .text-bold{
                font-weight: 700;
            }
            /* hr line */
            .hr{
                height: 1px;
                background-color: rgba(0, 0, 0, 0.1);
            }
            /* border-bottom */
            .border-bottom{
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            body{
                font-family: Arial, Helvetica, sans-serif;
                color: var(--dark-color);
                font-size: 14px;
            }
            .invoice-wrapper{
                min-height: 100vh;
                background-color: rgba(0, 0, 0, 0.1);
                padding-top: 20px;
                padding-bottom: 20px;
            }
            .invoice{
                max-width: 850px;
                margin-right: auto;
                margin-left: auto;
                background-color: var(--white-color);
                padding: 70px;
                border: 1px solid rgba(0, 0, 0, 0.2);
                border-radius: 5px;
                min-height: 920px;
            }
            .invoice-head-top-left img{
                width: 130px;
            }
            .invoice-head-top-right h3{
                font-weight: 500;
                font-size: 27px;
                color: var(--blue-color);
            }
            .invoice-head-middle, .invoice-head-bottom{
                padding: 16px 0;
            }
            .invoice-body{
                border: 1px solid rgba(0, 0, 0, 0.1);
                border-radius: 4px;
                overflow: hidden;
            }
            .invoice-body table{
                border-collapse: collapse;
                border-radius: 4px;
                width: 100%;
            }
            .invoice-body table td, .invoice-body table th{
                padding: 12px;
            }
            .invoice-body table tr{
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }
            .invoice-body table thead{
                background-color: rgba(0, 0, 0, 0.02);
            }
            .invoice-body-info-item{
                display: grid;
                grid-template-columns: 80% 20%;
                padding: 20px;
            }
            .invoice-body-info-item .info-item-td{
                padding: 12px;
                background-color: rgba(0, 0, 0, 0.02);
            }
            .invoice-foot{
                padding: 30px 0;
            }
            .invoice-foot p{
                font-size: 12px;
            }
            .invoice-btns{
                margin-top: 20px;
                display: flex;
                justify-content: center;
            }
            .invoice-btn{
                padding: 3px 9px;
                color: var(--dark-color);
                font-family: inherit;
                border: 1px solid rgba(0, 0, 0, 0.1);
                cursor: pointer;
            }

            .invoice-head-top, .invoice-head-middle, .invoice-head-bottom{
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                padding-bottom: 10px;
            }
            @media screen and (max-width: 992px){
                .invoice{
                    padding: 40px;
                }
            }

            @media screen and (max-width: 576px){
                .invoice-head-top, .invoice-head-middle, .invoice-head-bottom{
                    grid-template-columns: repeat(1, 1fr);
                }
                .invoice-head-bottom-right{
                    margin-top: 12px;
                    margin-bottom: 12px;
                }
                .invoice *{
                    text-align: left;
                }
                .invoice{
                    padding: 28px;
                }
            }

            .overflow-view{
                overflow-x: scroll;
            }
            .invoice-body{
                min-width: 600px;
            }
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title ">
                                    <img src = "{{public_path('/storage/img/Logo-ext.png')}}">

									<!-- <img
										src="https://sparksuite.github.io/simple-html-invoice-template/images/logo.png"
										style="width: 100%; max-width: 300px"
									/> -->
								</td>

								<td>
									<span class = "text-bold">Invoice #:</span> {{$repair->id}}<br />
									Creado: {{$repair->created_at}}<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td>
									{{$repair->work_shop->name}}.<br />
									{{$repair->work_shop->telephone}}.<br />
									{{$repair->work_shop->email}}.<br />
									{{$repair->work_shop->address}}.<br />
                                    <span class = "text-bold">Zona:</span> {{$repair->work_shop->state->name}}, {{$repair->work_shop->town->name}}, {{$repair->work_shop->district->name}}.<br />
								</td>

								<td>
									<span class = "text-bold">Auto:</span> {{$repair->car->model->name}}, {{$repair->car->model->brand->name}}.<br />
									<span class = "text-bold">Placas:</span> {{$repair->car->plates}}<br />
                                    <span class = "text-bold">VIN:</span>  {{$repair->car->VIN}}<br />
									<span class = "text-bold">Millaje:</span> {{$repair->car->current_mileage}}<br />									
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
            <div>
                <div class = "invoice-body">
                    <table>
                        <thead>
                            <tr>
                                <td class = "text-bold">Servicio</td>
                                <td class = "text-bold">Descripcion</td>
                                <td class = "text-bold">Precio</td>
                            </tr>
                        </thead>
                       
                        <tbody>
                            @foreach($repair->details as $detail)
                                <tr>
                                    <td>{{$detail->name}}</td>
                                    <td>{{$detail->description}}</td>
                                    <td class = "text-end">${{$detail->price}}</td>
                                </tr>
                            @endforeach                          
                        </tbody>
                    </table>
                    <div class = "invoice-body-bottom">
                        <div class = "invoice-body-info-item border-bottom">
                            <div class = "text-end">
                                <span class = "text-bold">Sub Total: </span>${{$repair->total}}
                            <div>
                            <div class = "text-end">
                                <span class = "text-bold">Total: </span>${{$repair->total}}
                            <div>
                        </div>
                    </div>
                </div>
            </div>
            <div class = "invoice-foot text-center">
                <p><span class = "text-bold text-center">NOTA:&nbsp;</span>Recibo generado por Computadora y no requiere firma física.</p>
            </div>
		</div>
	</body>
</html>