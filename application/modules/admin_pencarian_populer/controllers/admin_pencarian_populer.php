<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin_pencarian_populer extends MX_Controller  {
	
	var $table = "pencarian_populer";
	var $table_alias = "Pencarian Populer";
	var $uri_page = 6;
	var $per_page = 25;
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model("admin_".$this->table."/model_".$this->table, $this->table);
		$this->load->model("admin_pencarian_populer",'pencarian_populer');
		$this->lang->load('elemen_layout', 'indonesia');
	}
	
	public function setheader()
	{
		return Modules::run('layout/setheader');
	}

	public function setfooter()
	{
		return Modules::run('layout/setfooter');
	}
	 
	public function auth()
	{
		return Modules::run('auth/privateAuth');
	}
	
	public function forbiddenAuth()
	{
		return Modules::run('auth/forbiddenAuth');
	}
	
	function index()
	{
		$this->auth();
		$this->forbiddenAuth();
		$this->grid();
	}


	function grid()
	{
		$this->setheader();		
		$contents = $this->grid_content();

		$data = array(
				  'admin_url' => base_url(),
				  'contents' => $contents,
				  );
		$this->parser->parse('layout/contents.html', $data);
		
		$this->setfooter();
	}
	
	
	
	function grid_content()
	{	

		# gungun modified #
		$fields = $this->db->list_fields($this->table);
		# 

		#search
		$sch1_parm = rawurldecode($this->uri->segment(3));
		$sch1_parm = $sch1_parm != 'null' && !empty($sch1_parm) ? $sch1_parm : 'null';
		$sch1_val = $sch1_parm != 'null' ? $sch1_parm : '';

		$sch2_parm = rawurldecode($this->uri->segment(4));
		$sch2_parm = $sch2_parm != 'null' && !empty($sch2_parm) ? $sch2_parm : 'null';
		$sch2_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
		$sch2_select_arr = array($sch2_parm);
		$ref2 = Modules::run('widget/getStaticDropdown',$sch2_arr,$sch2_select_arr,2);
		
		
		$sch_path = rawurlencode($sch1_parm)."/".rawurlencode($sch2_parm);
		#end search

		#paging
		$get_page = $this->uri->segment(5);
		$uri_segment = $this->uri_page;
		$pg = $this->uri->segment($uri_segment);
		$per_page = !empty($get_page) ? $get_page : $this->per_page;
		$no = $go_pg = !$pg ? 0 : $pg;

		if(!$pg)
		{
			$lmt = 0;
			$pg = 1;
		}else{
			$lmt = $pg;
		}
		$path = base_url().$this->table."/pages/".$sch1_parm."/".$sch2_parm."/".$per_page;
		$jum_record = $this->pencarian_populer->getTotal($this->table,"ref_link",$sch1_parm,$sch2_parm);
		$paging = Modules::run("widget/page",$jum_record,$per_page,$path,$uri_segment);
		if(!$paging) $paging = "";
		$display_record = $jum_record > 0 ? "" : "display:none;";
		#end paging
		
		#record
		$query = $this->pencarian_populer->getList($this->table,"ref_link",$per_page,$lmt,$sch1_parm,$sch2_parm);
		$list = array();
		if($query->num_rows() > 0){
			foreach($query->result_array() as $key => $r)
			{
				$no++;

				// $title = $r->title;
				// $title = highlight_phrase($title, $sch1_parm, '<span style="color:#990000">', '</span>');
				// $publish = $r->publish == "Publish" ? "icon-ok-sign" : "icon-minus-sign";
				// $create_date = date("d/m/Y H:i",strtotime($r->create_date));

				$list[$key] 				= $r;
				$list[$key]['no']			= $no;
				$list[$key]['create_date'] 	= date("d/m/Y H:i",strtotime($r['create_date']));



				//$list[] = $rowData;
			}
		}	
		#end record


		$data = array(
				  'admin_url' => base_url(),
				  'paging'=>$paging,
				  'list'=>$list,
				  'jum_record'=>$jum_record,
				  'display_record'=>$display_record,
				  'sch1_parm'=>$sch1_parm,
				  'sch1_val'=>$sch1_val,
				  'sch2_parm'=>$sch2_parm,				  				  
				  'ref2'=>$ref2,
				  'sch_path'=>$sch_path,
				  'per_page'=>$per_page,
				  'pg'=>$go_pg,
				   'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				  'title_link'=>'admin_'.$this->table
				  );
		return $this->parser->parse("list.html", $data, TRUE);
	}
	
	function search()
	{
		$sch1 = rawurlencode($this->input->post('sch1'));
		$sch2 = rawurlencode($this->input->post('ref2'));				
		$per_page = rawurlencode($this->input->post('per_page'));
		
		$sch1 = empty($sch1) ? 'null' : $sch1;
		$sch2 = empty($sch2) ? 'null' : $sch2;
		
		redirect("admin_".$this->table."/pages/".$sch1."/".$sch2."/".$per_page);
	}
	
	
	function edit()
	{
		$this->setheader();		
		$id = $this->uri->segment(3);
		$contents = $this->edit_content($id);

		$add_edit = $id == 0 ? "Add" : "Edit";
		
		$data = array(
				  'admin_url' => base_url(),
				  'contents' => $contents,		  			  
				  'add_edit' => $add_edit
				  );
		$this->parser->parse('layout/contents.html', $data);
		
		$this->setfooter();
	}
	
	
	
	function edit_content($id)
	{
		$number = 0;
		$file_image = "";
		
		# gungun modified #
		$fields = $this->db->list_fields($this->table);
		# 

		if(is_numeric($id)){


			
			#set asset
			$ref2_arr = array("Not Publish"=>"Not Publish","Publish"=>"Publish");
			
			#record
			$q = $this->pencarian_populer->getDetail($this->table,$id);
			$list = array();
			if($q->num_rows() > 0){
				foreach($q->result_array() as $r){
					
					#image
					// if($r['file_image'])
					// {

					// 	$image = $r['file_image'];
					// 	$file_image = "
					// 	<a href='".base_url()."uploads/".$image."' rel='facebox'><img src='".base_url()."uploads/thumbs/".$image."' class='thumbnail'></a>
					// 	<br/>
					// 	<a href='".base_url().$this->table."/unlink/".$r->id."/".rawurlencode($image)."' class='btn'>delete</a><br/>
					// 	<input type='hidden' name='file_image_old' value='".$image."' id='file_image_old'>";
					// }
					#end image
	
					
					#ref dropdown no multi value
					//$ref2=null;
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : $r['publish'];
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown no multi value

					foreach ($fields as $field)
					{
					   $fieldRow[$field] = $this->session->flashdata("".$field."") ? $this->session->flashdata("".$field."") : $r["".$field.""];
					}
					$fieldRow['ref2'] = $ref2;
					
					$list[] = $fieldRow;
				}
			}else{
				
				
					#ref dropdown no multi value
					$ref2=null;
					$ref2_select_arr[0] = $this->session->flashdata("ref2") ? $this->session->flashdata("ref2") : "Not Publish";
					$ref2 = Modules::run('widget/getStaticDropdown',$ref2_arr,$ref2_select_arr,2);
					#end ref dropdown no multi value


					#ref dropdown multi value
					
				
					foreach ($fields as $field)
					{
					   $fieldRow[$field] = $this->session->flashdata("".$field."") ? $this->session->flashdata("".$field."") : "";
					}
					$fieldRow['ref2'] = $ref2;
					$fieldRow['id']=0;
					$list[] = $fieldRow;
			}
			#end record

	
			#notification
			$err = $this->session->flashdata("err") ? $this->session->flashdata("err") : "";
			$success = $this->session->flashdata("success") ? $this->session->flashdata("success") : "";
			$notif = array();
			$btn_plus = "display:none;";
			if(!empty($success)){
				$btn_plus = "";
				$notif[] = array(
									"notif_title"=>$success,
									"notif_class"=>"success fade in"
									);
			}else if(!empty($err)){
				$notif[] = array(
									"notif_title"=>$err,
									"notif_class"=>"alert-message error fade in"
									);
			}
			#end notification
		
			$data = array(
					  'base_url' => base_url(),
					  'admin_url' => base_url(),
					  'notif'=>$notif,
					  'btn_plus'=>$btn_plus,
					  'list'=>$list,
					  'title_head'=>ucfirst(str_replace('_',' ',$this->table_alias)),
				 	  'title_link'=>'admin_'.$this->table
					  );
			return $this->parser->parse("edit.html", $data, TRUE);
		}else{
			redirect("admin_".$this->table);
		}
	}
	
	function submit()
	{
		# gungun modified #
		$fields = $this->db->list_fields($this->table);
		$postData = $this->input->post();
		$postData['publish'] = $postData['ref2'];
		unset($postData['ref2']);
		# end gungun #

		$err = "";
		

		$ref2 = $this->input->post("ref2");
			
		$ordered = $this->input->post("ordered");
		$user_id = $this->session->userdata('adminID');
		$id = strip_tags($this->input->post("id"));



		# The image
		$file_image_old = $file_image_name = $file_image_tmp = "";
		// $file_image_old = strip_tags($this->input->post("file_image_old"));
		// $file_image_name = $_FILES["file"]["name"];
		// $file_image_tmp  = $_FILES["file"]["tmp_name"];
		# End the image
		
		$this->load->library('form_validation');

		unset($fields['create_date']);
		unset($fields['modify_date']);
		unset($fields['user_id']);
		unset($fields['modify_user_id']);
		
		foreach ($fields as $field) {
			if($field!='publish'&&$field!='create_date' && $field!='modify_date' && $field!='modify_user_id' && $field!='date2' && $field!='modify_user_id' && $field!='user_id'){
					$this->form_validation->set_rules("".$field."", "".$field."", "required");				
			}
		}
	
		if ($this->form_validation->run() == FALSE)
		{

			$this->session->set_flashdata("err",validation_errors());

			foreach ($this->input->post() as $key => $value) {
				$this->session->set_flashdata($key, $value);
			}

			
			redirect("admin_".$this->table."/edit/".$id);
		}else{
			if($id > 0)
			{
				$this->pencarian_populer->setUpdate($this->table,$postData,$file_image_tmp,$file_image_name,$file_image_old);
				$this->session->set_flashdata("success","Data saved successful");
				redirect("admin_".$this->table."/edit/".$id);
			}else{
				$id_term = $this->pencarian_populer->setInsert($this->table,$postData,$file_image_tmp,$file_image_name,$file_image_old);
				$last_id = $this->db->insert_id();
				
				$this->session->set_flashdata("success","Data inserted successful");
				redirect("admin_".$this->table."/edit/".$id_term);
			}
		}
	}
	
	function delete($id=0)
	{
		$del_status = $this->pencarian_populer->setDelete($this->table,$id);
		$response['id'] = $id;
		$response['status'] = $del_status;
		echo $result = json_encode($response);
		exit();
	}
	
	function unlink($id,$file_image)
	{
		$file_image = rawurldecode($file_image);
	
		$this->db->where("id",$id);
		$this->db->update($this->table,array("file_image"=>""));
		
		if(file_exists("uploads/".$file_image)){ unlink("uploads/".$file_image); }
		if(file_exists("uploads/thumbs/".$file_image)){ unlink("uploads/thumbs/".$file_image); }
		redirect($this->table."/edit/".$id);
	}

	function getTitle($id)
	{
		$title = "";
		$q = $this->contents->getDetail($this->table,$id)->row_array();
		if($q){
			$title['title'];
		}
		return $title;
	}
	
	function getRefDropdown($id,$name,$type=NULL)
	{
	
		$q = $this->pencarian_populer->getList($this->table,null,null,null);
		$list = array();
		foreach ($q->result() as $val) {
			$selected = $val->id == $id ? $selected = "selected='selected'" : "";	
			$list[]= array(
						'id' => $val->id,
						'title'=>$val->title,
						"selected"=>$selected
					 );
		}
		$data = array(
				"list"=>$list,
				"name"=>"ref".$name
				);
		return $this->parser->parse("layout/ref_dropdown".$type.".html", $data, TRUE);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */