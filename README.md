# Solución de correo electrónico Docker-MailSuite
Docker MailSuite es una solución de correo electrónico implementada sobre docker que utiliza OpenLDAP como base de datos de usuario, MailAD como servidor de correo de código abierto y Roundcube como cliente de correo web.

[MailAD](https://github.com/stdevPavelmc/mailad-docker/), creado por [stdevPavelmc](https://github.com/stdevPavelmc) utiliza ActiveDirectory como base de datos de usuario, Docker MailSuite reemplaza Active Directory por OpenLdap, reduciendo los recursos necesarios y brindando una interfaz sencilla e intuitiva para la gestión de usuarios.

Docker MailSuite esta compuesto por 3 servicios:

## Instalación de Docker MailSuite
La implementacion de la solución se realiza en 3 etapas y en el orden reflejado a continuación:

### 1. OpenLDAP

1. Definir los valores de variables de entorno e iniciar el servicio.
2. Una vez iniciado el servicio, acceda a la URL https://hostname:8443/setup para realizar el provisionamiento.
   
   ![Provisionamiento OpenLdap](https://github.com/adrianrp1988/wharehouse/blob/main/Docker-MailSuite/1-OpenLdap-Setup-Auth.png?raw=true "OpenLdap Setup")
4. Durante el proceso de configuracion el asistente solicitara la creacion de una cuenta con permisos administrativos (en lo adelante "post.master"), que tendra como principal utilidad, la de permitir la administracion del servicio OpenLdap.

   ![Provisionamiento OpenLdap](https://github.com/adrianrp1988/wharehouse/blob/main/Docker-MailSuite/2-OpenLdap-Setup-AdminAccount.png?raw=true "OpenLdap Setup")
6. Una vez terminado el aprovisionamiento, se podra acceder a la herramienta de administracion del servicio OpenLdap a través de la URL https://hostname:8443/ usando las credenciales de la cuenta post.master.

   ![Provisionamiento OpenLdap](https://github.com/adrianrp1988/wharehouse/blob/main/Docker-MailSuite/3-OpenLdap-Auth.png?raw=true)
   ![Provisionamiento OpenLdap](https://github.com/adrianrp1988/wharehouse/blob/main/Docker-MailSuite/4-OpenLdap-Account%20Management.png?raw=true)

### 2. MailAD

MailAD es una solución para desplegar un servidor de correo electrónico funcional implementado sobre Postfix:

1. Definir los valores de variables de entorno teniendo en cuenta los siguientes campos:

      - LDAP_LINK_USER_CN: corresponde al valor de la variable de entorno LDAP_ADMIN_DN del servicio OpenLDAP. Sera la cuenta que MailAd usara para realizar las peticiones.
	
      - LDAP_LINK_USER_PASSWD: corresponde al valor de la variable de entorno LDAP_LDAP_ADMIN_PASSWORD_DN del servicio OpenLDAP.
	
      - MAIL_ADMIN_USER: post.master (solo el nombre de usuario). Cuenta identificada como administrador del servidor de correo
	
      - MAIL_ADMIN_PASSWORD: m41l4dm1n1s7r470r.!

3. Iniciar el servicio

### 3. Roundcube

Roundcube es un cliente webmail que se integra con MailAD. 
Para instalar Roundcube:

1. Define las variables de entorno. Los valores serán similares a los reflejados en el servicio MailAD.

¡Listo! Ahora puedes utilizar tu solución de correo electrónico.
