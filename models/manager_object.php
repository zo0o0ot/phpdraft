<?php
/**
 * Represents a PHPDraft "manager" object.
 *
 * Managers have many players (picks), and belong to a single draft.
 *
 * @property int $manager_id The unique identifier for this manager
 * @property int $draft_id Foreign key to the draft this manager belongs to
 * @property string $manager_name Textual display name for each manager
 * @property string $team_name Deprecated
 * @property tinyint $draft_order The order in which the manager makes a pick in the draft.
 */
class manager_object {
    public $manager_id;
    public $draft_id;
    public $manager_name;
    public $team_name;
    public $draft_order;

    public function __construct(array $properties = array()) {
        foreach($properties as $property => $value)
            if(property_exists('manager_object', $property))
                $this->$property = $value;
    }

    /**
     * Get single instance of a manager object
     * @param int $manager_id
     * @return manager_object 
     */
    public static function getManagerById($manager_id) {
        $sql = "SELECT * FROM managers WHERE manager_id = '" . $manager_id . "' LIMIT 1";
        $manager_result = mysql_query($sql);
        if($manager_row = mysql_fetch_array($manager_result))
            return new manager_object(array(
                'manager_id' => $manager_row['manager_id'],
                'draft_id' => $manager_row['draft_id'],
                'manager_name' => $manager_row['manager_name'],
                'team_name' => $manager_row['team_name'],
                'draft_order' => $manager_row['draft_order']
            ));
    }

    /**
     * Given a single draft ID, get all managers for that draft
     * @param int $draft_id
     * @return manager_object 
     */
    public static function getManagersByDraftId($draft_id) {
        $managers = array();
        $sql = "SELECT * FROM managers WHERE draft_id = '" . $draft_id . "' ORDER BY manager_name";
        $managers_result = mysql_query($sql);

        while($manager_row = mysql_fetch_array($managers_result)) {
            $managers[] = new manager_object(array(
                'manager_id' => $manager_row['manager_id'],
                'draft_id' => $manager_row['draft_id'],
                'manager_name' => $manager_row['manager_name'],
                'team_name' => $manager_row['team_name'],
                'draft_order' => $manager_row['draft_order']
            ));
        }

        return $managers;
    }

    /**
     * Given a single draft ID, get the number of managers currently connected to that draft.
     * @param int $draft_id
     * @return int $number_of_managers
     */
    public static function getCountOfManagersByDraftId($draft_id) {
        $sql = "SELECT * FROM managers WHERE draft_id = '" . $draft_id . "' ORDER BY manager_name";

        return mysql_num_rows(mysql_query($sql));
    }
}

?>