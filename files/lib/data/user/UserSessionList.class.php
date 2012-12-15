<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/UserList.class.php');
require_once(WCF_DIR.'lib/system/session/UserSession.class.php');

/**
 * 
 * 
 * @author	Stefan Hahn
 * @copyright	2012 Stefan Hahn
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.leon.wcf.tools.userLists
 * @subpackage	data.user
 * @category 	Community Framework
 */
class UserSessionList extends UserList {
	public $objectClassName = 'UserSession';
	
	public function __construct() {
		$this->sqlSelects .= "		GROUP_CONCAT(DISTINCT groups.groupID ORDER BY groups.groupID ASC SEPARATOR ',') AS groupIDs, ";
		$this->sqlSelects .= "		GROUP_CONCAT(DISTINCT languages.languageID ORDER BY languages.languageID ASC SEPARATOR ',') AS languageIDs, ";
		$this->sqlJoins .= " LEFT JOIN	wcf".WCF_N."_user_to_groups groups ";
		$this->sqlJoins .= " ON		(groups.userID = user.userID) ";
		$this->sqlJoins .= " LEFT JOIN	wcf".WCF_N."_user_to_languages languages "
		$this->sqlJoins .= " ON		(languages.userID = user.userID) ";
		$this->sqlGroupBy .= "user.userID";
	}
}
