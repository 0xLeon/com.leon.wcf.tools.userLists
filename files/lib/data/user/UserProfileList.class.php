<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/UserSessionList.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

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
class UserProfileList extends UserSessionList {
	public $objectClassName = 'UserProfile';
	
	public function __construct() {
		$this->sqlSelects .= "		session.requestURI, ";
		$this->sqlSelects .= "		session.requestMethod, ";
		$this->sqlSelects .= "		session.ipAddress, ";
		$this->sqlSelects .= "		session.userAgent, ";
		$this->sqlSelects .= "		rank.*, ";
		$this->sqlSelects .= "		avatar.*, ";
		$this->sqlJoins .= " LEFT JOIN	wcf".WCF_N."_avatar avatar ";
		$this->sqlJoins .= " ON		(avatar.avatarID = user.avatarID) ";
		$this->sqlJoins .= " LEFT JOIN	wcf".WCF_N."_session session ";
		$this->sqlJoins .= " ON		(session.userID = user.userID AND session.packageID = ".PACKAGE_ID." AND session.lastActivityTime > ".(TIME_NOW - USER_ONLINE_TIMEOUT).") ";
		$this->sqlJoins .= " LEFT JOIN	wcf".WCF_N."_user_rank rank ";
		$this->sqlJoins .= " ON		(rank.rankID = user.rankID) ";
		
		if (WCF::getUser()->userID) {
			$this->sqlSelects .= "		hisWhitelist.userID AS buddy, ";
			$this->sqlSelects .= "		hisBlacklist.userID AS ignoredUser, ";
			$this->sqlJoins .= " LEFT JOIN	wcf".WCF_N."_user_whitelist hisWhitelist ";
			$this->sqlJoins .= " ON		(hisWhitelist.userID = user.userID AND hisWhitelist.whiteUserID = ".WCF::getUser()->userID." AND hisWhitelist.confirmed = 1) ";
			$this->sqlJoins .= " LEFT JOIN	wcf".WCF_N."_user_blacklist hisBlacklist ";
			$this->sqlJoins .= " ON		(hisBlacklist.userID = user.userID AND hisBlacklist.blackUserID = ".WCF::getUser()->userID.") ";
		}
		else {
			$this->sqlSelects .= "		0 AS buddy, ";
			$this->sqlSelects .= "		0 AS ignoredUser, ";
		}
		parent::__construct();
	}
}
