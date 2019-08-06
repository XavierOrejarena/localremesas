#!/usr/bin/env php
<?php
$offset = -4*60*60; //converting 5 hours to seconds.
$dateFormat = "d-m-Y";
$timeNdate = gmdate($dateFormat, time()+$offset);

/*
 #########################################################
#### INTEGRACIÓN FÁCIL ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# ESTE CÓDIGO FUNCIONA PARA LA VERSIÓN ONLINE Y OFFLINE
# Visita www.nubefact.com/integracion para más información
+++++++++++++++++++++++++++++++++++++++++++++++++++++++

#########################################################
#### FORMA DE TRABAJO ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# PASO 1: Conseguir una RUTA y un TOKEN para trabajar con NUBEFACT (Regístrate o ingresa a tu cuenta en www.nubefact.com).
# PASO 2: Generar un archivo en formato .JSON o .TXT con una estructura que se detalla en este documento.
# PASO 3: Enviar el archivo generado a nuestra WEB SERVICE ONLINE u OFFLINE según corresponda usando la RUTA y el TOKEN.
# PASO 4: Generamos el archivo XML y PDF (Según especificaciones de la SUNAT) y te devolveremos INSTANTÁNEAMENTE los datos del documento generado.
# Para ver el documento generado ingresa a www.nubefact.com/login con tus datos de acceso, y luego a la opción "Ver Facturas, Boletas y Notas"
# IMPORTANTE: Enviaremos el XML generado a la SUNAT y lo almacenaremos junto con el PDF, XML y CDR en la NUBE para que tu cliente pueda consultarlo en cualquier momento, si así lo desea.
+++++++++++++++++++++++++++++++++++++++++++++++++++++++


#########################################################
#### PASO 1: CONSEGUIR LA RUTA Y TOKEN ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# - Regístrate gratis en www.nubefact.com/register
# - Ir la opción API (Integración).
# IMPORTANTE: Para que la opción API esté activada necesitas escribirnos a soporte@nubefact.com o llámanos al teléfono: 01 468 3535 (opción 2) o celular (WhatsApp) 955 598762.
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
 * 
 * 
 */

// RUTA para enviar documentos
$ruta = "https://api.nubefact.com/api/v1/9cf67f43-ca59-4228-8c0d-556e5cfdf973";
// $ruta = "https://demo.nubefact.com/api/v1/03989d1a-6c8c-4b71-b1cd-7d37001deaa0";

//TOKEN para enviar documentos
$token = "2128fefd62964efd9fb9d1d9c41ed642a2bf82d99bdd4977a18053f99ba8707f";
// $token = "d0a80b88cde446d092025465bdb4673e103a0d881ca6479ebbab10664dbc5677";

