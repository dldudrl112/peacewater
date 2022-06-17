<?php


class Facility_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
//        $this->load->library('session');
        $this->load->database();
        $this->reserve_max_num = 9;

    }

    public function facilityListData($page = 1)
    {
        $limit=$this->per_page;
        $offset=$limit*($page-1);

        $sql="SELECT @rownum:=@rownum+1 num, idx, image, name, memo, reg_date from bbs_facility, (SELECT @rownum:= {$offset}) TMP ORDER BY idxs
              limit {$limit} offset {$offset}";
        return $this->db->query($sql)->result();
    }

    public function facilityTotalNum() {

        $sql = "select count(*) count From bbs_facility ";
        $count = $this->db->query($sql)->row();

        return $count->count;
    }

    public function facilityAddData($insert_data) {


//        $sql="SELECT idxs FROM history_menu order by idxs DESC LIMIT 1";
//        $idxs = $this->db->query($sql)->row()->idxs + 1;

        $sql="SELECT idxs FROM bbs_facility order by idxs DESC LIMIT 1";
        $obj_idxs = $this->db->query($sql)->row();
        if( !$obj_idxs) {
            $idxs = 1;
        } else {
            $idxs = $obj_idxs->idxs+1;
        }

        $insert_data['idxs'] = $idxs;
        $result = $this->db->insert('bbs_facility', $insert_data);
        if($result) {
            return array('return'=>true);
        }
        else {
            return array('return'=>false);
        }
    }

    public function facilityData($idx) {
        $array = array($idx);
        $sql="SELECT *
            FROM bbs_facility WHERE idx = ?";
        return $this->db->query($sql, $array)->row();
    }

    public function facilityEditData($update, $idx) {

        $this->db->where('idx', $idx);
        $result = $this->db->update('bbs_facility', $update);

        if($result) {
            return array('return'=>true);
        }
        else {
            return array('return'=>false);
        }
    }

    public function facilitydel($idx) {
        $array = array($idx);
        $sql= "DELETE FROM bbs_facility WHERE idx = ?";
        $result = $this->db->query($sql, $array);
        return array('return'=>$result);
    }

    public function facilityCount() {
        $sql="SELECT COUNT(*) count  FROM bbs_facility";
        return $this->db->query($sql)->row()->count;
    }

    public function facilityOrder($idxs, $idx) {
        for($i=0; $i<count($idxs); $i++) {
            $sql= "UPDATE bbs_facility SET idxs = ? WHERE idx = ?";
            $array = array($idxs[$i], $idx[$i]);
            $result = $this->db->query($sql, $array);
        }
        return array('return'=>$result);
    }

}