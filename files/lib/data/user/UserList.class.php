<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObjectList.class.php');
require_once(WCF_DIR.'lib/data/User.class.php');

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
class UserList extends DatabaseObjectList {
	/**
	 * List of users
	 * 
	 * @var array<User>
	 */
	public $users = array();
	
	/**
	 * Class name of the result object class
	 * 
	 * @var	string
	 */
	public $objectClassName = 'User';
	
	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT		COUNT(*) AS count
			FROM		wcf".WCF_N."_user user
			LEFT JOIN	wcf".WCF_N."_user_option_value user_option
			ON		(user_option.userID = user.userID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
		$row = WCF::getDB()->getFirstRow($sql);
		
		return $row['count'];
	}
	
	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT		".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
					user_option.*
					user.*
			FROM		wcf".WCF_N."_user user
			LEFT JOIN	wcf".WCF_N."_user_option_value user_option
			ON		(user_option.userID = user.userID)
			".$this->sqlJoins."
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->users[$row['userID']] = new $this->objectClassName(null, $row);
		}
	}
}