/*
#########################################################
#### PASO 2: GENERAR EL ARCHIVO PARA ENVIAR A NUBEFACT ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# - MANUAL para archivo JSON en el link: https://goo.gl/WHMmSb
# - MANUAL para archivo TXT en el link: https://goo.gl/Lz7hAq
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

$data = (object) array(
    "operacion" => "generar_comprobante",
    "tipo_de_comprobante" => "2",
    "serie" => "BBB1",
    "numero" => 3,
    "sunat_transaction" => 1,
    "cliente_tipo_de_documento" => 1,
    "cliente_numero_de_documento" => "41920371",
    "cliente_denominacion" => "JORGE LOPEZ",
    "cliente_direccion" => "JIRON COLOR 211",
    "cliente_email" => "",
    "cliente_email_1" => "",
    "cliente_email_2" => "",
    "fecha_de_emision" => "18-07-2019",
    "fecha_de_vencimiento" => "",
    "moneda" => "1",
    "tipo_de_cambio" => "",
    "porcentaje_de_igv" => "18.00",
    "descuento_global" => "",
    "total_descuento" => "",
    "total_anticipo" => "",
    "total_gravada" => "",
    "total_inafecta" => "600",
    "total_exonerada" => "",
    "total_igv" => "",
    "total_gratuita" => "",
    "total_otros_cargos" => "",
    "total" => "600",
    "percepcion_tipo" => "",
    "percepcion_base_imponible" => "",
    "total_percepcion" => "",
    "total_incluido_percepcion" => "",
    "detraccion" => "false",
    "observaciones" => "EJEMPLO JSON GENERAR CPE BOLETA 3 INAFECTAS",
    "documento_que_se_modifica_tipo" => "",
    "documento_que_se_modifica_serie" => "",
    "documento_que_se_modifica_numero" => "",
    "tipo_de_nota_de_credito" => "",
    "tipo_de_nota_de_debito" => "",
    "enviar_automaticamente_a_la_sunat" => "true",
    "enviar_automaticamente_al_cliente" => "false",
    "codigo_unico" => "",
    "condiciones_de_pago" => "",
    "medio_de_pago" => "",
    "placa_vehiculo" => "",
    "orden_compra_servicio" => "",
    "tabla_personalizada_codigo" => "",
    "formato_de_pdf" => "",
    "items" => array(
             "unidad_de_medida" => "NIU",
             "codigo" => "001",
             "descripcion" => "DETALLE DEL PRODUCTO",
             "cantidad" => "1",
             "valor_unitario" => "500",
             "precio_unitario" => "500",
             "descuento" => "",
             "subtotal" => "500",
             "tipo_de_igv" => 9,
             "igv" => "0",
             "total" => "500",
             "anticipo_regularizacion" => "false",
             "anticipo_documento_serie" => "",
             "anticipo_documento_numero" => "",
             "codigo_producto_sunat" => "10000000",
             "unidad_de_medida" => "ZZ",
             "codigo" => "001",
             "descripcion" => "DETALLE DEL SERVICIO",
             "cantidad" => "5",
             "valor_unitario" => "20",
             "precio_unitario" => "20",
             "descuento" => "",
             "subtotal" => "100",
             "tipo_de_igv" => 9,
             "igv" => "0",
             "total" => 100,
             "anticipo_regularizacion" => "false",
             "anticipo_documento_serie" => "",
             "anticipo_documento_numero" => "",
             "codigo_producto_sunat" => "20000000"
          )
        );
	
$data_json = json_encode($data);

/*
#########################################################
#### PASO 3: ENVIAR EL ARCHIVO A NUBEFACT ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# SI ESTÁS TRABAJANDO CON ARCHIVO JSON
# - Debes enviar en el HEADER de tu solicitud la siguiente lo siguiente:
# Authorization = Token token="8d19d8c7c1f6402687720eab85cd57a54f5a7a3fa163476bbcf381ee2b5e0c69"
# Content-Type = application/json
# - Adjuntar en el CUERPO o BODY el archivo JSON o TXT
# SI ESTÁS TRABAJANDO CON ARCHIVO TXT
# - Debes enviar en el HEADER de tu solicitud la siguiente lo siguiente:
# Authorization = Token token="8d19d8c7c1f6402687720eab85cd57a54f5a7a3fa163476bbcf381ee2b5e0c69"
# Content-Type = text/plain
# - Adjuntar en el CUERPO o BODY el archivo JSON o TXT
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

//Invocamos el servicio de NUBEFACT
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ruta);
curl_setopt(
	$ch, CURLOPT_HTTPHEADER, array(
	'Authorization: Token token="'.$token.'"',
	'Content-Type: application/json',
	)
);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$respuesta  = curl_exec($ch);
curl_close($ch);

/*
 #########################################################
#### PASO 4: LEER RESPUESTA DE NUBEFACT ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# Recibirás una respuesta de NUBEFACT inmediatamente lo cual se debe leer, verificando que no haya errores.
# Debes guardar en la base de datos la respuesta que te devolveremos.
# Escríbenos a soporte@nubefact.com o llámanos al teléfono: 01 468 3535 (opción 2) o celular (WhatsApp) 955 598762
# Puedes imprimir el PDF que nosotros generamos como también generar tu propia representación impresa previa coordinación con nosotros.
# La impresión del documento seguirá haciéndose desde tu sistema. Enviaremos el documento por email a tu cliente si así lo indicas en el archivo JSON o TXT.
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

$leer_respuesta = json_decode($respuesta, true);
if (isset($leer_respuesta['errors'])) {
	//Mostramos los errores si los hay
    echo $leer_respuesta['errors'];
} else {
	//Mostramos la respuesta
?>
<h2>RESPUESTA DE SUNAT</h2>
    <table border="1" style="border-collapse: collapse">
        <tbody>
            <tr><th>tipo:</th><td><?php echo $leer_respuesta['tipo_de_comprobante']; ?></td></tr>
            <tr><th>serie:</th><td><?php echo $leer_respuesta['serie']; ?></td></tr>
            <tr><th>numero:</th><td><?php echo $leer_respuesta['numero']; ?></td></tr>
            <tr><th>enlace:</th><td><?php echo $leer_respuesta['enlace']; ?></td></tr>
            <tr><th>aceptada_por_sunat:</th><td><?php echo $leer_respuesta['aceptada_por_sunat']; ?></td></tr>
            <tr><th>sunat_description:</th><td><?php echo $leer_respuesta['sunat_description']; ?></td></tr>
            <tr><th>sunat_note:</th><td><?php echo $leer_respuesta['sunat_note']; ?></td></tr>
            <tr><th>sunat_responsecode:</th><td><?php echo $leer_respuesta['sunat_responsecode']; ?></td></tr>
            <tr><th>sunat_soap_error:</th><td><?php echo $leer_respuesta['sunat_soap_error']; ?></td></tr>
            <tr><th>pdf_zip_base64:</th><td><?php echo $leer_respuesta['pdf_zip_base64']; ?></td></tr>
            <tr><th>xml_zip_base64:</th><td><?php echo $leer_respuesta['xml_zip_base64']; ?></td></tr>
            <tr><th>cdr_zip_base64:</th><td><?php echo $leer_respuesta['cdr_zip_base64']; ?></td></tr>
            <tr><th>codigo_hash:</th><td><?php echo $leer_respuesta['cadena_para_codigo_qr']; ?></td></tr>
            <tr><th>codigo_hash:</th><td><?php echo $leer_respuesta['codigo_hash']; ?></td></tr>
        </tbody>
    </table>
<?php
}