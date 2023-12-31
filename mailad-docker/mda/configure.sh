#!/bin/bash
set -e

# config file
CFILE=/etc/dovecot/config.local

if [ ! -f "${CFILE}" ] ; then
    # file doen't exist, fail
    echo "Config file: ${CFILE} does not exist, something failed in the initialization of the container!"
    exit 1
fi

# load config vars
. ${CFILE}

# # get the vars from the file
VARS=`cat ${CFILE} | cut -d "=" -f 1`

# replace the vars in the folders
for v in `echo $VARS | xargs` ; do
    # get the var content
    CONTp=${!v}

    # escape possible "/" in there
    CONT=`echo ${CONTp//\//\\\\/}`

    find "/etc/dovecot/" -type f -exec sed -i s/"\_$v\_"/"$CONT"/g {} \;
done

# Setup Let's Encript certs
if [[ ! -z "${LETSENCRYPT_FQDN}" ]] && [[ -f "/certs/${LETSENCRYPT_FQDN}.crt" ]] && [[ -f "/certs/${LETSENCRYPT_FQDN}.key" ]] && [[ -f "/certs/${LETSENCRYPT_FQDN}.dhparam.pem" ]]; then
    echo "Setup Let's Encrypt certs..."
    rm -f "/certs/mail.crt"
    rm -f "/certs/mail.key"
    rm -f "/certs/RSA2048.pem"
    cp "/certs/${LETSENCRYPT_FQDN}.crt" "/certs/mail.crt"
    cp "/certs/${LETSENCRYPT_FQDN}.key" "/certs/mail.key"
    cp "/certs/${LETSENCRYPT_FQDN}.dhparam.pem" "/certs/RSA2048.pem"
fi

# Setup Let's Encript certs
if [[ ! -z "${LETSENCRYPT_FQDN}" ]] && [[ -f "/certs/${LETSENCRYPT_FQDN}/fullchain.pem" ]] && [[ -f "/certs/${LETSENCRYPT_FQDN}/key.pem" ]]; then
    echo "Setup Let's Encrypt certs..."
    rm "/certs/mail.crt"
    rm "/certs/mail.key"
    cp "/certs/${LETSENCRYPT_FQDN}/fullchain.pem" "/certs/mail.crt"
    cp "/certs/${LETSENCRYPT_FQDN}/key.pem" "/certs/mail.key"
fi

# generate a Self-Signed cert/key if not present already on the /cert volume
if [ ! -f /certs/mail.crt -a ! -f /certs/mail.key ] ; then
    # no certs present.
    echo "WARNING! no cert found, generating a Self-Signed cert"

    openssl req -new -x509 -nodes -days 365 \
        -config /etc/dovecot/dovecot-openssl.cnf \
        -out /certs/mail.crt \
        -keyout /certs/mail.key
    chmod +r /certs/mail.key
fi

echo "Configuration success!"
