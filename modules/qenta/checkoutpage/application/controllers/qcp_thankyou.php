<?php
/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/shop_plugins/info/
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcp/blob/master/LICENSE
*/

/**
 * thankyou view extended class
 * overloads init function and resets referenceNr and storageId
 */
class qcp_thankyou extends qcp_thankyou_parent
{
	protected $_sMailError = null;

	/**
	 * Pending status
	 * @var string
	 */
	protected $_sPendingStatus = null;

	/**
	 * Template variable getter. Returns pending status
	 *
	 * @return string
	 */
	public function getPendingStatus()
	{
		return $this->_sPendingStatus;
	}
}