<?php


class Activity_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
//        $this->load->library('session');
        $this->load->database();
        $this->reserve_max_num = 9;

    }

    public function activityListData($page = 1,$category)
    {
        $limit=$this->per_page;
        $offset=$limit*($page-1);

        $sql="SELECT @rownum:=@rownum+1 num, idx,category, image, memo, reg_date from bbs_activity, (SELECT @rownum:= {$offset}) TMP 
                where bbs_activity.category = '$category'
                ORDER BY idxs
                limit {$limit} offset {$offset}";
        return $this->db->query($sql)->result();
    }

    public function activityTotalNum($category) {

        $sql = "select count(*) count From bbs_activity where category = '{$category}'";
        $count = $this->db->query($sql)->row();

        return $count->count;
    }

    public function activityAddData($insert_data) {


//        $sql="SELECT idxs FROM history_menu order by idxs DESC LIMIT 1";
//        $idxs = $this->db->query($sql)->row()->idxs + 1;

        $sql="SELECT idxs FROM bbs_activity order by idxs DESC LIMIT 1";
        $obj_idxs = $this->db->query($sql)->row();
        if( !$obj_idxs) {
            $idxs = 1;
        } else {
            $idxs = $obj_idxs->idxs+1;
        }

        $insert_data['idxs'] = $idxs;
        $result = $this->db->insert('bbs_activity', $insert_data);
        if($result) {
            return array('return'=>true);
        }
        else {
            return array('return'=>false);
        }
    }

    public function activityData($idx) {
        $array = array($idx);
        $sql="SELECT *
            FROM bbs_activity WHERE idx = ?";
        return $this->db->query($sql, $array)->row();
    }

    public function activityEditData($update, $idx) {

        $this->db->where('idx', $idx);
        $result = $this->db->update('bbs_activity', $update);

        if($result) {
            return array('return'=>true);
        }
        else {
            return array('return'=>false);
        }
    }

    public function activitydel($idx) {
        $array = array($idx);
        $sql= "DELETE FROM bbs_activity WHERE idx = ?";
        $result = $this->db->query($sql, $array);
        return array('return'=>$result);
    }

    public function activityCount($cate) {
        $sql="SELECT COUNT(*) count  FROM bbs_activity where category = '$cate'";
        return $this->db->query($sql)->row()->count;
    }

    public function activityOrder($idxs, $idx, $cate) {
        for($i=0; $i<count($idxs); $i++) {
            $sql= "UPDATE bbs_activity SET idxs = ? WHERE idx = ? and category = ?";
            $array = array($idxs[$i], $idx[$i], $cate);
            $result = $this->db->query($sql, $array);
        }
        return array('return'=>$result);
    }

}