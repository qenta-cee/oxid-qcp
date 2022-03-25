#!/bin/bash

set -e

SHOP_ROOT_PATH='/var/www'
NGROK_SCRIPT_PATH='/usr/local/bin/ngrok.sh'

# If we are in Github plugin repo CI environment
CI_REPO_URL=${GITHUB_SERVER_URL}/${GITHUB_REPOSITORY}
if [[ ${CI_REPO_URL} == ${PLUGIN_URL//.git/} ]]; then
  PLUGIN_VERSION=${GITHUB_SHA}
  CI='true'
fi

echo "Waiting for DB host ${OXID_DB_HOST}"

while ! mysqladmin ping -h"${OXID_DB_HOST}" --silent; do
  sleep 10
done

function set_shop_url() {
  if [[ -n ${SHOP_URL} ]]; then
    local _url_ssl=$(sed 's,^\(https\?://\)\?\(.*\),https://\2,' <<< ${SHOP_URL})
    local _url_plain=$(sed 's,^\(https\?://\)\?\(.*\),http://\2,' <<< ${SHOP_URL})
  else
    export NGROK_HOST=$(${NGROK_SCRIPT_PATH} 443 https)
    local _url_ssl=https://${NGROK_HOST}
    local _url_plain=http://${NGROK_HOST}
  fi
  export SHOP_URL_SSL=${_url_ssl}
  export SHOP_URL_PLAIN=${_url_plain}
}

function install_core() {
  local V=${OXID_VERSION}
  [[ ${OXID_VERSION} == 'latest' ]] && V=''
  ls -l
  pwd
  rm -r ${SHOP_ROOT_PATH}/*
  yes | composer create-project --keep-vcs oxid-esales/oxideshop-project ${SHOP_ROOT_PATH} ${V}
  ln -s ${SHOP_ROOT_PATH}/source ${SHOP_ROOT_PATH}/html
  mv /home/oxid/config.inc.php ${SHOP_ROOT_PATH}/html/
}

function inject_sql() {
  local SQL_FILE_PATH=${1}
  mysql -h ${OXID_DB_HOST} -u ${OXID_DB_USER} -p${OXID_DB_PASS} ${OXID_DB_NAME} < ${SQL_FILE_PATH}
}

function update_admin_account() {
  local PW_QUERY="update oxuser set oxpassword = md5(concat('"${OXID_ADMIN_PASS}"',unhex(oxpasssalt))) where oxid = 'oxdefaultadmin';";
  local USER_QUERY="update oxuser set oxusername = '"${OXID_ADMIN_USER}"' where oxid = 'oxdefaultadmin';";
  echo ${PW_QUERY} | mysql -h ${OXID_DB_HOST} -u ${OXID_DB_USER} -p${OXID_DB_PASS} ${OXID_DB_NAME}
  echo ${USER_QUERY} | mysql -h ${OXID_DB_HOST} -u ${OXID_DB_USER} -p${OXID_DB_PASS} ${OXID_DB_NAME}
}

function setup_db() {
  inject_sql ${SHOP_ROOT_PATH}'/source/Setup/Sql/database_schema.sql'
}

function install_demodata() {
  inject_sql ${SHOP_ROOT_PATH}'/vendor/oxid-esales/oxideshop-demodata-ce/src/demodata.sql'
}

function install_plugin() {
  cp -r /modules/* ${SHOP_ROOT_PATH}/source/modules/
  local MODULE_SOURCE_PATH=${SHOP_ROOT_PATH}/source/modules/qenta/checkoutpage
  ${SHOP_ROOT_PATH}/vendor/bin/oe-console oe:module:install-configuration ${MODULE_SOURCE_PATH}
}

function setup_store() {
  rm -rf ${SHOP_ROOT_PATH}/source/Setup/
  ${SHOP_ROOT_PATH}/vendor/bin/oe-eshop-db_views_regenerate
}

function print_info() {
  echo
  echo '####################################'
  echo
  echo "Shop: ${SHOP_URL_SSL}"
  echo "Admin Panel: ${SHOP_URL_SSL}/admin/"
  echo "Plugin Config: ${SHOP_URL_SSL}/"
  echo "User: ${OXID_ADMIN_USER}"
  echo "Password: ${OXID_ADMIN_PASS}"
  echo
  echo '####################################'
  echo
}

function _log() {
  echo "${@}" >> /tmp/shop.log
}

set_shop_url
install_core
_log "oxid installed"
setup_db
_log "db setup done"
install_demodata
_log "demodata installed"
install_plugin
_log "plugin installed"
setup_store
_log "store set up"
update_admin_account
_log "changed default admin account credentials"

if [[ ${CI} != 'true' ]]; then
  print_info
fi

_log "url=https://${OXID_URL}"
_log "ready"

echo "ready" > /tmp/debug.log

apache2-foreground "$@"
