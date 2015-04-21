<?php
    /*
	Plugin Name:  integracoreb2b SOAP plugin
	Plugin URI: http://google.com
	Description: A little plugin to manage integracoreb2b SOAP plugin
	Author: Saif Taifur
	Version: 0.1
	Author URI: mailto:cowboysaif@hotmail.com
	*/
	
	add_action( 'woocommerce_thankyou', 'order_info' );
	
	function order_info( $order_id ) {
 
	// Lets grab the order
	$order = new WC_Order( $order_id );
	//shipping method
	 $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
 	$chosen_shipping = $chosen_methods[0]; 
	//variables for data
	$order_number = "DWG" . str_replace("#" , "" , $order->get_order_number());
	$order_date = str_replace(" ", "T", $order->order_date);

	
	
		  $xml_data = '<?xml version="1.0" encoding="utf-16"?>																					
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">																					
	<soap:Header>																				
		<AuthHeader MyAttribute="" xmlns="http://www.integracoreb2b.com/">																			
			<Username>drainwig</Username>																		
			<Password>qB2W{Z</Password>																		
			<Test>true</Test>																		
		</AuthHeader>																			
	</soap:Header>																				
	<soap:Body>																				
		<OrderImport xmlns="http://www.integracoreb2b.com/">																			
			<Orders>																		
				<Order>																	
					<SiteID>1</SiteID>																
					<OrderNumber>'.$order_number.'</OrderNumber>																
					<ShipToCustID>DRAINWIG</ShipToCustID>																
					<BillToCustID>DRAINWIG</BillToCustID>																
					<CreateDate>'.$order_date.'</CreateDate>																														
					<Carrier>'.$chosen_shipping.'</Carrier>																
					<CarrierServiceCode>FDX 11</CarrierServiceCode>																
					<ThirdPartyAccountNumber></ThirdPartyAccountNumber>																
					<PrepaidOrCollect>03</PrepaidOrCollect>																
					<ShipToContact></ShipToContact>																
					<ShipToName>'.$order->shipping_first_name." ". $order->shipping_last_name .'</ShipToName>																
					<ShipToAddr1>'.$order->shipping_address_1.'</ShipToAddr1>																
					<ShipToAddr2>'.$order->shipping_address_2.'</ShipToAddr2>																
					<ShipToAddr3></ShipToAddr3>																
					<ShipToCity>'.$order->shipping_city.'</ShipToCity>																
					<ShipToState>'.$order->shipping_state.'</ShipToState>																
					<ShipToZip>'.$order->shipping_postcode.'</ShipToZip>																
					<ShipToCountry>'.$order->shipping_country.'</ShipToCountry>																
					<ShipToTelephone>'.$order->billing_phone.'</ShipToTelephone>																
					<ShipToFax></ShipToFax>																
					<CustPoNum></CustPoNum>																
					<BillToPoNum></BillToPoNum>																
					<RecipientEmailAddress>'.$order->billing_email.'</RecipientEmailAddress>																
					<CustomData1>'.$order->customer_note.'</CustomData1>																
					<CustomData2></CustomData2>																
					<CustomData3></CustomData3>																
					<DropShip>T</DropShip>																
					<ResidentialFlag>T</ResidentialFlag>																
					<PackSlipRequired>T</PackSlipRequired>																
					<OrderDetails>';
					foreach ( $order->get_items() as $items ) {
						$product = new WC_product($items['product_id']); 
					$xml_data = $xml_data .' 																
						<OrderDetail>															
							<ItemID>'.$product->get_sku().'</ItemID>														
							<Quantity>'.$items["qty"].'</Quantity>														
							<SalePrice>'.$items["line_total"].'</SalePrice>														
						</OrderDetail>';
					}
																			
					$xml_data = $xml_data . '</OrderDetails>																
					<OrderNotes>																
						<OrderNote>															
							<NoteType>71</NoteType>														
							<NoteText>'.$order->customer_note.'</NoteText>														
						</OrderNote>															
					</OrderNotes>																
				</Order>																	
			</Orders>																		
		</OrderImport>																			
	</soap:Body>																			
</soap:Envelope>';

	  $URL = "http://www.integracoreb2b.com/IntCore/IncomingOrders.asmx";
	  
	  $ch = curl_init($URL);
	  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  $output = curl_exec($ch);
	  curl_close($ch);

	ob_start();
	$data = get_post($order_id);
	var_dump($data);
	$data = ob_get_clean();
	$f = fopen(WP_PLUGIN_DIR . '/integracoreb2b/order.txt' , "w");
	fwrite($f,$data);
	fclose($f);
	
	}
?>