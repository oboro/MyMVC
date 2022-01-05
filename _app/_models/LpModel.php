<?php
class LpModel extends Model
{
	public function get( $partial_school = "" )
	{
		$db = DB_SHINRONAVI_YEAR;
		$query = <<<HERE
			SELECT id, name FROM $db.master_schools WHERE name  LIKE :partial_school
HERE;
		return $this->db->get_result( $this->db->do_query( $query, array( 'partial_school' => "%{$partial_school}%" ) ) );
	}
}

?>
